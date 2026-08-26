#include "collections.h"

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
    // Elements were released above when Full; never let the array free them again.
    if (transfer != Transfer::None) g_ptr_array_free(array, TRUE);
  }
}

// char** -> list<string>.
void strv_to_php(char **strv, Transfer transfer, zval *rv) {
  array_init(rv);
  for (char **s = strv; s != nullptr && *s != nullptr; s++) add_next_index_string(rv, *s);
  if (transfer == Transfer::Full) g_strfreev(strv);
}

// list<string> -> char** (NULL-terminated, owned by the caller).
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
    if (Z_TYPE_P(item) == IS_ARRAY || Z_TYPE_P(item) == IS_OBJECT) {
      g_strv_builder_unref(builder);
      zend_type_error("expected an array of strings, element is %s", zend_zval_value_name(item));
      return nullptr;
    }
    zend_string *s = zval_get_string(item);
    g_strv_builder_add(builder, ZSTR_VAL(s));
    zend_string_release(s);
  }
  ZEND_HASH_FOREACH_END();
  char **strv = g_strv_builder_end(builder);
  g_strv_builder_unref(builder);
  return strv;
}

}  // namespace phpgtk
