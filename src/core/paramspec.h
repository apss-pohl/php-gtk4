// Gtk4\GParamSpec handle (GParamSpec is a fundamental type, not a GObject).
#pragma once
#include "php_gtk4.h"

namespace phpgtk {
void register_GParamSpec_handlers(zend_class_entry *ce);
// C -> PHP: a new handle holding a ref on spec (null for nullptr).
void wrap_param_spec(GParamSpec *spec, zval *rv);
}  // namespace phpgtk
