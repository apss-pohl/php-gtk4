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
// registered loops then cannot make control return to PHP right away.
bool in_unregistered_nested_loop();

}  // namespace phpgtk
