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
zend_class_entry *enum_class_for_type(GType type);

// gint -> case object (or long if the type is not registered / value unknown).
void enum_to_php(GType type, gint value, zval *rv);
// case object of the matching class, or int -> gint. Returns false (TypeError thrown) otherwise.
bool enum_from_php(zval *value, GType type, gint *out);

}  // namespace phpgtk
