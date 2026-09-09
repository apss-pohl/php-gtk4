// php-gtk4 module header: everything a translation unit needs to talk to Zend.
#pragma once

#ifdef HAVE_CONFIG_H
#include "config.h"
#endif

#include <php.h>
#include <zend_exceptions.h>
#include <zend_interfaces.h>
#include <zend_enum.h>
#include <zend_strtod.h>
// Neither header has BEGIN_EXTERN_C guards; without this MSVC looks the spl_ce_* class
// entries up C++-mangled and the DLL fails to link (Linux linkers get the same names
// from the .so by accident). php-src's intl extension does the same.
extern "C" {
#include <ext/standard/info.h>
#include <ext/spl/spl_exceptions.h>
#include <main/fopen_wrappers.h>
}

#include <gtk/gtk.h>
// graphene's GType macros (GRAPHENE_TYPE_RECT, ...) live in their own header, which gtk.h does
// not pull in; the geometry GtkSnapshot takes is bound as boxed records, so every namespace can
// need them. The include directory is already on the search path through pkg-config gtk4.
#include <graphene-gobject.h>

#if PHP_VERSION_ID < 80400
#error "php-gtk4 requires PHP >= 8.4"
#endif
// ZTS is supported (per-request state is in module globals, core/globals.h);
// GTK itself stays single-threaded - see assert_gui_thread().

#define PHP_GTK4_VERSION "0.2.0-dev"
#define PHP_GTK4_NAMESPACE "Gtk4"

#ifndef PHPGTK_BUILD_INFO
#define PHPGTK_BUILD_INFO "built unknown, git unknown"
#endif
#ifndef PHPGTK_BUILD_FEATURES
#define PHPGTK_BUILD_FEATURES "webkit=no"
#endif

#include <array>
#include <cmath>
#include <cstring>
#include <type_traits>

extern zend_module_entry gtk4_module_entry;
// NOLINTNEXTLINE(readability-identifier-naming) the name Zend's static-build glue expects
#define phpext_gtk4_ptr &gtk4_module_entry

// ---- argument checks shared by hand-written and generated methods --------------------
// GLib strings are NUL-terminated UTF-8. A PHP string is neither by definition, and passing
// one through unchecked meant an embedded NUL silently truncated what GTK stored (PHP saw
// "a\0b", the window title said "a") while invalid UTF-8 tripped a g_return_if_fail deep
// inside GLib, where the value was dropped with only a CRITICAL on stderr. Both are argument
// errors and are reported as such. `arg` is the 1-based argument number, 0 for a value that
// is not an argument (a property write, a signal argument).
namespace phpgtk {
inline bool check_utf8(const zend_string *s, uint32_t arg) {
  const char *val = ZSTR_VAL(s);
  const size_t len = ZSTR_LEN(s);
  if (memchr(val, '\0', len) != nullptr) {
    if (arg != 0) {
      zend_argument_value_error(arg, "must not contain a null byte (GTK strings end at one)");
    } else {
      zend_value_error("string must not contain a null byte (GTK strings end at one)");
    }
    return false;
  }
  if (!g_utf8_validate(val, static_cast<gssize>(len), nullptr)) {
    if (arg != 0) {
      zend_argument_value_error(arg, "must be valid UTF-8");
    } else {
      zend_value_error("string must be valid UTF-8");
    }
    return false;
  }
  return true;
}

// PHP integers are 64-bit and signed; most C parameters are neither. Without this a
// negative value became a huge unsigned one (`$store->remove(-1)` reached GTK as
// 4294967295) and a too-large one was truncated, in both cases silently.
template <typename T>
inline bool check_range(zend_long v, uint32_t arg) {
  static_assert(std::is_integral_v<T> && sizeof(T) <= sizeof(zend_long), "wider than zend_long");
  constexpr int bits = static_cast<int>(sizeof(T) * 8);
  constexpr bool is_signed = std::is_signed_v<T>;
  // Derived from the width rather than std::numeric_limits<T>, so a one-byte type does not put
  // a `char` through an integer conversion. An unsigned 64-bit parameter (gsize) keeps
  // ZEND_LONG_MAX as its ceiling: PHP cannot express more, so the real bound is the floor.
  // A 64-bit T takes the ZEND_LONG bounds and never uses `shift`, but both arms of a ?: are
  // still compiled, so the shift count has to stay in range for it too - MSVC diagnoses the
  // discarded `1 << 64` and `1 << 63` (C4293/C4307) and /W3 is a gate on Windows.
  constexpr int shift = bits >= 64 ? 1 : bits;
  constexpr zend_long widest = bits >= 64 ? ZEND_LONG_MAX : (zend_long{1} << shift) - 1;
  constexpr zend_long signed_max = bits >= 64 ? ZEND_LONG_MAX : (zend_long{1} << (shift - 1)) - 1;
  constexpr zend_long signed_min = bits >= 64 ? ZEND_LONG_MIN : -(zend_long{1} << (shift - 1));
  constexpr zend_long lo = is_signed ? signed_min : 0;
  constexpr zend_long hi = is_signed ? signed_max : widest;
  if (v < lo || v > hi) {
    if (arg != 0) {
      zend_argument_value_error(arg, "must be between " ZEND_LONG_FMT " and " ZEND_LONG_FMT, lo,
                                hi);
    } else {
      zend_value_error("value must be between " ZEND_LONG_FMT " and " ZEND_LONG_FMT, lo, hi);
    }
    return false;
  }
  return true;
}

// A parameter whose C function documents a domain narrower than its type: gtk_calendar_set_month()
// takes an int and accepts 0..11, gtk_grid_attach() a positive span. GTK states these as
// g_return_if_fail(), which means a CRITICAL and the call silently not happening - the property
// keeps its old value and PHP hears nothing. The bounds come from the assertion GTK itself
// prints, and are listed per parameter in PARAM_DOMAINS (gen/gir/config.php). Pass
// ZEND_LONG_MIN / ZEND_LONG_MAX for an open end.
inline bool check_domain(zend_long v, zend_long lo, zend_long hi, uint32_t arg) {
  if (v >= lo && v <= hi) return true;
  if (lo == ZEND_LONG_MIN) {
    zend_argument_value_error(
        arg, "must be less than or equal to " ZEND_LONG_FMT ", " ZEND_LONG_FMT " given", hi, v);
  } else if (hi == ZEND_LONG_MAX) {
    zend_argument_value_error(
        arg, "must be greater than or equal to " ZEND_LONG_FMT ", " ZEND_LONG_FMT " given", lo, v);
  } else {
    zend_argument_value_error(
        arg, "must be between " ZEND_LONG_FMT " and " ZEND_LONG_FMT ", " ZEND_LONG_FMT " given", lo,
        hi, v);
  }
  return false;
}

// A double as PHP itself renders one: through zend_gcvt with an explicit '.', because printf's
// %G follows the C locale and a German one turned "0.5" in an error message into "0,5".
struct DoubleText {
  std::array<char, ZEND_DOUBLE_MAX_LENGTH> buf{};
  explicit DoubleText(double v) { zend_gcvt(v, 17, '.', 'E', buf.data()); }
  [[nodiscard]] const char *c_str() const { return buf.data(); }
};

// The same for a floating-point parameter (gtk_gesture_long_press_set_delay_factor: 0.5 .. 2.0).
// An open end is an infinity - HUGE_VAL, not INFINITY, which is a float and would narrow.
inline bool check_domain_double(double v, double lo, double hi, uint32_t arg) {
  if (v >= lo && v <= hi) return true;
  const DoubleText got(v);
  if (lo == -HUGE_VAL) {
    zend_argument_value_error(arg, "must be less than or equal to %s, %s given",
                              DoubleText(hi).c_str(), got.c_str());
  } else if (hi == HUGE_VAL) {
    zend_argument_value_error(arg, "must be greater than or equal to %s, %s given",
                              DoubleText(lo).c_str(), got.c_str());
  } else {
    zend_argument_value_error(arg, "must be between %s and %s, %s given", DoubleText(lo).c_str(),
                              DoubleText(hi).c_str(), got.c_str());
  }
  return false;
}

// An exclusive lower bound (gsk_path_builder_conic_to: `weight > 0`), where the inclusive
// check_domain_double() would let the boundary through to GTK's assertion.
inline bool check_domain_above(double v, double lo, uint32_t arg) {
  if (v > lo) return true;
  zend_argument_value_error(arg, "must be greater than %s, %s given", DoubleText(lo).c_str(),
                            DoubleText(v).c_str());
  return false;
}

// A filename crossing PHP -> C is made absolute against PHP's *own* working directory first.
// `chdir()` moves a per-thread virtual cwd under ZTS, while GTK, GLib and cairo are C libraries
// that read the process cwd - so a relative path would resolve somewhere else entirely there
// (NTS never notices: the two are the same directory). Answers a zend_string the caller
// releases, or nullptr with a ValueError thrown.
inline zend_string *absolute_filename(zend_string *path, uint32_t arg) {
  std::array<char, MAXPATHLEN> resolved{};
  if (expand_filepath(ZSTR_VAL(path), resolved.data()) == nullptr) {
    if (arg != 0) {
      zend_argument_value_error(arg, "cannot be resolved against the current directory");
    } else {
      zend_value_error("path cannot be resolved against the current directory");
    }
    return nullptr;
  }
  return zend_string_init(resolved.data(), strlen(resolved.data()), false);
}

// The enum counterpart of check_flags(), for a GIR `enumeration` that is not bound as a PHP enum
// (it is not in gen/allowlist.txt, so it crosses as an int): the value has to be one the type
// actually declares. These used to go through check_flags(), which casts the class to
// GFlagsClass and reads `mask` - on a GEnumClass that field is `minimum`, 0 for every one of
// them, so *every* non-zero value was refused with a nonsense message about a mask of 0x0. It
// was also a read of the wrong type. Found on a ZTS run where GTK happened to deliver
// GTK_SYSTEM_SETTING_ICON_THEME to a PHP vfunc_system_setting_changed().
inline bool check_enum_member(GType type, zend_long value, uint32_t arg) {
  // peek first: the class exists once anything of the type was touched, and a ref/unref pair
  // per argument is a GLib lock round trip on every call
  gpointer peeked = g_type_class_peek(type);
  auto *klass = static_cast<GEnumClass *>(peeked != nullptr ? peeked : g_type_class_ref(type));
  const bool known = value >= klass->minimum && value <= klass->maximum &&
                     g_enum_get_value(klass, static_cast<gint>(value)) != nullptr;
  if (peeked == nullptr) g_type_class_unref(klass);
  if (known) return true;
  if (arg != 0) {
    zend_argument_value_error(arg, "must be a valid %s value, " ZEND_LONG_FMT " given",
                              g_type_name(type), value);
  } else {
    zend_value_error("%s: invalid value " ZEND_LONG_FMT, g_type_name(type), value);
  }
  return false;
}

// GMenu's rule for an attribute or link name (gmenu.c valid_attribute_name(), which it asserts
// with g_return_if_fail): a letter, then letters, digits and dashes.
inline bool valid_menu_attribute_name(const char *name) {
  if (name == nullptr || !g_ascii_isalpha(name[0])) return false;
  for (const char *c = name + 1; *c != '\0'; c++) {
    if (!g_ascii_isalnum(*c) && *c != '-') return false;
  }
  return true;
}

// g_application_bind_busy_property() asserts the property exists and is a boolean.
inline bool has_boolean_property(GObject *object, const char *property) {
  GParamSpec *spec = g_object_class_find_property(G_OBJECT_GET_CLASS(object), property);
  return spec != nullptr && spec->value_type == G_TYPE_BOOLEAN;
}

// A flags value must be a combination of the type's own bits: GLib otherwise rejects the
// whole assignment with a CRITICAL and carries on with the default, telling PHP nothing.
inline bool check_flags(GType type, zend_long bits, uint32_t arg) {
  gpointer peeked = g_type_class_peek(type);  // as in check_enum_member()
  auto *klass = static_cast<GFlagsClass *>(peeked != nullptr ? peeked : g_type_class_ref(type));
  const guint mask = klass->mask;
  if (peeked == nullptr) g_type_class_unref(klass);
  if (bits >= 0 && (static_cast<guint64>(bits) & ~static_cast<guint64>(mask)) == 0) {
    return true;
  }
  if (arg != 0) {
    zend_argument_value_error(arg,
                              "must be a combination of %s (mask 0x%x), " ZEND_LONG_FMT " given",
                              g_type_name(type), mask, bits);
  } else {
    zend_value_error("%s: invalid flags value " ZEND_LONG_FMT " (mask 0x%x)", g_type_name(type),
                     bits, mask);
  }
  return false;
}
}  // namespace phpgtk

// `const char *` that may be NULL (transfer none) -> ?string.
#define PHPGTK_RETURN_STRING_OR_NULL(expr)   \
  do {                                       \
    const char *phpgtk_s_ = (expr);          \
    if (phpgtk_s_ == nullptr) RETURN_NULL(); \
    RETURN_STRING(phpgtk_s_);                \
  } while (0)
