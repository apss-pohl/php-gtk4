// Gtk4\GListModel - the interface. Its methods are implemented once here and
// aliased into every implementing class from the stub (@implementation-alias).
#pragma once
#include "php_gtk4.h"

namespace phpgtk {
extern zend_class_entry *ce_GListModel;
}  // namespace phpgtk

// Alias targets: gen_stub declares no prototype for an interface's (abstract) methods,
// but the ZEND_MALIAS entries of the implementing classes point at these.
ZEND_METHOD(Gtk4_GListModel, get_item_type);
ZEND_METHOD(Gtk4_GListModel, get_n_items);
ZEND_METHOD(Gtk4_GListModel, get_item);
