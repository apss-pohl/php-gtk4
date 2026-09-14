// Gtk4\GVariant - a GVariant with a type PHP spelled, for the values inference cannot type
// (a tuple inside a variant, a byte array from a string, an integer wider than `i`). Not a
// GObject (GVariant is a refcounted, immutable GLib value), so it has its own object layout;
// core/variant accepts the handle wherever a value is expected (variant_of_handle()).
#include "php_gtk4.h"
#include "classes.h"
#include "core/variant.h"

using namespace phpgtk;

namespace {

struct VariantObject {
  GVariant *variant;
  zend_object std;
};

zend_object_handlers handlers;

// zend_object -> our embedding struct.
VariantObject *from_zend(zend_object *o) {
  return reinterpret_cast<VariantObject *>(reinterpret_cast<char *>(o) -
                                           XtOffsetOf(VariantObject, std));
}

// create_object handler: an empty handle until __construct() fills it.
zend_object *create_object(zend_class_entry *ce) {
  auto *self = static_cast<VariantObject *>(zend_object_alloc(sizeof(VariantObject), ce));
  self->variant = nullptr;
  zend_object_std_init(&self->std, ce);
  object_properties_init(&self->std, ce);
  self->std.handlers = &handlers;
  return &self->std;
}

// free_obj handler: drop the handle's reference.
void free_obj(zend_object *o) {
  VariantObject *self = from_zend(o);
  if (self->variant != nullptr) g_variant_unref(self->variant);
  zend_object_std_dtor(o);
}

// clone_obj handler: a GVariant is immutable, so the copy shares the value.
zend_object *clone_obj(zend_object *o) {
  VariantObject *self = from_zend(o);
  zend_object *copy = create_object(o->ce);
  from_zend(copy)->variant = self->variant != nullptr ? g_variant_ref(self->variant) : nullptr;
  zend_objects_clone_members(copy, o);
  return copy;
}

// compare handler: 0 when g_variant_equal() says the two values are the same (type included),
// so == works by value.
int compare_objects(zval *a, zval *b) {
  ZEND_COMPARE_OBJECTS_FALLBACK(a, b);
  GVariant *x = from_zend(Z_OBJ_P(a))->variant;
  GVariant *y = from_zend(Z_OBJ_P(b))->variant;
  if (x == nullptr || y == nullptr) return x == y ? 0 : 1;
  return g_variant_equal(x, y) ? 0 : 1;
}

// get_debug_info handler: var_dump() shows the type string and the plain value.
HashTable *get_debug_info(zend_object *o, int *is_temp) {
  VariantObject *self = from_zend(o);
  HashTable *ht = zend_new_array(2);
  *is_temp = 1;
  if (self->variant == nullptr) return ht;
  zval z;
  ZVAL_STRING(&z, g_variant_get_type_string(self->variant));
  zend_hash_str_update(ht, ZEND_STRL("type"), &z);
  variant_to_php(self->variant, &z);
  zend_hash_str_update(ht, ZEND_STRL("value"), &z);
  return ht;
}

// The handle's value, or nullptr with an Error thrown for one __construct() never filled (the
// class is final and internal, so only a constructor that threw leaves such a handle).
GVariant *self_variant(zend_object *o) {
  GVariant *v = from_zend(o)->variant;
  if (v == nullptr) zend_throw_error(nullptr, "GVariant was created without a value");
  return v;
}

}  // namespace

/**
 * Gtk4\GVariant::__construct(string $type, mixed $value)
 *
 * Converts `$value` to the GVariant type `$type` spells, exactly like a parameter of that type
 * (`strict_types` honoured, `check_range` for the integers, valid UTF-8 for the strings).
 */
ZEND_METHOD(Gtk4_GVariant, __construct) {
  zend_string *type = nullptr;
  zval *value = nullptr;
  ZEND_PARSE_PARAMETERS_START(2, 2)
  Z_PARAM_STR(type)
  Z_PARAM_ZVAL(value)
  ZEND_PARSE_PARAMETERS_END();
  if (!g_variant_type_string_is_valid(ZSTR_VAL(type)) ||
      !g_variant_type_is_definite(G_VARIANT_TYPE(ZSTR_VAL(type)))) {
    zend_argument_value_error(1, "must be a definite GVariant type string, \"%s\" given",
                              ZSTR_VAL(type));
    RETURN_THROWS();
  }
  GVariant *v = php_to_variant(value, G_VARIANT_TYPE(ZSTR_VAL(type)));
  if (v == nullptr) RETURN_THROWS();
  VariantObject *self = from_zend(Z_OBJ_P(ZEND_THIS));
  if (self->variant != nullptr) g_variant_unref(self->variant);  // __construct() called again
  self->variant = g_variant_ref_sink(v);
}

/**
 * Gtk4\GVariant::get_type_string(): string
 *
 * The type string the value was built with.
 */
ZEND_METHOD(Gtk4_GVariant, get_type_string) {
  ZEND_PARSE_PARAMETERS_NONE();
  GVariant *v = self_variant(Z_OBJ_P(ZEND_THIS));
  if (v == nullptr) RETURN_THROWS();
  RETURN_STRING(g_variant_get_type_string(v));
}

/**
 * Gtk4\GVariant::unpack(): mixed
 *
 * The plain PHP value, converted the way every GVariant crosses back into PHP (a tuple a list, a
 * dictionary an associative array, a `v` its inner value).
 */
ZEND_METHOD(Gtk4_GVariant, unpack) {
  ZEND_PARSE_PARAMETERS_NONE();
  GVariant *v = self_variant(Z_OBJ_P(ZEND_THIS));
  if (v == nullptr) RETURN_THROWS();
  variant_to_php(v, return_value);
}

/**
 * Gtk4\GVariant::print(bool $type_annotate = false): string
 *
 * GLib's text form of the value (`g_variant_print()`), with the type annotated on request.
 */
ZEND_METHOD(Gtk4_GVariant, print) {
  bool type_annotate = false;
  ZEND_PARSE_PARAMETERS_START(0, 1)
  Z_PARAM_OPTIONAL
  Z_PARAM_BOOL(type_annotate)
  ZEND_PARSE_PARAMETERS_END();
  GVariant *v = self_variant(Z_OBJ_P(ZEND_THIS));
  if (v == nullptr) RETURN_THROWS();
  gchar *text = g_variant_print(v, type_annotate ? TRUE : FALSE);
  RETVAL_STRING(text);
  g_free(text);
}

namespace phpgtk {
zend_class_entry *ce_GVariant = nullptr;

// The value a Gtk4\GVariant handle carries, or nullptr when `value` is anything else.
GVariant *variant_of_handle(zval *value) {
  if (Z_TYPE_P(value) != IS_OBJECT || Z_OBJCE_P(value) != ce_GVariant) return nullptr;
  return from_zend(Z_OBJ_P(value))->variant;
}

// MINIT: install the object handlers on the class entry.
void register_GVariant(zend_class_entry *ce) {
  ce_GVariant = ce;
  memcpy(&handlers, &std_object_handlers, sizeof(zend_object_handlers));
  handlers.offset = XtOffsetOf(VariantObject, std);
  handlers.free_obj = free_obj;
  handlers.clone_obj = clone_obj;
  handlers.compare = compare_objects;
  handlers.get_debug_info = get_debug_info;
  ce->create_object = create_object;
}
}  // namespace phpgtk
