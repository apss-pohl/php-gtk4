// Gtk4\GParamSpec - a fundamental handle (see fundamental.h).
#pragma once
#include "php_gtk4.h"

namespace phpgtk {
// C -> PHP: a new handle holding a ref on spec (null for nullptr).
void wrap_param_spec(GParamSpec *spec, zval *rv);
}  // namespace phpgtk
