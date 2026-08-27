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

extern zend_module_entry gtk4_module_entry;
#define phpext_gtk4_ptr &gtk4_module_entry

// `const char *` that may be NULL (transfer none) -> ?string.
#define PHPGTK_RETURN_STRING_OR_NULL(expr)   \
  do {                                       \
    const char *phpgtk_s_ = (expr);          \
    if (phpgtk_s_ == nullptr) RETURN_NULL(); \
    RETURN_STRING(phpgtk_s_);                \
  } while (0)
