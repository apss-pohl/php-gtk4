// Gtk4\GdkEventSequence: an opaque identity on the fundamental registry. GTK uses the
// sequence as a *key* (one per touch point, NULL for the pointer) - gestures compare it by
// address, so a handle must keep the address, never copy the struct. There is nothing to ref:
// GTK owns the sequence for the duration of the touch, the handle is a name for it.
#include "php_gtk4.h"

#include "classes.h"
#include "core/fundamental.h"

using namespace phpgtk;

namespace {

// RefFn: the sequence has no refcount; the handle is the same address.
gpointer sequence_ref(gpointer p) {
  return p;
}

// UnrefFn: nothing to release.
void sequence_unref(gpointer) {}

}  // namespace

namespace phpgtk {

// MINIT: put GdkEventSequence on the fundamental registry (marshal's boxed arm finds it there).
void register_GdkEventSequence(zend_class_entry *ce) {
  register_fundamental(FundamentalClass{
      .type = GDK_TYPE_EVENT_SEQUENCE, .ce = ce, .ref = sequence_ref, .unref = sequence_unref});
}

}  // namespace phpgtk
