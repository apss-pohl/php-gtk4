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
      // A fundamental of its own (GdkEvent: neither boxed nor a GObject) is supported once a
      // handle class is registered for it (src/core/fundamental).
      return G_TYPE_IS_INSTANTIATABLE(t) && fundamental_class_for_type(t) != nullptr;
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
      if (G_TYPE_IS_INSTANTIATABLE(t) && fundamental_class_for_type(t) != nullptr) {
        wrap_fundamental(t, g_value_peek_pointer(v), rv);  // GdkEvent and friends
        return;
      }
      ZVAL_NULL(rv);
      zend_type_error("to_php: unsupported GType %s", g_type_name(t));
  }
}

// A property write and a signal argument get the same conversion rules a typed parameter
// gets - strict_types honoured where the assignment is written, weak coercion otherwise -
// and then the C type's range. Before this, `$win->default_width = 'garbage'` stored 0 and
// PHP_INT_MAX stored -1, while $win->set_default_size() rejected both.
// Whether the code doing the assignment declared strict_types. ZEND_ARG_USES_STRICT_TYPES()
// answers that for an internal *call* frame; a property write does not push one, so the frame
// to ask is the one currently executing.
bool caller_is_strict() {
  const zend_execute_data *ex = EG(current_execute_data);
  return ex != nullptr && ex->func != nullptr && ZEND_CALL_USES_STRICT_TYPES(ex);
}

// What PHP's weak mode accepts for a string parameter: scalars, and objects that can say
// what they are. An array or a resource cannot become a string here either.
bool weak_to_string_ok(const zval *pv) {
  switch (Z_TYPE_P(pv)) {
    case IS_LONG:
    case IS_DOUBLE:
    case IS_TRUE:
    case IS_FALSE:
    case IS_STRING:
      return true;
    case IS_OBJECT:
      return Z_OBJCE_P(pv)->__tostring != nullptr;
    default:
      return false;
  }
}

// An integer: exactly like a typed parameter, then the C type's range (set_or_unset below).
bool as_long(zval *pv, GType t, zend_long *out) {
  if (Z_TYPE_P(pv) == IS_LONG) {
    *out = Z_LVAL_P(pv);
    return true;
  }
  if (caller_is_strict() || !zend_parse_arg_long_weak(pv, out, 0)) {
    zend_type_error("cannot assign %s to a value of type %s", zend_zval_value_name(pv),
                    g_type_name(t));
    return false;
  }
  return true;
}

// Same for a float, where int -> float is allowed even under strict_types.
bool as_double(zval *pv, GType t, double *out) {
  if (Z_TYPE_P(pv) == IS_DOUBLE) {
    *out = Z_DVAL_P(pv);
    return true;
  }
  // int -> float is a widening conversion, allowed even under strict_types.
  if (Z_TYPE_P(pv) == IS_LONG) {
    *out = static_cast<double>(Z_LVAL_P(pv));
    return true;
  }
  if (caller_is_strict() || !zend_parse_arg_double_weak(pv, out, 0)) {
    zend_type_error("cannot assign %s to a value of type %s", zend_zval_value_name(pv),
                    g_type_name(t));
    return false;
  }
  return true;
}

// Same for a boolean: strict mode takes true/false, weak mode what PHP's rules allow.
bool as_bool(zval *pv, GType t, bool *out) {
  if (Z_TYPE_P(pv) == IS_TRUE || Z_TYPE_P(pv) == IS_FALSE) {
    *out = Z_TYPE_P(pv) == IS_TRUE;
    return true;
  }
  if (caller_is_strict() || !zend_parse_arg_bool_weak(pv, out, 0)) {
    zend_type_error("cannot assign %s to a value of type %s", zend_zval_value_name(pv),
                    g_type_name(t));
    return false;
  }
  return true;
}

template <typename T, typename Setter>
// The integer arms differ only in the C type the value has to fit and the setter they call.
bool set_or_unset(zval *pv, GType t, GValue *out, Setter set) {
  zend_long v = 0;
  if (!as_long(pv, t, &v) || !check_range<T>(v, 0)) {
    g_value_unset(out);
    return false;
  }
  set(out, static_cast<T>(v));
  return true;
}

// A GType named the way PHP names one - see marshal.h. The four scalars a payload realistically
// carries, plus any registered class; the class arm is how a GdkTexture or a PhpValue crosses.
GType gtype_from_php_name(zend_string *name, uint32_t arg) {
  const char *s = ZSTR_VAL(name);
  if (strcmp(s, "string") == 0) return G_TYPE_STRING;
  if (strcmp(s, "int") == 0) return G_TYPE_INT64;
  if (strcmp(s, "float") == 0 || strcmp(s, "double") == 0) return G_TYPE_DOUBLE;
  if (strcmp(s, "bool") == 0) return G_TYPE_BOOLEAN;
  const char *slash = strrchr(s, '\\');
  zend_class_entry *ce = class_for_gtype_name(slash != nullptr ? slash + 1 : s);
  const GType t = ce != nullptr ? gtype_for_class(ce) : 0;
  if (t == 0) {
    zend_argument_value_error(arg,
                              "must be \"string\", \"int\", \"float\", \"bool\" or a registered "
                              "class name, \"%s\" given",
                              s);
    return 0;
  }
  return t;
}

// The reverse of gtype_from_php_name(): the four scalars keep their PHP spelling, everything
// else answers with the GType's name, which is the registered class name.
zend_string *php_name_for_gtype(GType type) {
  switch (type) {
    case G_TYPE_STRING:
      return zend_string_init("string", sizeof("string") - 1, false);
    case G_TYPE_INT64:
      return zend_string_init("int", sizeof("int") - 1, false);
    case G_TYPE_DOUBLE:
      return zend_string_init("float", sizeof("float") - 1, false);
    case G_TYPE_BOOLEAN:
      return zend_string_init("bool", sizeof("bool") - 1, false);
    default:
      break;
  }
  const char *name = g_type_name(type);
  if (name == nullptr) name = "";
  return zend_string_init(name, strlen(name), false);
}

// zval -> GValue of type `t`. Returns false (TypeError thrown, *out unset) on failure.
bool to_gvalue(zval *pv, GType t, GValue *out) {
  g_value_init(out, t);
  switch (G_TYPE_FUNDAMENTAL(t)) {
    case G_TYPE_CHAR:
      return set_or_unset<gint8>(pv, t, out, g_value_set_schar);
    case G_TYPE_UCHAR:
      return set_or_unset<guchar>(pv, t, out, g_value_set_uchar);
    case G_TYPE_BOOLEAN: {
      bool b = false;
      if (!as_bool(pv, t, &b)) {
        g_value_unset(out);
        return false;
      }
      g_value_set_boolean(out, static_cast<gboolean>(b));
      return true;
    }
    case G_TYPE_INT:
      return set_or_unset<gint>(pv, t, out, g_value_set_int);
    case G_TYPE_UINT:
      return set_or_unset<guint>(pv, t, out, g_value_set_uint);
    case G_TYPE_LONG:
      return set_or_unset<glong>(pv, t, out, g_value_set_long);
    case G_TYPE_ULONG:
      return set_or_unset<gulong>(pv, t, out, g_value_set_ulong);
    case G_TYPE_INT64:
      return set_or_unset<gint64>(pv, t, out, g_value_set_int64);
    case G_TYPE_UINT64:
      return set_or_unset<guint64>(pv, t, out, g_value_set_uint64);
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
      zend_long bits = 0;
      if (!as_long(pv, t, &bits) || !check_flags(t, bits, 0)) {
        g_value_unset(out);
        return false;
      }
      g_value_set_flags(out, static_cast<guint>(bits));
      return true;
    }
    case G_TYPE_FLOAT: {
      double d = 0;
      if (!as_double(pv, t, &d)) {
        g_value_unset(out);
        return false;
      }
      g_value_set_float(out, static_cast<gfloat>(d));
      return true;
    }
    case G_TYPE_DOUBLE: {
      double d = 0;
      if (!as_double(pv, t, &d)) {
        g_value_unset(out);
        return false;
      }
      g_value_set_double(out, d);
      return true;
    }
    case G_TYPE_STRING: {
      if (Z_TYPE_P(pv) == IS_NULL) {
        g_value_set_string(out, nullptr);
      } else {
        // Weak mode converts what PHP would convert for a string parameter (scalars and
        // __toString); strict mode takes a string and nothing else. zval_get_string() is what
        // does the conversion either way, so the reference it returns is released below.
        if (Z_TYPE_P(pv) != IS_STRING && (caller_is_strict() || !weak_to_string_ok(pv))) {
          g_value_unset(out);
          zend_type_error("cannot assign %s to a value of type %s", zend_zval_value_name(pv),
                          g_type_name(t));
          return false;
        }
        zend_string *s = zval_get_string(pv);
        const bool ok = check_utf8(s, 0);
        if (ok) g_value_set_string(out, ZSTR_VAL(s));
        zend_string_release(s);
        if (!ok) {
          g_value_unset(out);
          return false;
        }
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
      if (G_TYPE_IS_INSTANTIATABLE(t) && fundamental_class_for_type(t) != nullptr) {
        if (Z_TYPE_P(pv) == IS_NULL) {
          g_value_set_instance(out, nullptr);
          return true;
        }
        gpointer instance = unwrap_fundamental(pv, t);
        if (instance == nullptr) {
          g_value_unset(out);
          return false;
        }
        g_value_set_instance(out, instance);  // the value takes its own reference
        return true;
      }
      g_value_unset(out);
      zend_type_error("to_gvalue: unsupported GType %s", g_type_name(t));
      return false;
  }
}

}  // namespace phpgtk
