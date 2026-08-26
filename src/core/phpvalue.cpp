#include "phpvalue.h"

#include <unordered_set>

#include "object.h"

struct _PhpValue {
  GObject parent_instance;
  zval value;
};

// NOLINTNEXTLINE(performance-no-int-to-ptr) GLib type-registration macro
G_DEFINE_TYPE(PhpValue, php_value, G_TYPE_OBJECT)

namespace {
// Every live instance, so RSHUTDOWN can drop zvals GTK still references.
std::unordered_set<PhpValue *> &live() {
  static std::unordered_set<PhpValue *> set;
  return set;
}
}  // namespace

// GObject finalize: release the PHP value (no-op if RSHUTDOWN already drained it).
static void php_value_finalize(GObject *object) {
  auto *self = PHP_VALUE(object);
  live().erase(self);
  if (!Z_ISUNDEF(self->value)) zval_ptr_dtor(&self->value);
  ZVAL_UNDEF(&self->value);
  G_OBJECT_CLASS(php_value_parent_class)->finalize(object);
}

// GObject class init: install finalize.
static void php_value_class_init(PhpValueClass *klass) {
  G_OBJECT_CLASS(klass)->finalize = php_value_finalize;
}

// GObject instance init: no value yet.
static void php_value_init(PhpValue *self) {
  ZVAL_UNDEF(&self->value);
  live().insert(self);
}

namespace phpgtk {

// New PhpValue instance (floating-free: a GObject with one ref) holding a copy of `value`.
GObject *php_value_new(zval *value) {
  auto *self = PHP_VALUE(g_object_new(PHP_TYPE_VALUE, nullptr));
  php_value_set(self, value);
  return G_OBJECT(self);
}

// Borrowed pointer to the stored zval (IS_UNDEF after RSHUTDOWN drained it).
zval *php_value_get(PhpValue *self) {
  return &self->value;
}

// Replace the stored value (ADDREF the new one, release the old one afterwards).
void php_value_set(PhpValue *self, zval *value) {
  zval old = self->value;
  ZVAL_COPY(&self->value, value);
  if (!Z_ISUNDEF(old)) zval_ptr_dtor(&old);
}

// RSHUTDOWN: instances GTK still holds must not release zvals after Zend is gone.
void phpvalue_request_shutdown() {
  for (PhpValue *self : live()) {
    if (!Z_ISUNDEF(self->value)) zval_ptr_dtor(&self->value);
    ZVAL_UNDEF(&self->value);
  }
}

}  // namespace phpgtk

// ---------------------------------------------------------------- PHP class

using namespace phpgtk;

/**
 * Gtk4\PhpValue::__construct(mixed $value = null)
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
 */
ZEND_METHOD(Gtk4_PhpValue, set_value) {
  zval *value;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_ZVAL(value)
  ZEND_PARSE_PARAMETERS_END();
  PhpValue *self = PHPGTK_SELF(PhpValue, PHP_TYPE_VALUE);
  php_value_set(self, value);
}
