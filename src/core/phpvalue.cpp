#include "phpvalue.h"

#include <unordered_set>

#include "object.h"
#include "globals.h"

struct _PhpValue {
  GObject parent_instance;
  zval value;
};

// NOLINTNEXTLINE(performance-no-int-to-ptr) GLib type-registration macro
G_DEFINE_TYPE(PhpValue, php_value, G_TYPE_OBJECT)

namespace {
// Every live instance, so RSHUTDOWN can drop zvals GTK still references.
std::unordered_set<PhpValue *> &live() {
  return GTK4_G(phpvalues);
}
}  // namespace

// The three functions G_DEFINE_TYPE declares `static` (class_init/init by name, finalize from
// class_init) must stay file-static, not anonymous-namespace: the macro's declaration is the
// contract.

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
  live().clear();  // instances GTK keeps past this request finalize with nothing to release
}

}  // namespace phpgtk
