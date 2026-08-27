#include "mainloop.h"

#include <utility>
#include <vector>

namespace phpgtk {

namespace {
struct Entry {
  QuitFn quit;
  gpointer data;
  int depth;  // g_main_depth() when run() started; its own dispatches run at depth + 1
};
// The stack of currently running loops, innermost last.
std::vector<Entry> &stack() {
  static std::vector<Entry> loops;
  return loops;
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
