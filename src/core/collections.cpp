#include "collections.h"
#include "marshal.h"

#include "boxed.h"
#include "object.h"

namespace phpgtk {

namespace {

// One element -> zval; Full transfer releases the element afterwards.
void element_to_php(gpointer element, GType type, Transfer transfer, zval *rv) {
  if (element == nullptr) {
    ZVAL_NULL(rv);
    return;
  }
  if (type == G_TYPE_STRING) {
    ZVAL_STRING(rv, static_cast<const char *>(element));
    if (transfer == Transfer::Full) g_free(element);
    return;
  }
  if (g_type_is_a(type, G_TYPE_OBJECT)) {
    wrap(G_OBJECT(element), rv);
    if (transfer == Transfer::Full) g_object_unref(element);
    return;
  }
  if (G_TYPE_IS_BOXED(type)) {
    wrap_boxed(type, element, rv);
    if (transfer == Transfer::Full) g_boxed_free(type, element);
    return;
  }
  ZVAL_NULL(rv);
  zend_type_error("cannot convert list element of type %s", g_type_name(type));
}

}  // namespace

// GList -> list.
void glist_to_php(GList *list, GType element_type, Transfer transfer, zval *rv) {
  array_init(rv);
  for (GList *l = list; l != nullptr; l = l->next) {
    zval item;
    element_to_php(l->data, element_type, transfer, &item);
    zend_hash_next_index_insert(Z_ARRVAL_P(rv), &item);
  }
  if (transfer != Transfer::None) g_list_free(list);
}

// GSList -> list.
void gslist_to_php(GSList *list, GType element_type, Transfer transfer, zval *rv) {
  array_init(rv);
  for (GSList *l = list; l != nullptr; l = l->next) {
    zval item;
    element_to_php(l->data, element_type, transfer, &item);
    zend_hash_next_index_insert(Z_ARRVAL_P(rv), &item);
  }
  if (transfer != Transfer::None) g_slist_free(list);
}

// GPtrArray -> list.
void gptrarray_to_php(GPtrArray *array, GType element_type, Transfer transfer, zval *rv) {
  array_init(rv);
  if (array != nullptr) {
    for (guint i = 0; i < array->len; i++) {
      zval item;
      element_to_php(g_ptr_array_index(array, i), element_type, transfer, &item);
      zend_hash_next_index_insert(Z_ARRVAL_P(rv), &item);
    }
    // Full: the elements were released above; Container: they stay the callee's. Either way the
    // array's own element-free function must not run again.
    if (transfer != Transfer::None) {
      g_ptr_array_set_free_func(array, nullptr);
      g_ptr_array_free(array, TRUE);
    }
  }
}

// char** of a known length -> list<string>. GLib hands the length back beside the array for
// the members that answer with one (g_key_file_get_groups()); the array may still be
// NULL-terminated, but the length is what is authoritative.
void strv_to_php(char **strv, gsize length, Transfer transfer, zval *rv) {
  array_init(rv);
  if (strv == nullptr) return;
  for (gsize i = 0; i < length; i++) {
    if (strv[i] != nullptr) add_next_index_string(rv, strv[i]);
  }
  if (transfer == Transfer::Full) {
    for (gsize i = 0; i < length; i++) g_free(strv[i]);
  }
  if (transfer != Transfer::None) g_free(static_cast<void *>(strv));
}

// char** -> list<string>.
void strv_to_php(char **strv, Transfer transfer, zval *rv) {
  array_init(rv);
  for (char **s = strv; s != nullptr && *s != nullptr; s++) add_next_index_string(rv, *s);
  if (transfer == Transfer::Full) {
    g_strfreev(strv);
  } else if (transfer == Transfer::Container) {
    g_free(static_cast<void *>(strv));  // the array, not the strings
  }
}

// Borrowed const vector -> list<string>.
void strv_to_php(const char *const *strv, zval *rv) {
  array_init(rv);
  for (const char *const *s = strv; s != nullptr && *s != nullptr; s++)
    add_next_index_string(rv, *s);
}

// list<string> -> char** (NULL-terminated, owned by the caller).
// PHP list -> C array of gint. Every element converts like an int parameter would.
gint *int_array_from_php(zval *value, gsize *n, uint32_t arg) {
  HashTable *ht = Z_ARRVAL_P(value);
  *n = zend_hash_num_elements(ht);
  auto *out = g_new0(gint, (*n) + 1);
  gsize i = 0;
  zval *item = nullptr;
  // NOLINTNEXTLINE(readability-math-missing-parentheses) inside ZEND_HASH_FOREACH_VAL
  ZEND_HASH_FOREACH_VAL(ht, item) {
    if (Z_TYPE_P(item) != IS_LONG && Z_TYPE_P(item) != IS_DOUBLE && Z_TYPE_P(item) != IS_STRING) {
      g_free(out);
      zend_argument_type_error(arg, "must be a list of int, %s given", zend_zval_type_name(item));
      return nullptr;
    }
    const zend_long v = zval_get_long(item);
    if (!check_range<gint>(v, arg)) {
      g_free(out);
      return nullptr;
    }
    out[i++] = static_cast<gint>(v);
  }
  ZEND_HASH_FOREACH_END();
  return out;
}

// PHP list -> C array of gdouble.
gdouble *double_array_from_php(zval *value, gsize *n, uint32_t arg) {
  HashTable *ht = Z_ARRVAL_P(value);
  *n = zend_hash_num_elements(ht);
  auto *out = g_new0(gdouble, (*n) + 1);
  gsize i = 0;
  zval *item = nullptr;
  // NOLINTNEXTLINE(readability-math-missing-parentheses) inside ZEND_HASH_FOREACH_VAL
  ZEND_HASH_FOREACH_VAL(ht, item) {
    if (Z_TYPE_P(item) != IS_LONG && Z_TYPE_P(item) != IS_DOUBLE && Z_TYPE_P(item) != IS_STRING) {
      g_free(out);
      zend_argument_type_error(arg, "must be a list of float, %s given", zend_zval_type_name(item));
      return nullptr;
    }
    out[i++] = zval_get_double(item);
  }
  ZEND_HASH_FOREACH_END();
  return out;
}

// PHP list -> C array of gboolean.
gboolean *bool_array_from_php(zval *value, gsize *n, uint32_t arg) {
  HashTable *ht = Z_ARRVAL_P(value);
  *n = zend_hash_num_elements(ht);
  auto *out = g_new0(gboolean, (*n) + 1);
  gsize i = 0;
  zval *item = nullptr;
  // NOLINTNEXTLINE(readability-math-missing-parentheses) inside ZEND_HASH_FOREACH_VAL
  ZEND_HASH_FOREACH_VAL(ht, item) {
    if (Z_TYPE_P(item) == IS_ARRAY || Z_TYPE_P(item) == IS_OBJECT) {
      g_free(out);
      zend_argument_type_error(arg, "must be a list of bool, %s given", zend_zval_type_name(item));
      return nullptr;
    }
    out[i++] = zend_is_true(item) ? TRUE : FALSE;
  }
  ZEND_HASH_FOREACH_END();
  return out;
}

// PHP list of strings -> a NULL-terminated char** the caller g_strfreev()s.
char **strv_from_php(zval *value) {
  ZVAL_DEREF(value);
  if (Z_TYPE_P(value) != IS_ARRAY) {
    zend_type_error("expected an array of strings, %s given", zend_zval_value_name(value));
    return nullptr;
  }
  GStrvBuilder *builder = g_strv_builder_new();
  zval *item;
  // NOLINTNEXTLINE(readability-math-missing-parentheses) Zend macro expansion
  ZEND_HASH_FOREACH_VAL(Z_ARRVAL_P(value), item) {
    // each element like a string parameter: strict_types where the array was written, weak
    // coercion otherwise, and no NUL / invalid UTF-8 (a GStrv element is a C string)
    if (Z_TYPE_P(item) != IS_STRING && (caller_is_strict() || !weak_to_string_ok(item))) {
      g_strv_builder_unref(builder);
      zend_type_error("expected an array of strings, element is %s", zend_zval_value_name(item));
      return nullptr;
    }
    zend_string *s = zval_get_string(item);
    if (!check_utf8(s, 0)) {
      zend_string_release(s);
      g_strv_builder_unref(builder);
      return nullptr;
    }
    g_strv_builder_add(builder, ZSTR_VAL(s));
    zend_string_release(s);
  }
  ZEND_HASH_FOREACH_END();
  char **strv = g_strv_builder_end(builder);
  g_strv_builder_unref(builder);
  return strv;
}

}  // namespace phpgtk
