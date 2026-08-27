#include "enums.h"

#include <unordered_map>

namespace phpgtk {

namespace {
// GEnum GType -> the PHP enum class.
std::unordered_map<GType, zend_class_entry *> &registry() {
  static std::unordered_map<GType, zend_class_entry *> map;
  return map;
}
// GFlags GType -> the PHP constants class.
std::unordered_map<GType, zend_class_entry *> &flags_registry() {
  static std::unordered_map<GType, zend_class_entry *> map;
  return map;
}
}  // namespace

// MINIT: bind (verification happens in enums_verify()).
void register_enum(GType type, zend_class_entry *ce) {
  if (type == 0 || !G_TYPE_IS_ENUM(type)) {
    php_error_docref(nullptr, E_CORE_ERROR, "php-gtk4: %s is not a GEnum type", g_type_name(type));
    return;
  }
  registry()[type] = ce;
}

// MINIT: bind a flags constant class (verification happens in enums_verify()).
void register_flags(GType type, zend_class_entry *ce) {
  if (type == 0 || !G_TYPE_IS_FLAGS(type)) {
    php_error_docref(nullptr, E_CORE_ERROR, "php-gtk4: %s is not a GFlags type", g_type_name(type));
    return;
  }
  flags_registry()[type] = ce;
}

// RINIT, once: compare every PHP case / constant value with the C enum / flags (see enums.h).
void enums_verify() {
  static bool done = false;
  if (done) return;
  done = true;
  for (const auto &[type, ce] : flags_registry()) {
    auto *klass = static_cast<GFlagsClass *>(g_type_class_ref(type));
    zend_string *key;
    zval *const_zv;
    // NOLINTNEXTLINE(readability-math-missing-parentheses) Zend macro expansion
    ZEND_HASH_FOREACH_STR_KEY_VAL(CE_CONSTANTS_TABLE(ce), key, const_zv) {
      auto *c = static_cast<zend_class_constant *>(Z_PTR_P(const_zv));
      if (Z_TYPE(c->value) != IS_LONG) continue;
      const zend_long v = Z_LVAL(c->value);
      if (v != 0 && g_flags_get_first_value(klass, static_cast<guint>(v)) == nullptr) {
        php_error_docref(
            nullptr, E_ERROR, "php-gtk4: %s::%s = %ld is not a value of the C flags %s",
            ZSTR_VAL(ce->name), ZSTR_VAL(key), static_cast<long>(v), g_type_name(type));
      }
    }
    ZEND_HASH_FOREACH_END();
    g_type_class_unref(klass);
  }
  for (const auto &[type, ce] : registry()) {
    auto *klass = static_cast<GEnumClass *>(g_type_class_ref(type));
    zend_string *key;
    zval *case_zv;
    // NOLINTNEXTLINE(readability-math-missing-parentheses) Zend macro expansion
    ZEND_HASH_FOREACH_STR_KEY_VAL(CE_CONSTANTS_TABLE(ce), key, case_zv) {
      auto *c = static_cast<zend_class_constant *>(Z_PTR_P(case_zv));
      if (!(ZEND_CLASS_CONST_FLAGS(c) & ZEND_CLASS_CONST_IS_CASE)) continue;
      // Read the backing value from the constant AST instead of materialising the
      // case object (which would consume object handles before user code runs).
      zend_long v = 0;
      if (Z_TYPE(c->value) == IS_CONSTANT_AST) {
        zend_ast *ast = Z_ASTVAL(c->value);
        if (ast->kind != ZEND_AST_CONST_ENUM_INIT || ast->child[2] == nullptr) continue;
        v = Z_LVAL_P(zend_ast_get_zval(ast->child[2]));
      } else if (Z_TYPE(c->value) == IS_OBJECT) {
        v = Z_LVAL_P(zend_enum_fetch_case_value(Z_OBJ(c->value)));
      } else {
        continue;
      }
      if (g_enum_get_value(klass, static_cast<gint>(v)) == nullptr) {
        php_error_docref(nullptr, E_ERROR, "php-gtk4: %s::%s = %ld does not exist in the C enum %s",
                         ZSTR_VAL(ce->name), ZSTR_VAL(key), static_cast<long>(v),
                         g_type_name(type));
      }
    }
    ZEND_HASH_FOREACH_END();
    g_type_class_unref(klass);
  }
}

// Registry lookup by GType.
zend_class_entry *enum_class_for_type(GType type) {
  auto it = registry().find(type);
  return it == registry().end() ? nullptr : it->second;
}

// gint -> PHP enum case if registered and known, else a plain int.
void enum_to_php(GType type, gint value, zval *rv) {
  zend_class_entry *ce = enum_class_for_type(type);
  if (ce != nullptr) {
    zend_object *c = nullptr;
    if (zend_enum_get_case_by_value(&c, ce, value, nullptr, /* try */ true) == SUCCESS &&
        c != nullptr) {
      ZVAL_OBJ_COPY(rv, c);
      return;
    }
  }
  ZVAL_LONG(rv, value);
}

// PHP enum case (of the registered class) or int -> gint.
bool enum_from_php(zval *value, GType type, gint *out) {
  ZVAL_DEREF(value);
  if (Z_TYPE_P(value) == IS_OBJECT) {
    zend_class_entry *ce = enum_class_for_type(type);
    if (ce == nullptr || Z_OBJCE_P(value) != ce) {
      zend_type_error("expected %s%s, %s given", ce != nullptr ? ZSTR_VAL(ce->name) : "int",
                      ce != nullptr ? " or int" : "", ZSTR_VAL(Z_OBJCE_P(value)->name));
      return false;
    }
    *out = static_cast<gint>(Z_LVAL_P(zend_enum_fetch_case_value(Z_OBJ_P(value))));
    return true;
  }
  if (Z_TYPE_P(value) != IS_LONG) {
    zend_type_error("expected an enum case or int for %s, %s given", g_type_name(type),
                    zend_zval_value_name(value));
    return false;
  }
  *out = static_cast<gint>(Z_LVAL_P(value));
  return true;
}

}  // namespace phpgtk
