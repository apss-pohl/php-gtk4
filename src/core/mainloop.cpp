#include "mainloop.h"

#include <utility>
#include <vector>

namespace phpgtk {

namespace {
struct Entry {
  QuitFn quit;
  gpointer data;
};
// The stack of currently running loops, innermost last.
std::vector<Entry> &stack() {
  static std::vector<Entry> loops;
  return loops;
}
}  // namespace

// Push on construction (a run() started), pop on destruction (it returned).
RunningLoop::RunningLoop(QuitFn quit, gpointer data) {
  stack().push_back({quit, data});
}
RunningLoop::~RunningLoop() {
  stack().pop_back();
}

// Ask every running loop to quit, innermost first.
void quit_running_loops() {
  // Iterate over a copy: quitting may run dispose handlers that re-enter here.
  const std::vector<Entry> loops = stack();
  for (auto it = loops.rbegin(); it != loops.rend(); ++it) it->quit(it->data);
}

}  // namespace phpgtk
