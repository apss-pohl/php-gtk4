#include "diagnostics.h"

#include "globals.h"

#include <atomic>
#include <cstdarg>
#include <cstring>
#include <string>
#include <utility>
#include <vector>

namespace phpgtk {

namespace {

// The thread a recorded message belongs to, claimed at RINIT. GLib logs from its own
// worker threads (gio, fontconfig), which have no request and must never touch the
// module globals; comparing against this is the only check that works before GTK is
// initialised, when mainloop.cpp does not know the GUI thread yet. Under ZTS the last
// request to start wins - php-gtk4 is CLI-only and GTK is single-threaded either way,
// so the others simply keep GLib's writer.
std::atomic<GThread *> owning_thread{nullptr};

// The zend_interrupt_function we displaced (another extension's, or none).
void (*previous_interrupt)(zend_execute_data *) = nullptr;

// Whether a message arriving here belongs to a request we may report into.
bool ours() {
  return owning_thread.load() == g_thread_self();
}

// Remember a message and ask the VM to stop at its next safe point. Runs inside GTK
// frames, so it only reads the engine (the executed file/line) and never calls into it.
void record(int type, std::string text) {
  Diagnostic entry;
  entry.type = type;
  entry.message = std::move(text);
  zend_string *file = zend_get_executed_filename_ex();
  if (file != nullptr) {
    entry.filename.assign(ZSTR_VAL(file), ZSTR_LEN(file));
    entry.lineno = zend_get_executed_lineno();
  }
  GTK4_G(diagnostics).push_back(std::move(entry));
  zend_atomic_bool_store_ex(&EG(vm_interrupt), true);
}

// GLogWriterFunc: GLib's diagnostics, recorded for the next safe point (see the header).
GLogWriterOutput writer(GLogLevelFlags level, const GLogField *fields, gsize n_fields, gpointer) {
  const auto bits = static_cast<unsigned>(level);
  const unsigned reportable = static_cast<unsigned>(G_LOG_LEVEL_CRITICAL) |
                              static_cast<unsigned>(G_LOG_LEVEL_WARNING) |
                              static_cast<unsigned>(G_LOG_LEVEL_MESSAGE);
  // Not our thread (no request to report into), or a level below advice: INFO and DEBUG stay
  // with GLib's own writer, which already honours G_MESSAGES_DEBUG.
  if (!ours() || (bits & reportable) == 0U) {
    return g_log_writer_default(level, fields, n_fields, nullptr);
  }
  const DiagnosticsMode mode = GTK4_G(diagnostics_mode);
  if (mode == DiagnosticsMode::Stderr) {
    return g_log_writer_default(level, fields, n_fields, nullptr);
  }
  if (mode == DiagnosticsMode::Off) return G_LOG_WRITER_HANDLED;

  const char *domain = nullptr;
  const char *message = nullptr;
  for (gsize i = 0; i < n_fields; i++) {
    if (strcmp(fields[i].key, "GLIB_DOMAIN") == 0) {
      domain = static_cast<const char *>(fields[i].value);
    } else if (strcmp(fields[i].key, "MESSAGE") == 0) {
      message = static_cast<const char *>(fields[i].value);
    }
  }
  if (message == nullptr) return g_log_writer_default(level, fields, n_fields, nullptr);

  // G_LOG_LEVEL_MESSAGE is GTK advising rather than refusing ("GtkDialog mapped without a
  // transient parent. This is discouraged."): an E_NOTICE, so it reaches the developer through
  // the same channel without claiming something went wrong.
  int type = E_NOTICE;
  if ((bits & static_cast<unsigned>(G_LOG_LEVEL_WARNING)) != 0U) {
    type = E_WARNING;
  } else if ((bits & static_cast<unsigned>(G_LOG_LEVEL_CRITICAL)) != 0U) {
    type = mode == DiagnosticsMode::Fatal ? E_ERROR : E_WARNING;
  }
  record(type, std::string(domain != nullptr ? domain : "GLib") + ": " + message);
  return G_LOG_WRITER_HANDLED;
}

// zend_interrupt_function: the VM's safe point. Report first, so a message keeps the
// frame it was raised under even if the extension we displaced switches frames.
void interrupt_handler(zend_execute_data *execute_data) {
  diagnostics_flush();
  if (previous_interrupt != nullptr) previous_interrupt(execute_data);
}

}  // namespace

// gtk4.diagnostics ini value -> mode.
bool diagnostics_mode_from_name(const char *name, size_t len, DiagnosticsMode *out) {
  const std::string value(name, len);
  if (value == "fatal") {
    *out = DiagnosticsMode::Fatal;
  } else if (value == "warning") {
    *out = DiagnosticsMode::Warning;
  } else if (value == "stderr") {
    *out = DiagnosticsMode::Stderr;
  } else if (value == "off") {
    *out = DiagnosticsMode::Off;
  } else {
    return false;
  }
  return true;
}

// Module init. The writer is process-wide and GLib allows exactly one, so it is installed
// unconditionally and decides per message what to do; every mode including Stderr is a
// branch inside it rather than a different installation.
void diagnostics_minit() {
  g_log_set_writer_func(writer, nullptr, nullptr);
  previous_interrupt = zend_interrupt_function;
  zend_interrupt_function = interrupt_handler;
}

// PHP dlclose()s this .so, so the engine must not keep pointing into it. (GLib offers no
// way to take the writer back; it is guarded by owning_thread, which nothing sets again.)
void diagnostics_mshutdown() {
  zend_interrupt_function = previous_interrupt;
  previous_interrupt = nullptr;
  owning_thread.store(nullptr);
}

// Claim this thread: from here on its GLib messages are reported instead of printed.
void diagnostics_request_init() {
  owning_thread.store(g_thread_self());
}

// Anything still recorded when the request ends never reached a safe point. Report it as a
// warning rather than losing it - a fatal here would only replace the shutdown that is
// already happening.
void diagnostics_request_shutdown() {
  for (Diagnostic &entry : GTK4_G(diagnostics)) entry.type = E_WARNING;
  diagnostics_flush();
  GTK4_G(diagnostics).clear();
  owning_thread.store(nullptr);
}

// php-gtk4's own report about a PHP-side problem: an E_WARNING at the next safe point.
void diagnostic(const char *format, ...) {
  va_list args;
  va_start(args, format);
  char *text = g_strdup_vprintf(format, args);
  va_end(args);
  const DiagnosticsMode mode = ours() ? GTK4_G(diagnostics_mode) : DiagnosticsMode::Stderr;
  switch (mode) {
    case DiagnosticsMode::Off:
      break;
    case DiagnosticsMode::Stderr:
      g_warning("%s", text);  // through the writer above, which passes Stderr on to GLib
      break;
    case DiagnosticsMode::Fatal:
    case DiagnosticsMode::Warning:
      record(E_WARNING, text);
      break;
  }
  g_free(text);
}

// Hand the recorded messages to the engine. Re-entrant by construction: reporting one runs
// a user error handler, which may call back into GTK and record another - that nested call
// returns immediately and the new message is reported by the next safe point.
void diagnostics_flush() {
  if (GTK4_G(diagnostics).empty() || GTK4_G(reporting_diagnostic)) return;
  GTK4_G(reporting_diagnostic) = true;
  std::vector<Diagnostic> batch;
  batch.swap(GTK4_G(diagnostics));
  for (const Diagnostic &entry : batch) {
    if (entry.filename.empty()) {
      zend_error(entry.type, "%s", entry.message.c_str());
      continue;
    }
    zend_string *file = zend_string_init(entry.filename.c_str(), entry.filename.size(), false);
    // An E_ERROR does not come back: zend_error() bails out by longjmp, so the release
    // below and the flag reset are skipped. Both die with the request that is ending -
    // the zend_string is request memory and the batch is freed with the arena.
    zend_error_at(entry.type, file, entry.lineno, "%s", entry.message.c_str());
    zend_string_release(file);
  }
  GTK4_G(reporting_diagnostic) = false;
}

}  // namespace phpgtk
