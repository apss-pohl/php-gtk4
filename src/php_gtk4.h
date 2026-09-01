// php-gtk4 module header: everything a translation unit needs to talk to Zend.
#pragma once

#ifdef HAVE_CONFIG_H
#include "config.h"
#endif

#include <php.h>
#include <zend_exceptions.h>
#include <zend_interfaces.h>
#include <zend_enum.h>
// Neither header has BEGIN_EXTERN_C guards; without this MSVC looks the spl_ce_* class
// entries up C++-mangled and the DLL fails to link (Linux linkers get the same names
// from the .so by accident). php-src's intl extension does the same.
extern "C" {
#include <ext/standard/info.h>
#include <ext/spl/spl_exceptions.h>
}

#include <gtk/gtk.h>

#if PHP_VERSION_ID < 80400
#error "php-gtk4 requires PHP >= 8.4"
#endif
// ZTS is supported (per-request state is in module globals, core/globals.h);
// GTK itself stays single-threaded - see assert_gui_thread().

#define PHP_GTK4_VERSION "0.1.0-dev"
#define PHP_GTK4_NAMESPACE "Gtk4"

#ifndef PHPGTK_BUILD_INFO
#define PHPGTK_BUILD_INFO "built unknown, git unknown"
#endif
#ifndef PHPGTK_BUILD_FEATURES
#define PHPGTK_BUILD_FEATURES "webkit=no"
#endif

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

// A flags value must be a combination of the type's own bits: GLib otherwise rejects the
// whole assignment with a CRITICAL and carries on with the default, telling PHP nothing.
inline bool check_flags(GType type, zend_long bits, uint32_t arg) {
  auto *klass = static_cast<GFlagsClass *>(g_type_class_ref(type));
  const guint mask = klass->mask;
  g_type_class_unref(klass);
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
