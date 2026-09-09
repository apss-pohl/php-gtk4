// Registry of running main loops (GMainLoop::run(), GtkApplication::run()) so
// the Rethrow exception mode can stop them when a callback throws.
#pragma once
#include "php_gtk4.h"

namespace phpgtk {

using QuitFn = void (*)(gpointer data);

// RAII: registers a quit function for the duration of a run() call.
class RunningLoop {
 public:
  RunningLoop(QuitFn quit, gpointer data);
  ~RunningLoop();
  RunningLoop(const RunningLoop &) = delete;
  RunningLoop &operator=(const RunningLoop &) = delete;
  RunningLoop(RunningLoop &&) = delete;
  RunningLoop &operator=(RunningLoop &&) = delete;
};

// Ask every registered loop (innermost first) to quit.
void quit_running_loops();

// True while a callback runs inside a main loop that is *not* registered here
// (GTK-internal g_main_context_iteration() loops: DnD, portals, or PHP driving
// GLib::main_context_iteration()) nested inside a registered run(). Quitting the
// registered loops then cannot make control return to PHP right away. With no
// registered run() the driving PHP call is the next return: not nested.
bool in_unregistered_nested_loop();

// Records the thread that ran Gtk::init(); every later GTK entry point that
// drives the main loop asserts it is on that thread. GTK is one per process
// and single-threaded, whatever PHP's build: with ZTS, only one request
// thread may own the GUI. Throws \Error and returns false on a violation.
bool assert_gui_thread(const char *what);
// Whether this is the thread Gtk::init() ran on. A GLib log writer is process-wide and GTK
// calls it from whatever thread logged, so anything that touches per-request state has to ask.
bool on_gui_thread();
// Gtk::init() succeeded on this thread: it is the GUI thread from now on (first caller wins).
void record_gui_thread();

}  // namespace phpgtk
