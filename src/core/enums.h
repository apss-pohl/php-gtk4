// GEnum <-> PHP enum, GFlags <-> int.
//
// A GEnum type with a registered PHP enum (int-backed, cases declared in the
// stub) converts to the enum case and accepts either a case or a plain int.
// Unregistered enum types stay plain ints. GFlags are always ints (bitmasks
// cannot be PHP enums); their values live in constant classes.
#pragma once
#include "php_gtk4.h"

namespace phpgtk {

// MINIT: bind a PHP enum class to a GEnum GType.
void register_enum(GType type, zend_class_entry *ce);
// MINIT: bind a constant class to a GFlags GType (verified like enums: every
// `const int` must be a flag value of the type, or 0).
void register_flags(GType type, zend_class_entry *ce);
// RINIT (once): every PHP case must exist in the GEnumClass with the same value
// (nick is not checked); a mismatch is a fatal error - the stub drifted from the
// GTK headers. Cannot run at MINIT: internal enum case objects do not exist yet.
void enums_verify();
// MINIT of a dl()-loaded module: take the reference on every case name that the engine's
// backed-enum table drops at unload but never took. zend_enum_add_case() assumes the name is
// interned; under OPcache a name created at runtime is not (accel_init_interned_string_for_php
// only finds strings already in shared memory), so the table's ZVAL_PTR_DTOR efree()d a
// persistent string at request shutdown: "zend_mm_heap corrupted".
// shortcut: compensates a php-src bug (Zend/zend_enum.c, still in master), drop when PHP takes
// the reference itself.
void enums_hold_case_names(int module_number);
// Registry lookup by GType: the PHP enum (or flags constant class) bound to it, nullptr if none.
zend_class_entry *enum_class_for_type(GType type);

// gint -> case object (or long if the type is not registered / value unknown).
void enum_to_php(GType type, gint value, zval *rv);
// case object of the matching class, or int -> gint. Returns false (TypeError thrown) otherwise.
bool enum_from_php(zval *value, GType type, gint *out);

}  // namespace phpgtk
