// run(): argv handling and the running-loop registry for ExceptionMode::Rethrow.
#include "core/error.h"
#include "core/globals.h"
#include "core/mainloop.h"

#include <string>
#include <vector>

namespace {
// QuitFn for the running-loop registry: g_application_quit() ends run().
void quit_app(gpointer data) {
  g_application_quit(G_APPLICATION(data));
}
}  // namespace
