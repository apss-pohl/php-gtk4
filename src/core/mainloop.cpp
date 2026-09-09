#include "mainloop.h"
#include "globals.h"

#include <atomic>
#include <utility>
#include <vector>

namespace phpgtk {

namespace {
using Entry = RunningLoopEntry;
// The stack of currently running loops, innermost last (module globals).
std::vector<Entry> &stack() {
  return GTK4_G(running_loops);
}
}  // namespace

// Push on construction (a run() started).
RunningLoop::RunningLoop(QuitFn quit, gpointer data) {
  stack().push_back({.quit = quit, .data = data, .depth = g_main_depth()});
}
// Pop on destruction (the run() returned).
RunningLoop::~RunningLoop() {
  stack().pop_back();
}

// Ask every running loop to quit, innermost first.
void quit_running_loops() {
  // Iterate over a copy: quitting may run dispose handlers that re-enter here.
  const std::vector<Entry> loops = stack();
  for (auto it = loops.rbegin(); it != loops.rend(); ++it) it->quit(it->data);
}

// A dispatch of the innermost registered loop runs at its depth + 1; deeper means a loop
// we do not know about is iterating in between.
bool in_unregistered_nested_loop() {
  // No registered run() at all: whatever PHP call drove this dispatch (main_context_iteration(),
  // a testing hook) is the next return to PHP, so a pending Throwable propagates directly.
  if (stack().empty()) return false;
  return g_main_depth() > stack().back().depth + 1;
}

}  // namespace phpgtk

namespace phpgtk {

namespace {
// The one thread allowed to talk to GTK: process-wide (GTK is), never a module global, and
// permanent - once a thread owned the GUI no other thread in the process can (GTK cannot be
// re-initialised). The one runtime-written process-wide static; atomic for ZTS.
std::atomic<GThread *> gui_thread = nullptr;
}  // namespace

// Gtk::init() succeeded on this thread; first caller wins, later inits on the same thread are fine.
void record_gui_thread() {
  GThread *expected = nullptr;
  gui_thread.compare_exchange_strong(expected, g_thread_self());
}

// Whether this thread is the one that owns GTK. Unlike assert_gui_thread() this never throws:
// a GLib log writer runs on whatever thread logged, where under ZTS there is no request and
// GTK4_G() would resolve through a TSRM slot that is not ours.
bool on_gui_thread() {
  GThread *owner = gui_thread.load();
  return owner != nullptr && owner == g_thread_self();
}

// Throws \Error if GTK was initialised on another thread. No-op before init (GTK is not in use
// yet) and always true on NTS, where there is only one PHP thread.
bool assert_gui_thread(const char *what) {
  GThread *owner = gui_thread.load();
  if (owner == nullptr || owner == g_thread_self()) return true;
  zend_throw_error(nullptr, "%s: GTK is single-threaded and was initialised on another thread",
                   what);
  return false;
}

}  // namespace phpgtk
