#include "main.h"

#include <php_version.h>
#if PHP_VERSION_ID < 80400
#error "php-gtk4 requires PHP >= 8.4"
#endif

extern "C" {

PHPCPP_EXPORT void *get_module() {
  // Name "gtk4" must match Makefile NAME and gtk4.ini (extension=gtk4).
  static Php::Extension extension("gtk4", "0.1.0");

  extension.add(Php::Constant("PHPGTK_VERSION", "0.1.0"));
  extension.add(Php::Constant("PHPGTK_BUILD_INFO", phpgtk_build_info()));
  extension.add(Php::Constant("PHPGTK_FEATURES", phpgtk_build_features()));
  extension.add(Php::Ini("gtk4.build_info", phpgtk_build_info(), Php::Ini::Place::System));
  extension.add(Php::Ini("gtk4.features", phpgtk_build_features(), Php::Ini::Place::System));

  // Registration order matters: parents before children. Each register_*()
  // is responsible for its own namespace; generated namespaces plug in here.
  phpgtk::register_Gtk(extension);

  return extension;
}
}
