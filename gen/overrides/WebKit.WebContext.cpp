// WebKitURISchemeRequestCallback (notified scope): the PHP callable that answers a custom scheme
// lives in a Callback owned by WebKit. WebKit takes a scheme handler exactly once and never gives
// it back - there is no unregister call, and registering the same scheme twice is refused - so the
// handler outlives the request that installed it and its destroy notify may not run until the web
// context is finalized at process exit, with Zend already gone. A Callback is emalloc'd request
// memory, so request teardown *disarms* every handler while Zend is still up (releases the
// callable, frees the Callback, and forgets the context); what the late notify then frees is the
// GLib-allocated struct alone, and the trampoline answers nothing.
#include "core/callback.h"
#include "core/teardown.h"

#include <dlfcn.h>

namespace {

// Where a context keeps its live scheme handlers, for the teardown hook and the duplicate check.
const char *const URI_SCHEMES_KEY = "php-gtk4-uri-scheme-handlers";

// One registration. `cb` and `context` are null once request teardown has disarmed it; `context`
// is what the destroy notify needs to take the handler off the list, since WebKit hands the
// notify nothing but this struct.
struct SchemeHandler {
  Callback *cb;
  GObject *context;
  char *scheme;
};

// The context's handler list, created on first use and dropped with the context.
GPtrArray *scheme_handlers(GObject *context, bool create) {
  auto *handlers = static_cast<GPtrArray *>(g_object_get_data(context, URI_SCHEMES_KEY));
  if (handlers == nullptr && create) {
    handlers = g_ptr_array_new();
    g_object_set_data_full(context, URI_SCHEMES_KEY, handlers,
                           reinterpret_cast<GDestroyNotify>(g_ptr_array_unref));
  }
  return handlers;
}

// PHP dlclose()s the extension at the end of MSHUTDOWN, but WebKit finalizes its web context
// from an atexit handler that runs *after* that, and finalizing it calls the destroy notify
// below. With the module unmapped, that call is a jump into freed address space: a SIGSEGV after
// the script's last line, in a frame with no symbols. So a context that has been handed one of
// our function pointers pins the module, exactly the way pin_gtk_library() (src/gtk4.cpp) pins
// libgtk against the same unload. The handle is deliberately leaked - the reference is the point
// - and taking it per registration rather than once keeps this free of process-wide state.
void pin_this_module() {
  Dl_info info{};
  // dladdr wants a data pointer
  if (dladdr(reinterpret_cast<const void *>(&scheme_handlers), &info) != 0
      && info.dli_fname != nullptr) {
    void *handle = dlopen(info.dli_fname, RTLD_NOW | RTLD_NOLOAD | RTLD_NODELETE);
    (void)handle;
  }
}

// RFC 3986: a scheme is a letter followed by letters, digits, '+', '-' or '.'. WebKit refuses
// anything else ("Cannot register invalid URI scheme x") and keeps neither the handler nor the
// name, so the value never reaches a state PHP could observe.
bool scheme_is_wellformed(const zend_string *scheme) {
  const char *c = ZSTR_VAL(scheme);
  const char *end = c + ZSTR_LEN(scheme);
  if (c == end || g_ascii_isalpha(*c) == 0) return false;
  for (++c; c < end; ++c) {
    if (g_ascii_isalnum(*c) == 0 && *c != '+' && *c != '-' && *c != '.') return false;
  }
  return true;
}

// Whether `scheme` is already answered by a handler of this context.
bool scheme_is_registered(GObject *context, const char *scheme) {
  GPtrArray *handlers = scheme_handlers(context, false);
  if (handlers == nullptr) return false;
  for (guint i = 0; i < handlers->len; i++) {
    auto *handler = static_cast<SchemeHandler *>(g_ptr_array_index(handlers, i));
    if (g_strcmp0(handler->scheme, scheme) == 0) return true;
  }
  return false;
}

// WebKitURISchemeRequestCallback trampoline: (WebKitURISchemeRequest $request) -> void. The PHP
// side answers the request (finish(), finish_with_response() or finish_error()); a handler that
// throws, or one disarmed by request teardown, leaves it unanswered and WebKit fails the load.
void uri_scheme_request(WebKitURISchemeRequest *request, gpointer data) {
  auto *handler = static_cast<SchemeHandler *>(data);
  if (handler->cb == nullptr) return;  // disarmed by request teardown
  zval arg;
  wrap(G_OBJECT(request), &arg);
  zval retval;
  callback_invoke(handler->cb, 1, &arg, &retval);
  zval_ptr_dtor(&retval);
  zval_ptr_dtor(&arg);
  callback_drain();
}

// GDestroyNotify: WebKit dropped the handler (the context was finalized). Nothing here may touch
// Zend unless the handler is still armed, which means the request that installed it is alive.
void uri_scheme_request_free(gpointer data) {
  auto *handler = static_cast<SchemeHandler *>(data);
  if (handler->cb != nullptr) {
    GPtrArray *handlers = scheme_handlers(handler->context, false);
    if (handlers != nullptr) g_ptr_array_remove_fast(handlers, handler);
    teardown_untrack_notified(handler);
    callback_free(handler->cb);
  }
  g_free(handler->scheme);
  g_free(handler);
}

// teardown clear hook: disarm every handler of this context while Zend is up (see the file
// comment). The structs stay for WebKit's notify; the emptied list dies with the context.
void uri_scheme_request_clear(GObject *owner) {
  GPtrArray *handlers = scheme_handlers(owner, false);
  if (handlers == nullptr) return;
  while (handlers->len > 0) {
    auto *handler = static_cast<SchemeHandler *>(g_ptr_array_index(handlers, handlers->len - 1));
    g_ptr_array_remove_index_fast(handlers, handlers->len - 1);
    teardown_untrack_notified(handler);
    callback_free(handler->cb);  // parks the callable for the drain, frees the request memory
    handler->cb = nullptr;
    handler->context = nullptr;
  }
}

}  // namespace
