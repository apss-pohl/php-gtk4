// Action-group bookkeeping for activate_action(): the groups insert_action_group() put on a
// widget, by prefix, so the parameter of a `prefix.action` can be converted to the type the
// group declares for it - the GTK action muxer that resolves such names is private API.
#include <cstring>
#include <string>

namespace {

// qdata key of a widget's inserted groups: GHashTable prefix -> GActionGroup (a ref of ours;
// the muxer holds its own).
GQuark inserted_groups_quark() {
  static GQuark q = g_quark_from_static_string("php-gtk4-inserted-action-groups");
  return q;
}

// insert_action_group(): remember the group under `prefix`, or forget it (group == nullptr).
void remember_inserted_group(GtkWidget *widget, const char *prefix, GActionGroup *group) {
  auto *groups =
      static_cast<GHashTable *>(g_object_get_qdata(G_OBJECT(widget), inserted_groups_quark()));
  if (groups == nullptr) {
    if (group == nullptr) return;
    groups = g_hash_table_new_full(g_str_hash, g_str_equal, g_free, g_object_unref);
    g_object_set_qdata_full(G_OBJECT(widget), inserted_groups_quark(), groups,
                            reinterpret_cast<GDestroyNotify>(g_hash_table_unref));
  }
  if (group == nullptr) {
    g_hash_table_remove(groups, prefix);
  } else {
    g_hash_table_insert(groups, g_strdup(prefix), g_object_ref(group));
  }
}

// The parameter type of `action` in `group`; nullptr when the group has no such action.
const GVariantType *group_parameter_type(GActionGroup *group, const char *action) {
  if (g_action_group_has_action(group, action) == FALSE) return nullptr;
  return g_action_group_get_action_parameter_type(group, action);
}

// The parameter type `name` expects when activated on `widget`, resolved the way GTK's action
// muxer resolves the name: the class actions of the widget and of every ancestor
// (gtk_widget_class_query_action), a group inserted under the name's prefix on any of them,
// and at a window the application window itself ("win.") and its registered application
// ("app."). nullptr when nothing answers to the name: the value is then converted by inference
// and GTK reports the unknown action as it always did.
const GVariantType *widget_action_parameter_type(GtkWidget *widget, const char *name) {
  const char *dot = std::strchr(name, '.');
  const std::string prefix = dot != nullptr ? std::string(name, dot - name) : std::string();
  const char *unprefixed = dot != nullptr ? dot + 1 : nullptr;
  for (GtkWidget *w = widget; w != nullptr; w = gtk_widget_get_parent(w)) {
    GtkWidgetClass *klass = GTK_WIDGET_GET_CLASS(w);
    for (guint i = 0;; i++) {
      GType owner = 0;
      const char *action_name = nullptr;
      const GVariantType *parameter_type = nullptr;
      const char *property_name = nullptr;
      if (gtk_widget_class_query_action(klass, i, &owner, &action_name, &parameter_type,
                                        &property_name) == FALSE) {
        break;
      }
      if (std::strcmp(action_name, name) == 0) return parameter_type;
    }
    if (unprefixed == nullptr) continue;
    if (auto *groups =
            static_cast<GHashTable *>(g_object_get_qdata(G_OBJECT(w), inserted_groups_quark()))) {
      if (auto *group = static_cast<GActionGroup *>(g_hash_table_lookup(groups, prefix.c_str()))) {
        return group_parameter_type(group, unprefixed);
      }
    }
    // NOLINTNEXTLINE(bugprone-assignment-in-if-condition) GTK_IS_WINDOW() macro expansion
    if (!GTK_IS_WINDOW(w)) continue;
    // NOLINTNEXTLINE(bugprone-assignment-in-if-condition) G_IS_ACTION_GROUP() macro expansion
    if (prefix == "win" && G_IS_ACTION_GROUP(w)) {
      return group_parameter_type(G_ACTION_GROUP(w), unprefixed);
    }
    if (prefix == "app") {
      GtkApplication *app = gtk_window_get_application(GTK_WINDOW(w));
      if (app != nullptr && g_application_get_is_registered(G_APPLICATION(app)) == TRUE) {
        return group_parameter_type(G_ACTION_GROUP(app), unprefixed);
      }
    }
  }
  return nullptr;
}

}  // namespace
