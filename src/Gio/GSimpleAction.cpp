// Gtk4\GSimpleAction (+ the GAction interface methods it implements)
#include "php_gtk4.h"
#include "core/object.h"
#include "core/variant.h"

using namespace phpgtk;

/**
 * Gtk4\GSimpleAction::__construct(string $name, ?string $parameter_type = null, mixed $state =
 * null)
 */
ZEND_METHOD(Gtk4_GSimpleAction, __construct) {
  zend_string *name;
  zend_string *ptype = nullptr;
  zval *state = nullptr;
  ZEND_PARSE_PARAMETERS_START(1, 3)
  Z_PARAM_STR(name)
  Z_PARAM_OPTIONAL
  Z_PARAM_STR_OR_NULL(ptype)
  Z_PARAM_ZVAL(state)
  ZEND_PARSE_PARAMETERS_END();
  if (!g_action_name_is_valid(ZSTR_VAL(name))) {
    zend_argument_value_error(1, "is not a valid action name");
    RETURN_THROWS();
  }
  if (ptype != nullptr && !g_variant_type_string_is_valid(ZSTR_VAL(ptype))) {
    zend_argument_value_error(2, "is not a valid GVariant type string");
    RETURN_THROWS();
  }
  const GVariantType *vt = ptype != nullptr ? G_VARIANT_TYPE(ZSTR_VAL(ptype)) : nullptr;
  GSimpleAction *action;
  if (state != nullptr && Z_TYPE_P(state) != IS_NULL) {
    GVariant *sv = php_to_variant(state, nullptr);
    if (sv == nullptr) RETURN_THROWS();
    action = g_simple_action_new_stateful(ZSTR_VAL(name), vt, sv);
  } else {
    action = g_simple_action_new(ZSTR_VAL(name), vt);
  }
  attach_new(object_from_zval(ZEND_THIS), G_OBJECT(action));
}

/**
 * Gtk4\GSimpleAction::get_name(): string
 */
ZEND_METHOD(Gtk4_GSimpleAction, get_name) {
  ZEND_PARSE_PARAMETERS_NONE();
  GAction *a = PHPGTK_SELF(GAction, G_TYPE_ACTION);
  RETURN_STRING(g_action_get_name(a));
}

/**
 * Gtk4\GSimpleAction::get_enabled(): bool
 */
ZEND_METHOD(Gtk4_GSimpleAction, get_enabled) {
  ZEND_PARSE_PARAMETERS_NONE();
  GAction *a = PHPGTK_SELF(GAction, G_TYPE_ACTION);
  RETURN_BOOL(g_action_get_enabled(a));
}

/**
 * Gtk4\GSimpleAction::set_enabled(bool $enabled): void
 */
ZEND_METHOD(Gtk4_GSimpleAction, set_enabled) {
  bool enabled;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_BOOL(enabled)
  ZEND_PARSE_PARAMETERS_END();
  GSimpleAction *a = PHPGTK_SELF(GSimpleAction, G_TYPE_SIMPLE_ACTION);
  g_simple_action_set_enabled(a, enabled);
}

/**
 * Gtk4\GSimpleAction::get_parameter_type(): ?string
 */
ZEND_METHOD(Gtk4_GSimpleAction, get_parameter_type) {
  ZEND_PARSE_PARAMETERS_NONE();
  GAction *a = PHPGTK_SELF(GAction, G_TYPE_ACTION);
  const GVariantType *t = g_action_get_parameter_type(a);
  if (t == nullptr) RETURN_NULL();
  gchar *s = g_variant_type_dup_string(t);
  RETVAL_STRING(s);
  g_free(s);
}

/**
 * Gtk4\GSimpleAction::get_state(): mixed
 */
ZEND_METHOD(Gtk4_GSimpleAction, get_state) {
  ZEND_PARSE_PARAMETERS_NONE();
  GAction *a = PHPGTK_SELF(GAction, G_TYPE_ACTION);
  GVariant *state = g_action_get_state(a);
  variant_to_php(state, return_value);
  if (state != nullptr) g_variant_unref(state);
}

/**
 * Gtk4\GSimpleAction::set_state(mixed $state): void
 *
 * Set the state directly (emits `notify::state`, not `change-state`).
 */
ZEND_METHOD(Gtk4_GSimpleAction, set_state) {
  zval *state;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_ZVAL(state)
  ZEND_PARSE_PARAMETERS_END();
  GSimpleAction *a = PHPGTK_SELF(GSimpleAction, G_TYPE_SIMPLE_ACTION);
  const GVariantType *t = g_action_get_state_type(G_ACTION(a));
  if (t == nullptr) {
    zend_throw_exception_ex(spl_ce_LogicException, 0,
                            "GSimpleAction::set_state(): action '%s' is stateless",
                            g_action_get_name(G_ACTION(a)));
    RETURN_THROWS();
  }
  GVariant *v = php_to_variant(state, t);
  if (v == nullptr) RETURN_THROWS();
  g_simple_action_set_state(a, v);
}

/**
 * Gtk4\GSimpleAction::activate(mixed $parameter = null): void
 *
 * Activate as if through an action group; emits `activate`.
 */
ZEND_METHOD(Gtk4_GSimpleAction, activate) {
  zval *param = nullptr;
  ZEND_PARSE_PARAMETERS_START(0, 1)
  Z_PARAM_OPTIONAL
  Z_PARAM_ZVAL(param)
  ZEND_PARSE_PARAMETERS_END();
  GAction *a = PHPGTK_SELF(GAction, G_TYPE_ACTION);
  const GVariantType *t = g_action_get_parameter_type(a);
  GVariant *v = nullptr;
  if (t != nullptr) {
    const bool null_given = param == nullptr || Z_TYPE_P(param) == IS_NULL;
    if (null_given && !g_variant_type_is_maybe(t)) {
      zend_argument_value_error(1, "is required: action '%s' takes a parameter of type %s",
                                g_action_get_name(a), g_variant_type_peek_string(t));
      RETURN_THROWS();
    }
    zval null_zv;
    ZVAL_NULL(&null_zv);
    v = php_to_variant(null_given ? &null_zv : param, t);
    if (v == nullptr) RETURN_THROWS();
    g_variant_ref_sink(v);
  } else if (param != nullptr && Z_TYPE_P(param) != IS_NULL) {
    zend_argument_value_error(1, "must be null: action '%s' takes no parameter",
                              g_action_get_name(a));
    RETURN_THROWS();
  }
  g_action_activate(a, v);
  if (v != nullptr) g_variant_unref(v);
  if (EG(exception) != nullptr) RETURN_THROWS();
}
