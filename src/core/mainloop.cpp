#include "mainloop.h"
#include "globals.h"

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
  return !stack().empty() && g_main_depth() > stack().back().depth + 1;
}

}  // namespace phpgtk

namespace phpgtk {

namespace {
// The one thread allowed to talk to GTK: process-wide (GTK is), never a module global.
GThread *gui_thread = nullptr;
}  // namespace

// Gtk::init() succeeded on this thread; first caller wins, later inits on the same thread are fine.
void record_gui_thread() {
  if (gui_thread == nullptr) gui_thread = g_thread_self();
}

// Throws \Error if GTK was initialised on another thread. No-op before init (GTK is not in use
// yet) and always true on NTS, where there is only one PHP thread.
bool assert_gui_thread(const char *what) {
  if (gui_thread == nullptr || gui_thread == g_thread_self()) return true;
  zend_throw_error(nullptr, "%s: GTK is single-threaded and was initialised on another thread",
                   what);
  return false;
}

}  // namespace phpgtk
