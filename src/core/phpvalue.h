// PhpValue: a GObject subclass (GType "PhpValue") carrying a zval, so PHP data
// can be stored wherever GTK wants GObjects (GListStore, ...). The zval is
// released in the GObject finalize, which our handles guarantee happens while
// Zend is alive; instances that GTK keeps beyond the request are drained in
// RSHUTDOWN via phpvalue_request_shutdown().
#pragma once
#include "php_gtk4.h"

G_BEGIN_DECLS
#define PHP_TYPE_VALUE (php_value_get_type())
G_DECLARE_FINAL_TYPE(PhpValue, php_value, PHP, VALUE, GObject)
G_END_DECLS

namespace phpgtk {
GObject *php_value_new(zval *value);  // new instance holding a copy of value
zval *php_value_get(PhpValue *self);  // borrowed
// Replace the stored value (ADDREF the new one, release the old one afterwards).
void php_value_set(PhpValue *self, zval *value);
// RSHUTDOWN: drain the values of the instances GTK still holds, before Zend is gone.
void phpvalue_request_shutdown();
}  // namespace phpgtk
