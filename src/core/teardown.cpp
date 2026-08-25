#include "teardown.h"

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

// RSHUTDOWN: disconnect every live handler and destroy every armed source while Zend is up.
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
}

}  // namespace phpgtk
