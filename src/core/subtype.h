// PHP subclasses as real GTypes. `class MyButton extends GtkButton` gets its own
// GType ("Php__MyButton", parent GTK_TYPE_BUTTON) the first time it is instantiated;
// constructors create instances of that type (subtype_new), and a class_init
// installs a thunk into every class-struct slot for which the PHP class defines a
// `vfunc_<name>()` method (generated per class: gen/gir.php emits the thunks and
// registers them with register_vfunc()). Thunks call the PHP method on the existing
// handle; an instance without a handle (mid-construction, after RSHUTDOWN) chains to
// the native implementation, as does `parent::vfunc_<name>()` through the generated
// native methods (subtype_native_class()). Design: README.md "Design".
//
// GTypes cannot be unregistered, so the type registry is process-wide (one mutex for
// ZTS); the PHP class is re-resolved by name per request (subtype_class_for_gtype).
#pragma once
#include "php_gtk4.h"

namespace phpgtk {

// Generated per vfunc: write the thunk into the class struct.
using VfuncInstall = void (*)(gpointer klass);
// MINIT (generated register_vfuncs_<Class>()): vfunc `name` of the native type `owner`.
void register_vfunc(GType owner, const char *name, VfuncInstall install);
// MINIT (generated register_vfuncs_<Interface>()): slot `name` of the interface `iface`. A PHP
// class that `implements` the interface's PHP counterpart gets the interface added to its GType
// (g_type_add_interface_static) with every registered slot pointing at a thunk that calls the
// PHP method of the same name.
void register_iface_vfunc(GType iface, const char *name, VfuncInstall install);

// True for a GType registered here for a PHP class.
bool is_php_type(GType type);

// The GType of a PHP user class extending a registered GObject class, registering it (and
// its PHP ancestors) on first use. 0 for a registered native class, or on failure with an
// Error thrown.
GType subtype_for_class(zend_class_entry *ce);

// Constructors: when the class of `self` (ZEND_THIS) is a PHP subclass, g_object_new() of
// its GType with the NULL-terminated property list (name/value varargs like g_object_new)
// and return the instance for attach*() - the handle is already bound to it by the
// instance_init, attach*() only settles the reference; nullptr when the class is the
// native one (use the C constructor) or on failure (exception thrown, check EG(exception)).
GObject *subtype_new(zval *self, const char *first_property, ...);

// Thunks: the PHP `vfunc_<name>` to call on `obj` and the handle as `self` (an owned zval,
// caller zval_ptr_dtor()s it); nullptr when the instance has no handle or no such user
// method - chain to the native implementation then.
zend_function *subtype_vfunc(GObject *obj, const char *method, zval *self);

// The class struct of the nearest non-PHP ancestor of obj's type (for `parent::vfunc_x()`
// and for thunks chaining to GTK).
gpointer subtype_native_class(GObject *obj);

// Thunks whose slot returns a borrowed pointer (transfer none: GAction.get_name's
// `const char *`, get_state_type's `const GVariantType *`): the instance owns the C copy of
// PHP's answer (qdata per slot), replaced only when the answer changes, so a caller keeping
// the pointer sees it live for as long as GLib's contract - a constant name, a constant
// type - lets it. The type variant validates the string first: nullptr with a TypeError
// (naming `origin`, the PHP method) when it is not a GVariant type string.
const char *subtype_keep_string(GObject *obj, const char *slot, const char *value);
// The GVariantType twin: validated first, nullptr with a TypeError naming `origin` otherwise.
const GVariantType *subtype_keep_variant_type(GObject *obj, const char *origin, const char *slot,
                                              const char *value);

// RSHUTDOWN: drop the per-request vfunc lookup cache (keyed by class entries that die now).
void subtype_request_shutdown();

// wrap(): the PHP class registered for a PHP GType in this request, nullptr if the class
// does not exist here (a different request declared it) - fall back to the native parent.
zend_class_entry *subtype_class_for_gtype(GType type);

}  // namespace phpgtk
