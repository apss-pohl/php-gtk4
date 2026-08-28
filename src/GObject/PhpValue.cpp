// Gtk4\PhpValue: our own GObject subclass carrying a PHP value (GType in core/phpvalue).
#include "php_gtk4.h"
#include "core/object.h"
#include "core/phpvalue.h"

using namespace phpgtk;

/**
 * Gtk4\PhpValue::__construct(mixed $value = null)
 *
 * A GObject carrying any PHP value, so PHP data can live in a `GListStore`.
 */
ZEND_METHOD(Gtk4_PhpValue, __construct) {
  zval *value = nullptr;
  ZEND_PARSE_PARAMETERS_START(0, 1)
  Z_PARAM_OPTIONAL
  Z_PARAM_ZVAL(value)
  ZEND_PARSE_PARAMETERS_END();
  zval null_zv;
  ZVAL_NULL(&null_zv);
  attach_new(object_from_zval(ZEND_THIS), php_value_new(value != nullptr ? value : &null_zv));
}

/**
 * Gtk4\PhpValue::get_value(): mixed
 *
 * The carried value.
 */
ZEND_METHOD(Gtk4_PhpValue, get_value) {
  ZEND_PARSE_PARAMETERS_NONE();
  PhpValue *self = PHPGTK_SELF(PhpValue, PHP_TYPE_VALUE);
  zval *v = php_value_get(self);
  if (Z_ISUNDEF_P(v)) RETURN_NULL();
  RETURN_COPY(v);
}

/**
 * Gtk4\PhpValue::set_value(mixed $value): void
 *
 * Replace the carried value.
 */
ZEND_METHOD(Gtk4_PhpValue, set_value) {
  zval *value;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_ZVAL(value)
  ZEND_PARSE_PARAMETERS_END();
  PhpValue *self = PHPGTK_SELF(PhpValue, PHP_TYPE_VALUE);
  php_value_set(self, value);
}
