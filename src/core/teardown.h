// Request-scoped teardown of everything that holds a PHP callable from C:
// signal closures and GLib sources. A GObject we do not own (a display, the
// application internals) may outlive the PHP request; if its closure were
// finalized after Zend shut down, releasing the callable would touch a dead
// allocator. So every closure/source registers here and RSHUTDOWN disconnects
// / removes whatever is still alive - their finalizers then run while Zend is
// up. Everything here is NTS-only state (one request at a time).
#pragma once
#include "php_gtk4.h"

namespace phpgtk {

void teardown_track_closure(GClosure *closure, GObject *instance, gulong handler_id);
void teardown_untrack_closure(GClosure *closure);
void teardown_track_source(guint source_id);
void teardown_untrack_source(guint source_id);

// RSHUTDOWN: disconnect all tracked handlers, remove all tracked sources.
void teardown_request();

}  // namespace phpgtk
