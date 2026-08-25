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
};

// Ask every registered loop (innermost first) to quit.
void quit_running_loops();

}  // namespace phpgtk
