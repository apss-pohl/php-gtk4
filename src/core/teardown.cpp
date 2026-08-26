#include "teardown.h"

#include "callback.h"

#include <unordered_map>
#include <unordered_set>
#include <vector>

namespace phpgtk {

namespace {
// A connected PHP signal handler: the instance it is connected to and its handler id.
struct Handler {
  GObject *instance;
  gulong id;
};
// Live closures created by connect(), keyed by the GClosure.
std::unordered_map<GClosure *, Handler> &closures() {
  static std::unordered_map<GClosure *, Handler> map;
  return map;
}
// Ids of armed GLib sources created from PHP callables.
std::unordered_set<guint> &sources() {
  static std::unordered_set<guint> set;
  return set;
}
struct Notified {
  GObject *owner;
  void (*clear)(GObject *);
};
// Notified-scope callables, keyed by their Callback pointer.
std::unordered_map<gpointer, Notified> &notified() {
  static std::unordered_map<gpointer, Notified> map;
  return map;
}
}  // namespace

// Called by connect(): remember a closure until its finalize notifier untracks it.
void teardown_track_closure(GClosure *closure, GObject *instance, gulong handler_id) {
  closures()[closure] = {.instance = instance, .id = handler_id};
}

// Called by the closure finalize notifier.
void teardown_untrack_closure(GClosure *closure) {
  closures().erase(closure);
}

// Called by GLib::idle_add()/timeout_add().
void teardown_track_source(guint source_id) {
  sources().insert(source_id);
}

// Called by the source destroy notify.
void teardown_untrack_source(guint source_id) {
  sources().erase(source_id);
}

// Called by the installing method (set_draw_func, set_filter_func, ...).
void teardown_track_notified(gpointer key, GObject *owner, void (*clear)(GObject *)) {
  notified()[key] = {.owner = owner, .clear = clear};
}

// Called by the callable's destroy notify.
void teardown_untrack_notified(gpointer key) {
  notified().erase(key);
}

// RSHUTDOWN: disconnect every live handler, destroy every armed source and clear every
// notified callable while Zend is up. Every entry's pointer is valid as long as the entry
// exists (finalizing the instance/owner runs the finalize/destroy notify, which untracks),
// so each step takes the *current* first entry rather than iterating a snapshot: one
// disconnect/clear may finalize other tracked objects and remove their entries too.
void teardown_request() {
  while (!closures().empty()) {
    const auto [closure, h] = *closures().begin();
    closures().erase(closure);  // in case the disconnect does not finalize it right away
    if (g_signal_handler_is_connected(h.instance, h.id)) {
      g_signal_handler_disconnect(h.instance, h.id);
    }
  }
  while (!sources().empty()) {
    const guint id = *sources().begin();
    sources().erase(id);
    GSource *source = g_main_context_find_source_by_id(nullptr, id);
    if (source != nullptr) g_source_destroy(source);  // runs the destroy notify
  }
  while (!notified().empty()) {
    const auto [key, n] = *notified().begin();
    notified().erase(key);
    n.clear(n.owner);  // runs the destroy notify
  }
  callback_drain();
}

}  // namespace phpgtk
