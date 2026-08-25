#include "main.h"

#include <Zend/zend_modules.h>

#include <php_version.h>
#if PHP_VERSION_ID < 80400
#error "php-gtk4 requires PHP >= 8.4"
#endif

extern "C" {

PHPCPP_EXPORT void *get_module() {
  // Name "gtk4" must match Makefile NAME and gtk4.ini (extension=gtk4).
  static Php::Extension extension("gtk4", "0.1.0");

  // Everything PHP-visible lives in the Gtk4 namespace (classes and
  // constants) so gtk3 and gtk4 can be loaded into the same process.
  Php::Namespace ns(phpgtk::PHP_NAMESPACE);
  ns.add(Php::Constant("PHPGTK_VERSION", "0.1.0"));
  ns.add(Php::Constant("PHPGTK_BUILD_INFO", phpgtk_build_info()));
  ns.add(Php::Constant("PHPGTK_FEATURES", phpgtk_build_features()));
  extension.add(Php::Ini("gtk4.build_info", phpgtk_build_info(), Php::Ini::Place::System));
  extension.add(Php::Ini("gtk4.features", phpgtk_build_features(), Php::Ini::Place::System));

  // Registration order matters: parents before children. Each register_*()
  // is responsible for its own namespace section; generated ones plug in here.
  phpgtk::register_Gtk(ns);
  extension.add(std::move(ns));

  // PHP-level names no longer clash with php-gtk3 (we are namespaced), but
  // libgtk-3 and libgtk-4 export the same C symbols (gtk_window_new, ...).
  // Whichever library the process loaded first wins the symbol resolution,
  // so with gtk3 loaded first every gtk4 call silently lands in GTK 3 and
  // fails its type assertions. Nothing can be done from here except warn.
  extension.onStartup([]() {
    if (zend_hash_str_exists(&module_registry, "gtk3", sizeof("gtk3") - 1)) {
      Php::warning << "php-gtk4: extension 'gtk3' is also loaded. libgtk-3 and libgtk-4 cannot "
                      "share a process (identical C symbols); gtk4 calls will misbehave. Load "
                      "only one of them (e.g. bin/php-gtk4, or php -n -dextension=gtk4)."
                   << std::flush;
    }
  });

  return extension;
}
}
