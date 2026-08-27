// Request-scoped teardown of everything that holds a PHP callable from C:
// signal closures and GLib sources. A GObject we do not own (a display, the
// application internals) may outlive the PHP request; if its closure were
// finalized after Zend shut down, releasing the callable would touch a dead
// allocator. So every closure/source registers here and RSHUTDOWN disconnects
// / removes whatever is still alive - their finalizers then run while Zend is
// up. The registries are per-request module globals (core/globals.h).
#pragma once
#include "php_gtk4.h"

namespace phpgtk {

void teardown_track_closure(GClosure *closure, GObject *instance, gulong handler_id);
void teardown_untrack_closure(GClosure *closure);
void teardown_track_source(guint source_id);
// A PHP callable installed on `owner` with a GDestroyNotify (draw funcs,
// filter/sort funcs - GIR scope "notified"). `clear(owner)` must make the
// owner drop the callable (which runs the notify -> untrack). Track from the
// installing method, untrack from the destroy notify.
void teardown_track_notified(gpointer key, GObject *owner, void (*clear)(GObject *));
void teardown_untrack_notified(gpointer key);
void teardown_untrack_source(guint source_id);

// RSHUTDOWN: disconnect all tracked handlers, remove all tracked sources,
// clear all tracked notified callbacks.
void teardown_request();

}  // namespace phpgtk
