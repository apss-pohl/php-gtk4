#include "marshal.h"
#include "object.h"

namespace phpgtk {

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
    default:
      return false;
  }
}

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
      ZVAL_LONG(rv, g_value_get_enum(v));
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
    case G_TYPE_PARAM: {
      // TODO: a GParamSpec class; until then expose the property name.
      GParamSpec *spec = g_value_get_param(v);
      if (spec == nullptr)
        ZVAL_NULL(rv);
      else
        ZVAL_STRING(rv, g_param_spec_get_name(spec));
      return;
    }
    // TODO: BOXED (Boxed handle), VARIANT, POINTER (opaque handle)
    default:
      ZVAL_NULL(rv);
      zend_type_error("to_php: unsupported GType %s", g_type_name(t));
  }
}

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
    case G_TYPE_ENUM:
      g_value_set_enum(out, static_cast<gint>(zval_get_long(pv)));
      return true;
    case G_TYPE_FLAGS:
      g_value_set_flags(out, static_cast<guint>(zval_get_long(pv)));
      return true;
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
    default:
      g_value_unset(out);
      zend_type_error("to_gvalue: unsupported GType %s", g_type_name(t));
      return false;
  }
}

}  // namespace phpgtk
