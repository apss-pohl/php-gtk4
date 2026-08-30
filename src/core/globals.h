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

struct Object;  // object.h

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

// Zend macro: a typedef struct, value-initialised by GINIT's placement new; `_PhpValue` is
// GObject's naming (G_DECLARE_FINAL_TYPE) for the C struct.
// NOLINTBEGIN(modernize-use-using,cppcoreguidelines-pro-type-member-init,bugprone-reserved-identifier)
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
std::unordered_set<phpgtk::Object *> held;         // handles their GObject holds a ref on (toggle)
std::unordered_map<gpointer, zend_object *> fundamental_handles;  // instance -> its live handle
bool shutting_down;            // RSHUTDOWN: no new holds, Zend is going away
phpgtk::Object *constructing;  // subtype.cpp: handle a g_object_new() is for
ZEND_END_MODULE_GLOBALS(gtk4)
// NOLINTEND(modernize-use-using,cppcoreguidelines-pro-type-member-init,bugprone-reserved-identifier)

ZEND_EXTERN_MODULE_GLOBALS(gtk4)
#define GTK4_G(v) ZEND_MODULE_GLOBALS_ACCESSOR(gtk4, v)
