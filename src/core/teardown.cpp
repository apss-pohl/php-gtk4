#include "teardown.h"

#include "callback.h"

#include <unordered_map>
#include <unordered_set>
#include <vector>

namespace phpgtk {

namespace {
struct Handler {
  GObject *instance;
  gulong id;
};
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
// notified callable while Zend is up.
void teardown_request() {
  // Disconnecting finalizes the closure, which untracks it - iterate a copy.
  const std::vector<std::pair<GClosure *, Handler>> live(closures().begin(), closures().end());
  for (const auto &[closure, h] : live) {
    if (G_IS_OBJECT(h.instance) && g_signal_handler_is_connected(h.instance, h.id)) {
      g_signal_handler_disconnect(h.instance, h.id);
    } else {
      closures().erase(closure);
    }
  }
  const std::vector<guint> ids(sources().begin(), sources().end());
  for (guint id : ids) {
    GSource *source = g_main_context_find_source_by_id(nullptr, id);
    if (source != nullptr) g_source_destroy(source);  // runs the destroy notify -> untrack
    sources().erase(id);
  }
  const std::vector<std::pair<gpointer, Notified>> pending(notified().begin(), notified().end());
  for (const auto &[key, n] : pending) {
    if (G_IS_OBJECT(n.owner))
      n.clear(n.owner);  // runs the destroy notify -> untrack
    else
      notified().erase(key);
  }
  callback_drain();
}

}  // namespace phpgtk
