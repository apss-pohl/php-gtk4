// Gtk4\GMainLoop - a bare main loop on the default context. Not a GObject
// (GMainLoop is a refcounted GLib struct), so it has its own object layout.
#include "php_gtk4.h"
#include "core/classes.h"
#include "core/mainloop.h"

namespace {

struct MainLoopObject {
  GMainLoop *loop;
  zend_object std;
};

zend_object_handlers handlers;

// zend_object -> our embedding struct.
MainLoopObject *from_zend(zend_object *o) {
  return reinterpret_cast<MainLoopObject *>(reinterpret_cast<char *>(o) -
                                            XtOffsetOf(MainLoopObject, std));
}

// create_object handler: every GMainLoop handle owns a fresh loop on the default context.
zend_object *create_object(zend_class_entry *ce) {
  auto *self = static_cast<MainLoopObject *>(zend_object_alloc(sizeof(MainLoopObject), ce));
  self->loop = g_main_loop_new(nullptr, FALSE);
  zend_object_std_init(&self->std, ce);
  object_properties_init(&self->std, ce);
  self->std.handlers = &handlers;
  return &self->std;
}

// free_obj handler: quit a still-running loop (handle dropped from inside a callback), then unref.
void free_obj(zend_object *o) {
  MainLoopObject *self = from_zend(o);
  if (self->loop != nullptr) {
    if (g_main_loop_is_running(self->loop)) g_main_loop_quit(self->loop);
    g_main_loop_unref(self->loop);
  }
  zend_object_std_dtor(o);
}

// QuitFn for the running-loop registry (ExceptionMode::Rethrow stops loops through this).
void quit_loop(gpointer data) {
  g_main_loop_quit(static_cast<GMainLoop *>(data));
}

}  // namespace

/**
 * Gtk4\GMainLoop::__construct()
 */
ZEND_METHOD(Gtk4_GMainLoop, __construct) {
  ZEND_PARSE_PARAMETERS_NONE();
}

/**
 * Gtk4\GMainLoop::run(): void
 *
 * Run until {@see quit()}. Reentrant runs are refused.
 */
ZEND_METHOD(Gtk4_GMainLoop, run) {
  ZEND_PARSE_PARAMETERS_NONE();
  GMainLoop *loop = from_zend(Z_OBJ_P(ZEND_THIS))->loop;
  if (g_main_loop_is_running(loop)) {
    zend_throw_exception(spl_ce_LogicException, "GMainLoop::run(): this loop is already running",
                         0);
    RETURN_THROWS();
  }
  // Keep the loop alive while running even if PHP drops the last handle.
  g_main_loop_ref(loop);
  {
    phpgtk::RunningLoop running(quit_loop, loop);
    g_main_loop_run(loop);
  }
  g_main_loop_unref(loop);
  // Rethrow mode: a callback left its Throwable pending; it now propagates
  // from this run() call.
}

/**
 * Gtk4\GMainLoop::quit(): void
 */
ZEND_METHOD(Gtk4_GMainLoop, quit) {
  ZEND_PARSE_PARAMETERS_NONE();
  g_main_loop_quit(from_zend(Z_OBJ_P(ZEND_THIS))->loop);
}

/**
 * Gtk4\GMainLoop::is_running(): bool
 */
ZEND_METHOD(Gtk4_GMainLoop, is_running) {
  ZEND_PARSE_PARAMETERS_NONE();
  RETURN_BOOL(g_main_loop_is_running(from_zend(Z_OBJ_P(ZEND_THIS))->loop));
}

namespace phpgtk {
// MINIT: install the object handlers on the class entry.
void register_GMainLoop_handlers(zend_class_entry *ce) {
  memcpy(&handlers, &std_object_handlers, sizeof(zend_object_handlers));
  handlers.offset = XtOffsetOf(MainLoopObject, std);
  handlers.free_obj = free_obj;
  handlers.clone_obj = nullptr;
  ce->create_object = create_object;
}
}  // namespace phpgtk
