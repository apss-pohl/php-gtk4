// Gtk4\GtkApplication - the preferred main loop.
#include "php_gtk4.h"
#include "core/mainloop.h"
#include "core/object.h"
#include "core/variant.h"
#include "core/collections.h"

#include <string>
#include <vector>

using namespace phpgtk;

/**
 * Gtk4\GtkApplication::__construct(?string $application_id = null, int $flags = 0)
 */
ZEND_METHOD(Gtk4_GtkApplication, __construct) {
  zend_string *id = nullptr;
  zend_long flags = 0;
  ZEND_PARSE_PARAMETERS_START(0, 2)
  Z_PARAM_OPTIONAL
  Z_PARAM_STR_OR_NULL(id)
  Z_PARAM_LONG(flags)
  ZEND_PARSE_PARAMETERS_END();
  if (id != nullptr && !g_application_id_is_valid(ZSTR_VAL(id))) {
    zend_argument_value_error(
        1, "is not a valid application id (reverse-DNS, e.g. \"org.example.App\")");
    RETURN_THROWS();
  }
  GtkApplication *app = gtk_application_new(id != nullptr ? ZSTR_VAL(id) : nullptr,
                                            static_cast<GApplicationFlags>(flags));
  attach_new(object_from_zval(ZEND_THIS), G_OBJECT(app));
}

// QuitFn for the running-loop registry: g_application_quit() ends run().
static void quit_app(gpointer data) {
  g_application_quit(G_APPLICATION(data));
}

/**
 * Gtk4\GtkApplication::run(array $argv = []): int
 *
 * Run the application (emits `startup`, `activate`, ...) until the last window closes or {@see
 * quit()} is called.
 */
ZEND_METHOD(Gtk4_GtkApplication, run) {
  HashTable *argv_ht = nullptr;
  ZEND_PARSE_PARAMETERS_START(0, 1)
  Z_PARAM_OPTIONAL
  Z_PARAM_ARRAY_HT(argv_ht)
  ZEND_PARSE_PARAMETERS_END();
  GtkApplication *gtk_app = PHPGTK_SELF(GtkApplication, GTK_TYPE_APPLICATION);
  GApplication *app = G_APPLICATION(gtk_app);

  // argv must stay valid for the whole run: copy the strings.
  std::vector<std::string> strings;
  if (argv_ht != nullptr) {
    zval *item;
    // NOLINTNEXTLINE(readability-math-missing-parentheses) Zend macro expansion
    ZEND_HASH_FOREACH_VAL(argv_ht, item) {
      zend_string *s = zval_get_string(item);
      strings.emplace_back(ZSTR_VAL(s), ZSTR_LEN(s));
      zend_string_release(s);
    }
    ZEND_HASH_FOREACH_END();
  }
  std::vector<char *> argv;
  argv.reserve(strings.size() + 1);
  for (auto &s : strings) argv.push_back(s.data());
  argv.push_back(nullptr);

  int status = 0;
  {
    RunningLoop running(quit_app, app);
    status = g_application_run(app, static_cast<int>(strings.size()),
                               strings.empty() ? nullptr : argv.data());
  }
  if (EG(exception) != nullptr) RETURN_THROWS();  // Rethrow mode
  RETURN_LONG(status);
}

/**
 * Gtk4\GtkApplication::quit(): void
 */
ZEND_METHOD(Gtk4_GtkApplication, quit) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkApplication *app = PHPGTK_SELF(GtkApplication, GTK_TYPE_APPLICATION);
  g_application_quit(G_APPLICATION(app));
}

/**
 * Gtk4\GtkApplication::add_window(GtkWindow $window): void
 */
ZEND_METHOD(Gtk4_GtkApplication, add_window) {
  zval *window;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_OBJECT_OF_CLASS(window, class_for_gtype_name("GtkWindow"))
  ZEND_PARSE_PARAMETERS_END();
  GtkApplication *app = PHPGTK_SELF(GtkApplication, GTK_TYPE_APPLICATION);
  GObject *w = unwrap(window, GTK_TYPE_WINDOW);
  if (w == nullptr) RETURN_THROWS();
  gtk_application_add_window(app, GTK_WINDOW(w));
}

/**
 * Gtk4\GtkApplication::get_active_window(): ?GtkWindow
 */
ZEND_METHOD(Gtk4_GtkApplication, get_active_window) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkApplication *app = PHPGTK_SELF(GtkApplication, GTK_TYPE_APPLICATION);
  GtkWindow *w = gtk_application_get_active_window(app);
  wrap(w != nullptr ? G_OBJECT(w) : nullptr, return_value);
}

/**
 * Gtk4\GtkApplication::get_application_id(): ?string
 */
ZEND_METHOD(Gtk4_GtkApplication, get_application_id) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkApplication *app = PHPGTK_SELF(GtkApplication, GTK_TYPE_APPLICATION);
  PHPGTK_RETURN_STRING_OR_NULL(g_application_get_application_id(G_APPLICATION(app)));
}

/**
 * Gtk4\GtkApplication::add_action(GAction $action): void
 */
ZEND_METHOD(Gtk4_GtkApplication, add_action) {
  zval *action;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_OBJECT_OF_CLASS(action, class_for_gtype_name("GObject"))
  ZEND_PARSE_PARAMETERS_END();
  GtkApplication *app = PHPGTK_SELF(GtkApplication, GTK_TYPE_APPLICATION);
  GObject *a = unwrap(action, G_TYPE_ACTION);
  if (a == nullptr) RETURN_THROWS();
  g_action_map_add_action(G_ACTION_MAP(app), G_ACTION(a));
}

/**
 * Gtk4\GtkApplication::remove_action(string $name): void
 */
ZEND_METHOD(Gtk4_GtkApplication, remove_action) {
  zend_string *name;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_STR(name)
  ZEND_PARSE_PARAMETERS_END();
  GtkApplication *app = PHPGTK_SELF(GtkApplication, GTK_TYPE_APPLICATION);
  g_action_map_remove_action(G_ACTION_MAP(app), ZSTR_VAL(name));
}

/**
 * Gtk4\GtkApplication::lookup_action(string $name): ?GAction
 */
ZEND_METHOD(Gtk4_GtkApplication, lookup_action) {
  zend_string *name;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_STR(name)
  ZEND_PARSE_PARAMETERS_END();
  GtkApplication *app = PHPGTK_SELF(GtkApplication, GTK_TYPE_APPLICATION);
  GAction *a = g_action_map_lookup_action(G_ACTION_MAP(app), ZSTR_VAL(name));
  wrap(a != nullptr ? G_OBJECT(a) : nullptr, return_value);
}

/**
 * Gtk4\GtkApplication::has_action(string $name): bool
 *
 * GActionGroup methods (has_action, list_actions, activate_action) work once the application is
 * registered, i.e. from `startup` on; add/remove/lookup_action work any time.
 */
ZEND_METHOD(Gtk4_GtkApplication, has_action) {
  zend_string *name;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_STR(name)
  ZEND_PARSE_PARAMETERS_END();
  GtkApplication *app = PHPGTK_SELF(GtkApplication, GTK_TYPE_APPLICATION);
  RETURN_BOOL(g_action_group_has_action(G_ACTION_GROUP(app), ZSTR_VAL(name)));
}

/**
 * Gtk4\GtkApplication::list_actions(): array
 */
ZEND_METHOD(Gtk4_GtkApplication, list_actions) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkApplication *app = PHPGTK_SELF(GtkApplication, GTK_TYPE_APPLICATION);
  gchar **names = g_action_group_list_actions(G_ACTION_GROUP(app));
  array_init(return_value);
  for (gchar **n = names; n != nullptr && *n != nullptr; n++)
    add_next_index_string(return_value, *n);
  g_strfreev(names);
}

/**
 * Gtk4\GtkApplication::activate_action(string $name, mixed $parameter = null): void
 */
ZEND_METHOD(Gtk4_GtkApplication, activate_action) {
  zend_string *name;
  zval *param = nullptr;
  ZEND_PARSE_PARAMETERS_START(1, 2)
  Z_PARAM_STR(name)
  Z_PARAM_OPTIONAL
  Z_PARAM_ZVAL(param)
  ZEND_PARSE_PARAMETERS_END();
  GtkApplication *app = PHPGTK_SELF(GtkApplication, GTK_TYPE_APPLICATION);
  auto *group = G_ACTION_GROUP(app);
  if (!g_action_group_has_action(group, ZSTR_VAL(name))) {
    zend_value_error("no action '%s' on this application", ZSTR_VAL(name));
    RETURN_THROWS();
  }
  const GVariantType *t = g_action_group_get_action_parameter_type(group, ZSTR_VAL(name));
  GVariant *v = nullptr;
  if (t != nullptr) {
    const bool null_given = param == nullptr || Z_TYPE_P(param) == IS_NULL;
    if (null_given && !g_variant_type_is_maybe(t)) {
      zend_argument_value_error(2, "is required: action '%s' takes a parameter of type %s",
                                ZSTR_VAL(name), g_variant_type_peek_string(t));
      RETURN_THROWS();
    }
    zval null_zv;
    ZVAL_NULL(&null_zv);
    v = php_to_variant(null_given ? &null_zv : param, t);
    if (v == nullptr) RETURN_THROWS();
    g_variant_ref_sink(v);
  }
  g_action_group_activate_action(group, ZSTR_VAL(name), v);
  if (v != nullptr) g_variant_unref(v);
  if (EG(exception) != nullptr) RETURN_THROWS();
}

/**
 * Gtk4\GtkApplication::get_windows(): array
 *
 * The application's windows, most recently focused first.
 */
ZEND_METHOD(Gtk4_GtkApplication, get_windows) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkApplication *app = PHPGTK_SELF(GtkApplication, GTK_TYPE_APPLICATION);
  glist_to_php(gtk_application_get_windows(app), GTK_TYPE_WINDOW, Transfer::None, return_value);
}
