#include "marshal.h"
#include "object.h"
#include "paramspec.h"
#include "boxed.h"
#include "fundamental.h"
#include "enums.h"
#include "collections.h"
#include "gerror.h"
#include "variant.h"

namespace phpgtk {

// Whether to_php() can convert a value of this GType (used to skip fields in var_dump()).
bool to_php_supported(GType t) {
  if (t == G_TYPE_GTYPE) return true;
  switch (G_TYPE_FUNDAMENTAL(t)) {
    case G_TYPE_CHAR:
    case G_TYPE_UCHAR:
    case G_TYPE_BOOLEAN:
    case G_TYPE_INT:
    case G_TYPE_UINT:
    case G_TYPE_LONG:
    case G_TYPE_ULONG:
    case G_TYPE_INT64:
    case G_TYPE_UINT64:
    case G_TYPE_ENUM:
    case G_TYPE_FLAGS:
    case G_TYPE_FLOAT:
    case G_TYPE_DOUBLE:
    case G_TYPE_STRING:
    case G_TYPE_OBJECT:
    case G_TYPE_INTERFACE:
    case G_TYPE_PARAM:
      return true;
    case G_TYPE_BOXED:
      return t == G_TYPE_STRV || t == G_TYPE_BYTES || t == G_TYPE_ERROR ||
             boxed_class_for_type(t) != nullptr || fundamental_class_for_type(t) != nullptr;
    case G_TYPE_VARIANT:
      return true;
    default:
      return false;
  }
}

// GValue -> zval. Unsupported types: TypeError + null.
void to_php(const GValue *v, zval *rv) {
  GType t = G_VALUE_TYPE(v);
  if (t == G_TYPE_GTYPE) {  // not a fundamental
    ZVAL_LONG(rv, static_cast<zend_long>(g_value_get_gtype(v)));
    return;
  }
  switch (G_TYPE_FUNDAMENTAL(t)) {
    case G_TYPE_CHAR:
      // NOLINTNEXTLINE(bugprone-signed-char-misuse) gint8 is signed by definition
      ZVAL_LONG(rv, static_cast<zend_long>(g_value_get_schar(v)));
      return;
    case G_TYPE_UCHAR:
      ZVAL_LONG(rv, g_value_get_uchar(v));
      return;
    case G_TYPE_BOOLEAN:
      ZVAL_BOOL(rv, g_value_get_boolean(v));
      return;
    case G_TYPE_INT:
      ZVAL_LONG(rv, g_value_get_int(v));
      return;
    case G_TYPE_UINT:
      ZVAL_LONG(rv, g_value_get_uint(v));
      return;
    case G_TYPE_LONG:
      ZVAL_LONG(rv, g_value_get_long(v));
      return;
    case G_TYPE_ULONG:
      ZVAL_LONG(rv, static_cast<zend_long>(g_value_get_ulong(v)));
      return;
    case G_TYPE_INT64:
      ZVAL_LONG(rv, g_value_get_int64(v));
      return;
    case G_TYPE_UINT64:
      ZVAL_LONG(rv, static_cast<zend_long>(g_value_get_uint64(v)));
      return;
    case G_TYPE_ENUM:
      enum_to_php(t, g_value_get_enum(v), rv);
      return;
    case G_TYPE_FLAGS:
      ZVAL_LONG(rv, g_value_get_flags(v));
      return;
    case G_TYPE_FLOAT:
      ZVAL_DOUBLE(rv, g_value_get_float(v));
      return;
    case G_TYPE_DOUBLE:
      ZVAL_DOUBLE(rv, g_value_get_double(v));
      return;
    case G_TYPE_STRING: {
      const char *s = g_value_get_string(v);
      if (s == nullptr)
        ZVAL_NULL(rv);
      else
        ZVAL_STRING(rv, s);
      return;
    }
    case G_TYPE_OBJECT:
    case G_TYPE_INTERFACE:
      wrap(G_OBJECT(g_value_get_object(v)), rv);
      return;
    case G_TYPE_PARAM:
      wrap_param_spec(g_value_get_param(v), rv);
      return;
    case G_TYPE_BOXED: {
      if (t == G_TYPE_STRV) {
        strv_to_php(static_cast<char **>(g_value_get_boxed(v)), Transfer::None, rv);
        return;
      }
      if (t == G_TYPE_BYTES) {
        auto *bytes = static_cast<GBytes *>(g_value_get_boxed(v));
        gsize size = 0;
        const auto *data =
            bytes != nullptr ? static_cast<const char *>(g_bytes_get_data(bytes, &size)) : nullptr;
        if (data == nullptr)
          ZVAL_EMPTY_STRING(rv);
        else
          ZVAL_STRINGL(rv, data, size);
        return;
      }
      if (t == G_TYPE_ERROR) {
        auto *error = static_cast<GError *>(g_value_get_boxed(v));
        if (error == nullptr)
          ZVAL_NULL(rv);
        else
          gerror_to_php(error, rv);
        return;
      }
      if (boxed_class_for_type(t) == nullptr && fundamental_class_for_type(t) != nullptr) {
        wrap_fundamental(t, g_value_get_boxed(v), rv);  // refcounted boxed: cairo_t, ...
        return;
      }
      wrap_boxed(t, g_value_get_boxed(v), rv);
      return;
    }
    case G_TYPE_VARIANT:
      variant_to_php(g_value_get_variant(v), rv);
      return;
    // G_TYPE_POINTER stays unsupported on purpose (no meaningful PHP value).
    default:
      ZVAL_NULL(rv);
      zend_type_error("to_php: unsupported GType %s", g_type_name(t));
  }
}

// zval -> GValue of type `t`. Returns false (TypeError thrown, *out unset) on failure.
bool to_gvalue(zval *pv, GType t, GValue *out) {
  g_value_init(out, t);
  switch (G_TYPE_FUNDAMENTAL(t)) {
    case G_TYPE_CHAR:
      g_value_set_schar(out, static_cast<gint8>(zval_get_long(pv)));
      return true;
    case G_TYPE_UCHAR:
      g_value_set_uchar(out, static_cast<guchar>(zval_get_long(pv)));
      return true;
    case G_TYPE_BOOLEAN:
      g_value_set_boolean(out, zend_is_true(pv));
      return true;
    case G_TYPE_INT:
      g_value_set_int(out, static_cast<gint>(zval_get_long(pv)));
      return true;
    case G_TYPE_UINT:
      g_value_set_uint(out, static_cast<guint>(zval_get_long(pv)));
      return true;
    case G_TYPE_LONG:
      g_value_set_long(out, static_cast<glong>(zval_get_long(pv)));
      return true;
    case G_TYPE_ULONG:
      g_value_set_ulong(out, static_cast<gulong>(zval_get_long(pv)));
      return true;
    case G_TYPE_INT64:
      g_value_set_int64(out, zval_get_long(pv));
      return true;
    case G_TYPE_UINT64:
      g_value_set_uint64(out, static_cast<guint64>(zval_get_long(pv)));
      return true;
    case G_TYPE_ENUM: {
      gint e = 0;
      if (!enum_from_php(pv, t, &e)) {
        g_value_unset(out);
        return false;
      }
      g_value_set_enum(out, e);
      return true;
    }
    case G_TYPE_FLAGS: {
      const zend_long bits = zval_get_long(pv);
      auto *klass = static_cast<GFlagsClass *>(g_type_class_ref(t));
      const guint mask = klass->mask;
      g_type_class_unref(klass);
      if (bits < 0 || (static_cast<guint64>(bits) & ~static_cast<guint64>(mask)) != 0) {
        g_value_unset(out);
        zend_value_error("%s: invalid flags value %ld (mask 0x%x)", g_type_name(t),
                         static_cast<long>(bits), mask);
        return false;
      }
      g_value_set_flags(out, static_cast<guint>(bits));
      return true;
    }
    case G_TYPE_FLOAT:
      g_value_set_float(out, static_cast<gfloat>(zval_get_double(pv)));
      return true;
    case G_TYPE_DOUBLE:
      g_value_set_double(out, zval_get_double(pv));
      return true;
    case G_TYPE_STRING: {
      if (Z_TYPE_P(pv) == IS_NULL) {
        g_value_set_string(out, nullptr);
      } else {
        zend_string *s = zval_get_string(pv);
        g_value_set_string(out, ZSTR_VAL(s));
        zend_string_release(s);
      }
      return true;
    }
    case G_TYPE_OBJECT:
    case G_TYPE_INTERFACE: {
      if (Z_TYPE_P(pv) == IS_NULL) {
        g_value_set_object(out, nullptr);
        return true;
      }
      GObject *o = unwrap(pv, t);
      if (o == nullptr) {
        g_value_unset(out);
        return false;
      }
      g_value_set_object(out, o);
      return true;
    }
    case G_TYPE_BOXED: {
      if (t == G_TYPE_BYTES) {
        if (Z_TYPE_P(pv) == IS_ARRAY || Z_TYPE_P(pv) == IS_OBJECT) {
          g_value_unset(out);
          zend_type_error("expected string for GBytes, %s given", zend_zval_value_name(pv));
          return false;
        }
        zend_string *str = zval_get_string(pv);
        g_value_take_boxed(out, g_bytes_new(ZSTR_VAL(str), ZSTR_LEN(str)));
        zend_string_release(str);
        return true;
      }
      if (t == G_TYPE_STRV) {
        char **strv = strv_from_php(pv);
        if (strv == nullptr) {
          g_value_unset(out);
          return false;
        }
        g_value_take_boxed(out, static_cast<gpointer>(strv));
        return true;
      }
      if (Z_TYPE_P(pv) == IS_NULL) {
        g_value_set_boxed(out, nullptr);
        return true;
      }
      if (boxed_class_for_type(t) == nullptr && fundamental_class_for_type(t) != nullptr) {
        gpointer instance = unwrap_fundamental(pv, t);
        if (instance == nullptr) {
          g_value_unset(out);
          return false;
        }
        g_value_set_boxed(out, instance);  // copy = ref for refcounted boxed types
        return true;
      }
      gpointer data = unwrap_boxed(pv, t);
      if (data == nullptr) {
        g_value_unset(out);
        return false;
      }
      g_value_set_boxed(out, data);  // copies
      return true;
    }
    case G_TYPE_VARIANT: {
      if (Z_TYPE_P(pv) == IS_NULL) {
        g_value_set_variant(out, nullptr);
        return true;
      }
      GVariant *variant = php_to_variant(pv, nullptr);
      if (variant == nullptr) {
        g_value_unset(out);
        return false;
      }
      g_value_take_variant(out, g_variant_ref_sink(variant));
      return true;
    }
    default:
      g_value_unset(out);
      zend_type_error("to_gvalue: unsupported GType %s", g_type_name(t));
      return false;
  }
}

}  // namespace phpgtk
