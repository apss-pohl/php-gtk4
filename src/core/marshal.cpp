#include "marshal.h"
#include "wrap.h"
#include <string>

namespace phpgtk {

Php::Value to_php(const GValue *v) {
  GType t = G_VALUE_TYPE(v);
  if (t == G_TYPE_GTYPE) return (int64_t)g_value_get_gtype(v);  // not a fundamental
  switch (G_TYPE_FUNDAMENTAL(t)) {
    case G_TYPE_CHAR:
      return (int)g_value_get_schar(v);
    case G_TYPE_UCHAR:
      return (int)g_value_get_uchar(v);
    case G_TYPE_BOOLEAN:
      return (bool)g_value_get_boolean(v);
    case G_TYPE_INT:
      return (int64_t)g_value_get_int(v);
    case G_TYPE_UINT:
      return (int64_t)g_value_get_uint(v);
    case G_TYPE_LONG:
      return (int64_t)g_value_get_long(v);
    case G_TYPE_ULONG:
      return (int64_t)g_value_get_ulong(v);
    case G_TYPE_INT64:
      return (int64_t)g_value_get_int64(v);
    case G_TYPE_UINT64:
      return (int64_t)g_value_get_uint64(v);
    case G_TYPE_ENUM:
      return (int64_t)g_value_get_enum(v);
    case G_TYPE_FLAGS:
      return (int64_t)g_value_get_flags(v);
    case G_TYPE_FLOAT:
      return (double)g_value_get_float(v);
    case G_TYPE_DOUBLE:
      return g_value_get_double(v);
    case G_TYPE_STRING: {
      const char *s = g_value_get_string(v);
      return s ? Php::Value(s) : Php::Value(nullptr);
    }
    case G_TYPE_OBJECT:
    case G_TYPE_INTERFACE:
      return wrap(G_OBJECT(g_value_get_object(v)));
    case G_TYPE_PARAM: {
      // TODO: a GParamSpec wrapper class; until then expose the property name.
      GParamSpec *spec = g_value_get_param(v);
      return spec != nullptr ? Php::Value(g_param_spec_get_name(spec)) : Php::Value(nullptr);
    }
    // TODO: BOXED (BoxedWrapper), VARIANT, POINTER (opaque handle)
    default:
      throw Php::Exception(std::string("to_php: unsupported GType ") + g_type_name(t));
  }
}

void to_gvalue(const Php::Value &value, GType type, GValue *out) {
  const Php::Value &pv = value;
  const GType t = type;
  g_value_init(out, t);
  switch (G_TYPE_FUNDAMENTAL(t)) {
    case G_TYPE_CHAR:
      g_value_set_schar(out, (gint8)pv.numericValue());
      break;
    case G_TYPE_UCHAR:
      g_value_set_uchar(out, (guchar)pv.numericValue());
      break;
    case G_TYPE_BOOLEAN:
      g_value_set_boolean(out, pv.boolValue());
      break;
    case G_TYPE_INT:
      g_value_set_int(out, (gint)pv.numericValue());
      break;
    case G_TYPE_UINT:
      g_value_set_uint(out, (guint)pv.numericValue());
      break;
    case G_TYPE_LONG:
      g_value_set_long(out, (glong)pv.numericValue());
      break;
    case G_TYPE_ULONG:
      g_value_set_ulong(out, (gulong)pv.numericValue());
      break;
    case G_TYPE_INT64:
      g_value_set_int64(out, pv.numericValue());
      break;
    case G_TYPE_UINT64:
      g_value_set_uint64(out, (guint64)pv.numericValue());
      break;
    case G_TYPE_ENUM:
      g_value_set_enum(out, (gint)pv.numericValue());
      break;
    case G_TYPE_FLAGS:
      g_value_set_flags(out, (guint)pv.numericValue());
      break;
    case G_TYPE_FLOAT:
      g_value_set_float(out, (gfloat)pv.floatValue());
      break;
    case G_TYPE_DOUBLE:
      g_value_set_double(out, pv.floatValue());
      break;
    case G_TYPE_STRING:
      if (pv.isNull())
        g_value_set_string(out, nullptr);
      else
        g_value_set_string(out, pv.stringValue().c_str());
      break;
    case G_TYPE_OBJECT:
    case G_TYPE_INTERFACE:
      g_value_set_object(out, pv.isNull() ? nullptr : unwrap(pv, t));
      break;
    default:
      g_value_unset(out);
      throw Php::Exception(std::string("to_gvalue: unsupported GType ") + g_type_name(t));
  }
}

}  // namespace phpgtk
