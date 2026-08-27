// Module globals: every piece of per-request state the runtime keeps, in one
// struct so a ZTS PHP (several requests, each on its own thread) gets one copy
// per thread. NTS builds see a single instance; GTK4_G(x) is the only accessor.
//
// What is *not* here on purpose: the GType/class registries (object.cpp,
// enums.cpp, boxed.cpp, fundamental.cpp) - filled once in MINIT on the main
// thread, read-only afterwards, shared by every thread. And GTK itself, which
// is one per process and single-threaded regardless of PHP's threading model:
// see gui_thread below.
#pragma once
#include "php_gtk4.h"

#include <unordered_map>
#include <unordered_set>
#include <vector>

#include "error.h"

namespace phpgtk {

// mainloop.cpp: a run() in progress.
struct RunningLoopEntry {
  void (*quit)(gpointer data);
  gpointer data;
  int depth;  // g_main_depth() when run() started; its own dispatches run at depth + 1
};
// teardown.cpp: a connected PHP signal handler.
struct TrackedHandler {
  GObject *instance;
  gulong id;
};
// teardown.cpp: a notified-scope callable.
struct TrackedNotified {
  GObject *owner;
  void (*clear)(GObject *);
};

}  // namespace phpgtk

// NOLINTNEXTLINE(modernize-use-using) Zend macro expands to a typedef struct
ZEND_BEGIN_MODULE_GLOBALS(gtk4)
zval exception_handler;  // Gtk::set_exception_handler(); IS_UNDEF when none
zval parked_exception;   // Rethrow mode, nested unregistered loop: waiting for a boundary
phpgtk::ExceptionMode exception_mode;
std::vector<phpgtk::RunningLoopEntry> running_loops;  // innermost last
std::vector<zval> callback_graveyard;                 // callback_free() deferred releases
std::unordered_map<GClosure *, phpgtk::TrackedHandler> closures;
std::unordered_set<guint> sources;
std::unordered_map<gpointer, phpgtk::TrackedNotified> notified;
std::unordered_set<struct _PhpValue *> phpvalues;  // live PhpValue instances
ZEND_END_MODULE_GLOBALS(gtk4)

ZEND_EXTERN_MODULE_GLOBALS(gtk4)
#define GTK4_G(v) ZEND_MODULE_GLOBALS_ACCESSOR(gtk4, v)

namespace phpgtk {
// Records the thread that ran Gtk::init(); every later GTK entry point that
// drives the main loop asserts it is on that thread. GTK is one per process
// and single-threaded, whatever PHP's build: with ZTS, only one request
// thread may own the GUI. Throws \Error and returns false on a violation.
bool assert_gui_thread(const char *what);
void record_gui_thread();
}  // namespace phpgtk
