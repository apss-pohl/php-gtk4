// php-gtk4 module header: everything a translation unit needs to talk to Zend.
#pragma once

#ifdef HAVE_CONFIG_H
#include "config.h"
#endif

#include <php.h>
#include <zend_exceptions.h>
#include <zend_interfaces.h>
#include <zend_enum.h>
#include <ext/standard/info.h>
#include <ext/spl/spl_exceptions.h>

#include <gtk/gtk.h>

#if PHP_VERSION_ID < 80400
#error "php-gtk4 requires PHP >= 8.4"
#endif

#define PHP_GTK4_VERSION "0.1.0"
#define PHP_GTK4_NAMESPACE "Gtk4"

#ifndef PHPGTK_BUILD_INFO
#define PHPGTK_BUILD_INFO "built unknown, git unknown"
#endif
#ifndef PHPGTK_BUILD_FEATURES
#define PHPGTK_BUILD_FEATURES "webkit=no"
#endif

extern zend_module_entry gtk4_module_entry;
#define phpext_gtk4_ptr &gtk4_module_entry
