// The GtkBuilderScope that resolves a <signal handler="..."> to a PHP callable.
//
// GTK 4 replaced gtk_builder_connect_signals() with GtkBuilderScope, and the default
// implementation (GtkBuilderCScope) looks a handler up as a C symbol - so every .ui document with
// a <signal> element failed with "No function named `on_click`". This scope answers from the array
// GtkBuilder::set_handlers() was given and delegates the rest of the interface (resolving
// class="GtkLabel" to a GType) to a GtkBuilderCScope it owns: GtkBuilderCScope is a final type, so
// composition is the only way to keep GTK's own resolution.
#include "core/callback.h"
#include "core/gsignal.h"

namespace {

struct PhpBuilderScope {
  GObject parent;
  GtkBuilderScope *c;  // owned; the C scope this one delegates type resolution to
  zval handlers;       // PHP array of name => callable, or IS_UNDEF
};

struct PhpBuilderScopeClass {
  GObjectClass parent;
};

GObjectClass *scope_parent_class = nullptr;

// The C scope's own vtable: gtk_builder_scope_*() are internal to GTK, so delegation goes
// through the interface struct, which is public.
GtkBuilderScopeInterface *c_iface(PhpBuilderScope *scope) {
  return GTK_BUILDER_SCOPE_GET_IFACE(scope->c);
}

// GtkBuilderScopeInterface.get_type_from_name: GTK's own resolution, unchanged.
GType scope_type_from_name(GtkBuilderScope *self, GtkBuilder *builder, const char *type_name) {
  auto *scope = reinterpret_cast<PhpBuilderScope *>(self);
  return c_iface(scope)->get_type_from_name(scope->c, builder, type_name);
}

// GtkBuilderScopeInterface.get_type_from_function: GTK's own resolution, unchanged.
GType scope_type_from_function(GtkBuilderScope *self, GtkBuilder *builder, const char *name) {
  auto *scope = reinterpret_cast<PhpBuilderScope *>(self);
  return c_iface(scope)->get_type_from_function(scope->c, builder, name);
}

// GtkBuilderScopeInterface.create_closure: the handler array first, GTK's C lookup after.
GClosure *scope_create_closure(GtkBuilderScope *self, GtkBuilder *builder, const char *name,
                               GtkBuilderClosureFlags flags, GObject *object, GError **error) {
  auto *scope = reinterpret_cast<PhpBuilderScope *>(self);
  zval *handler = Z_TYPE(scope->handlers) == IS_ARRAY
                      ? zend_hash_str_find(Z_ARRVAL(scope->handlers), name, strlen(name))
                      : nullptr;
  if (handler == nullptr) {  // not ours: let the C scope report it in GTK's own words
    return c_iface(scope)->create_closure(scope->c, builder, name, flags, object, error);
  }
  // swapped="yes" and object="..." rebind what the handler runs on; a PHP callable is already
  // bound (a closure captures with `use`), so honouring either would be a lie.
  if ((static_cast<unsigned>(flags) & static_cast<unsigned>(GTK_BUILDER_CLOSURE_SWAPPED)) != 0U
      || object != nullptr) {
    g_set_error(error, GTK_BUILDER_ERROR, GTK_BUILDER_ERROR_INVALID_ATTRIBUTE,
                "handler '%s': swapped=\"yes\" and object=\"...\" are not supported for a PHP "
                "handler - capture what it needs with `use` instead",
                name);
    return nullptr;
  }
  zend_string *origin = zend_string_init(name, strlen(name), false);
  GClosure *closure = phpgtk::php_closure_new(handler, origin);
  zend_string_release(origin);

  return closure;
}

// GInterfaceInitFunc for GtkBuilderScope.
void scope_iface_init(gpointer iface, gpointer) {
  auto *i = static_cast<GtkBuilderScopeInterface *>(iface);
  i->get_type_from_name = scope_type_from_name;
  i->get_type_from_function = scope_type_from_function;
  i->create_closure = scope_create_closure;
}

// GObjectClass.finalize: the handler array is a PHP value, so it is parked rather than released
// here - a GTK finalize frame is exactly where dropping the last reference is unsafe.
void scope_finalize(GObject *object) {
  auto *scope = reinterpret_cast<PhpBuilderScope *>(object);
  g_clear_object(&scope->c);
  if (Z_TYPE(scope->handlers) != IS_UNDEF) {
    phpgtk::callback_park(&scope->handlers);
    ZVAL_UNDEF(&scope->handlers);
  }
  scope_parent_class->finalize(object);
}

// GClassInitFunc.
void scope_class_init(gpointer klass, gpointer) {
  scope_parent_class = static_cast<GObjectClass *>(g_type_class_peek_parent(klass));
  G_OBJECT_CLASS(klass)->finalize = scope_finalize;
}

// GInstanceInitFunc: the C scope it delegates to, and an empty handler map.
void scope_instance_init(GTypeInstance *instance, gpointer) {
  auto *scope = reinterpret_cast<PhpBuilderScope *>(instance);
  scope->c = GTK_BUILDER_SCOPE(gtk_builder_cscope_new());
  ZVAL_UNDEF(&scope->handlers);
}

// The scope's GType, registered once per process (like every other GType registry here).
GType php_builder_scope_type() {
  static gsize once = 0;
  // NOLINTNEXTLINE(performance-no-int-to-ptr) g_once_init_enter() macro expansion
  if (g_once_init_enter(&once) != 0) {
    const GTypeInfo info = {
        .class_size = sizeof(PhpBuilderScopeClass),
        .base_init = nullptr,
        .base_finalize = nullptr,
        .class_init = scope_class_init,
        .class_finalize = nullptr,
        .class_data = nullptr,
        .instance_size = sizeof(PhpBuilderScope),
        .n_preallocs = 0,
        .instance_init = scope_instance_init,
        .value_table = nullptr,
    };
    const GType type =
        g_type_register_static(G_TYPE_OBJECT, "PhpGtk4BuilderScope", &info, GTypeFlags(0));
    const GInterfaceInfo iface = {scope_iface_init, nullptr, nullptr};
    g_type_add_interface_static(type, GTK_TYPE_BUILDER_SCOPE, &iface);
    g_once_init_leave(&once, type);
  }
  return static_cast<GType>(once);
}

}  // namespace
