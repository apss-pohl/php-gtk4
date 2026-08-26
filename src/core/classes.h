// Class entries of the non-GObject classes (GObject-derived ones come from
// the registry in object.h). Set in MINIT (src/gtk4.cpp).
#pragma once
#include "php_gtk4.h"

namespace phpgtk {
extern zend_class_entry *ce_ExceptionMode;
extern zend_class_entry *ce_GMainLoop;
extern zend_class_entry *ce_GParamSpec;
extern zend_class_entry *ce_GListModel;  // interface
extern zend_class_entry *ce_CairoContext;
}  // namespace phpgtk
