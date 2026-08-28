#include "variant.h"

#include <string>

namespace phpgtk {

// GVariant -> PHP value (see variant.h for the mapping); never fails.
void variant_to_php(GVariant *v, zval *rv) {
  if (v == nullptr) {
    ZVAL_NULL(rv);
    return;
  }
  switch (g_variant_classify(v)) {
    case G_VARIANT_CLASS_BOOLEAN:
      ZVAL_BOOL(rv, g_variant_get_boolean(v));
      return;
    case G_VARIANT_CLASS_BYTE:
      ZVAL_LONG(rv, g_variant_get_byte(v));
      return;
    case G_VARIANT_CLASS_INT16:
      ZVAL_LONG(rv, g_variant_get_int16(v));
      return;
    case G_VARIANT_CLASS_UINT16:
      ZVAL_LONG(rv, g_variant_get_uint16(v));
      return;
    case G_VARIANT_CLASS_INT32:
      ZVAL_LONG(rv, g_variant_get_int32(v));
      return;
    case G_VARIANT_CLASS_UINT32:
      ZVAL_LONG(rv, g_variant_get_uint32(v));
      return;
    case G_VARIANT_CLASS_INT64:
      ZVAL_LONG(rv, g_variant_get_int64(v));
      return;
    case G_VARIANT_CLASS_UINT64:
      ZVAL_LONG(rv, static_cast<zend_long>(g_variant_get_uint64(v)));
      return;
    case G_VARIANT_CLASS_HANDLE:
      ZVAL_LONG(rv, g_variant_get_handle(v));
      return;
    case G_VARIANT_CLASS_DOUBLE:
      ZVAL_DOUBLE(rv, g_variant_get_double(v));
      return;
    case G_VARIANT_CLASS_STRING:
    case G_VARIANT_CLASS_OBJECT_PATH:
    case G_VARIANT_CLASS_SIGNATURE:
      ZVAL_STRING(rv, g_variant_get_string(v, nullptr));
      return;
    case G_VARIANT_CLASS_VARIANT: {
      GVariant *inner = g_variant_get_variant(v);
      variant_to_php(inner, rv);
      g_variant_unref(inner);
      return;
    }
    case G_VARIANT_CLASS_MAYBE: {
      GVariant *inner = g_variant_get_maybe(v);
      if (inner == nullptr) {
        ZVAL_NULL(rv);
      } else {
        variant_to_php(inner, rv);
        g_variant_unref(inner);
      }
      return;
    }
    case G_VARIANT_CLASS_ARRAY: {
      const GVariantType *elem = g_variant_type_element(g_variant_get_type(v));
      array_init(rv);
      GVariantIter iter;
      g_variant_iter_init(&iter, v);
      GVariant *child;
      if (g_variant_type_is_dict_entry(elem)) {
        while ((child = g_variant_iter_next_value(&iter)) != nullptr) {
          GVariant *key = g_variant_get_child_value(child, 0);
          GVariant *val = g_variant_get_child_value(child, 1);
          zval zv;
          variant_to_php(val, &zv);
          if (g_variant_classify(key) == G_VARIANT_CLASS_STRING) {
            zend_hash_str_update(Z_ARRVAL_P(rv), g_variant_get_string(key, nullptr),
                                 g_variant_get_size(key) - 1, &zv);
          } else {
            zval zk;
            variant_to_php(key, &zk);
            zend_hash_index_update(Z_ARRVAL_P(rv), zval_get_long(&zk), &zv);
            zval_ptr_dtor(&zk);
          }
          g_variant_unref(key);
          g_variant_unref(val);
          g_variant_unref(child);
        }
      } else {
        while ((child = g_variant_iter_next_value(&iter)) != nullptr) {
          zval zv;
          variant_to_php(child, &zv);
          zend_hash_next_index_insert(Z_ARRVAL_P(rv), &zv);
          g_variant_unref(child);
        }
      }
      return;
    }
    case G_VARIANT_CLASS_TUPLE:
    case G_VARIANT_CLASS_DICT_ENTRY: {
      array_init(rv);
      const gsize n = g_variant_n_children(v);
      for (gsize i = 0; i < n; i++) {
        GVariant *child = g_variant_get_child_value(v, i);
        zval zv;
        variant_to_php(child, &zv);
        zend_hash_next_index_insert(Z_ARRVAL_P(rv), &zv);
        g_variant_unref(child);
      }
      return;
    }
  }
  ZVAL_NULL(rv);
}

namespace {

// True for a list (0..n-1 keys) whose values are all strings -> inferred as "as".
bool array_is_string_list(HashTable *ht) {
  if (!zend_array_is_list(ht)) return false;
  zval *item;
  // NOLINTNEXTLINE(readability-math-missing-parentheses) Zend macro expansion
  ZEND_HASH_FOREACH_VAL(ht, item) {
    if (Z_TYPE_P(item) != IS_STRING) return false;
  }
  ZEND_HASH_FOREACH_END();
  return true;
}

// Throw the TypeError for a value that does not fit `type` and return nullptr.
GVariant *fail(zval *value, const GVariantType *type) {
  gchar *ts = type != nullptr ? g_variant_type_dup_string(type) : g_strdup("(inferred)");
  zend_type_error("cannot convert %s to GVariant type %s", zend_zval_value_name(value), ts);
  g_free(ts);
  return nullptr;
}
}  // namespace

// PHP value -> GVariant of `type` (or inferred when nullptr); see variant.h.
GVariant *php_to_variant(zval *value, const GVariantType *type) {
  ZVAL_DEREF(value);
  if (type == nullptr) {
    // Inference
    switch (Z_TYPE_P(value)) {
      case IS_TRUE:
        return g_variant_new_boolean(TRUE);
      case IS_FALSE:
        return g_variant_new_boolean(FALSE);
      case IS_LONG:
        return (Z_LVAL_P(value) >= G_MININT32 && Z_LVAL_P(value) <= G_MAXINT32)
                   ? g_variant_new_int32(static_cast<gint32>(Z_LVAL_P(value)))
                   : g_variant_new_int64(Z_LVAL_P(value));
      case IS_DOUBLE:
        return g_variant_new_double(Z_DVAL_P(value));
      case IS_STRING:
        return g_variant_new_string(Z_STRVAL_P(value));
      case IS_NULL:
        return g_variant_new_maybe(G_VARIANT_TYPE_VARIANT, nullptr);
      case IS_ARRAY: {
        HashTable *ht = Z_ARRVAL_P(value);
        if (array_is_string_list(ht)) return php_to_variant(value, G_VARIANT_TYPE_STRING_ARRAY);
        if (zend_array_is_list(ht)) return php_to_variant(value, G_VARIANT_TYPE("av"));
        return php_to_variant(value, G_VARIANT_TYPE_VARDICT);
      }
      default:
        return fail(value, nullptr);
    }
  }

  if (g_variant_type_is_variant(type)) {
    GVariant *inner = php_to_variant(value, nullptr);
    return inner != nullptr ? g_variant_new_variant(inner) : nullptr;
  }
  if (g_variant_type_is_maybe(type)) {
    if (Z_TYPE_P(value) == IS_NULL)
      return g_variant_new_maybe(g_variant_type_element(type), nullptr);
    GVariant *inner = php_to_variant(value, g_variant_type_element(type));
    return inner != nullptr ? g_variant_new_maybe(nullptr, inner) : nullptr;
  }
  if (g_variant_type_is_basic(type)) {
    if (Z_TYPE_P(value) == IS_ARRAY || Z_TYPE_P(value) == IS_OBJECT) return fail(value, type);
    const gchar c = g_variant_type_peek_string(type)[0];
    switch (c) {
      case 'b':
        return g_variant_new_boolean(zend_is_true(value));
      case 'y':
        return g_variant_new_byte(static_cast<guint8>(zval_get_long(value)));
      case 'n':
        return g_variant_new_int16(static_cast<gint16>(zval_get_long(value)));
      case 'q':
        return g_variant_new_uint16(static_cast<guint16>(zval_get_long(value)));
      case 'i':
        return g_variant_new_int32(static_cast<gint32>(zval_get_long(value)));
      case 'u':
        return g_variant_new_uint32(static_cast<guint32>(zval_get_long(value)));
      case 'x':
        return g_variant_new_int64(zval_get_long(value));
      case 't':
        return g_variant_new_uint64(static_cast<guint64>(zval_get_long(value)));
      case 'h':
        return g_variant_new_handle(static_cast<gint32>(zval_get_long(value)));
      case 'd':
        return g_variant_new_double(zval_get_double(value));
      case 's':
      case 'o':
      case 'g': {
        if (Z_TYPE_P(value) == IS_ARRAY || Z_TYPE_P(value) == IS_OBJECT) return fail(value, type);
        zend_string *s = zval_get_string(value);
        GVariant *v = nullptr;
        if (c == 's') {
          v = g_variant_new_string(ZSTR_VAL(s));
        } else if (c == 'o' && g_variant_is_object_path(ZSTR_VAL(s))) {
          v = g_variant_new_object_path(ZSTR_VAL(s));
        } else if (c == 'g' && g_variant_is_signature(ZSTR_VAL(s))) {
          v = g_variant_new_signature(ZSTR_VAL(s));
        }
        zend_string_release(s);
        return v != nullptr ? v : fail(value, type);
      }
      default:
        return fail(value, type);
    }
  }
  if (Z_TYPE_P(value) != IS_ARRAY) return fail(value, type);
  HashTable *ht = Z_ARRVAL_P(value);

  if (g_variant_type_is_array(type)) {
    const GVariantType *elem = g_variant_type_element(type);
    GVariantBuilder builder;
    g_variant_builder_init(&builder, type);
    if (g_variant_type_is_dict_entry(elem)) {
      const GVariantType *kt = g_variant_type_key(elem);
      const GVariantType *vt = g_variant_type_value(elem);
      zend_string *key;
      zend_ulong idx;
      zval *item;
      // NOLINTNEXTLINE(readability-math-missing-parentheses) Zend macro expansion
      ZEND_HASH_FOREACH_KEY_VAL(ht, idx, key, item) {
        zval zk;
        if (key != nullptr)
          ZVAL_STR_COPY(&zk, key);
        else
          ZVAL_LONG(&zk, static_cast<zend_long>(idx));
        GVariant *vk = php_to_variant(&zk, kt);
        zval_ptr_dtor(&zk);
        GVariant *vv = vk != nullptr ? php_to_variant(item, vt) : nullptr;
        if (vk == nullptr || vv == nullptr) {
          if (vk != nullptr) g_variant_unref(g_variant_ref_sink(vk));
          g_variant_builder_clear(&builder);
          return nullptr;
        }
        g_variant_builder_add_value(&builder, g_variant_new_dict_entry(vk, vv));
      }
      ZEND_HASH_FOREACH_END();
    } else {
      zval *item;
      // NOLINTNEXTLINE(readability-math-missing-parentheses) Zend macro expansion
      ZEND_HASH_FOREACH_VAL(ht, item) {
        GVariant *vv = php_to_variant(item, elem);
        if (vv == nullptr) {
          g_variant_builder_clear(&builder);
          return nullptr;
        }
        g_variant_builder_add_value(&builder, vv);
      }
      ZEND_HASH_FOREACH_END();
    }
    return g_variant_builder_end(&builder);
  }
  if (g_variant_type_is_tuple(type)) {
    GVariantBuilder builder;
    g_variant_builder_init(&builder, type);
    const GVariantType *elem = g_variant_type_first(type);
    zval *item;
    // NOLINTNEXTLINE(readability-math-missing-parentheses) Zend macro expansion
    ZEND_HASH_FOREACH_VAL(ht, item) {
      if (elem == nullptr) {
        g_variant_builder_clear(&builder);
        return fail(value, type);
      }
      GVariant *vv = php_to_variant(item, elem);
      if (vv == nullptr) {
        g_variant_builder_clear(&builder);
        return nullptr;
      }
      g_variant_builder_add_value(&builder, vv);
      elem = g_variant_type_next(elem);
    }
    ZEND_HASH_FOREACH_END();
    if (elem != nullptr) {
      g_variant_builder_clear(&builder);
      return fail(value, type);
    }
    return g_variant_builder_end(&builder);
  }
  return fail(value, type);
}

}  // namespace phpgtk
