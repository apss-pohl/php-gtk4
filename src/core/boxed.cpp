#include "boxed.h"

#include <string>
#include <unordered_map>

namespace phpgtk {

namespace {

zend_object_handlers handlers;

// GType -> boxed class info (MINIT-filled).
std::unordered_map<GType, BoxedClass> &registry() {
  static std::unordered_map<GType, BoxedClass> map;
  return map;
}

// Registry entry for a handle's GType (nullptr if the type was never registered).
const BoxedClass *info_of(const Boxed *self) {
  return boxed_class_for_type(self->type);
}

// create_object handler: allocates the embedding struct; data is set by the constructor/wrap.
zend_object *create_object(zend_class_entry *ce) {
  auto *self = static_cast<Boxed *>(zend_object_alloc(sizeof(Boxed), ce));
  self->type = 0;
  self->data = nullptr;
  self->owner = nullptr;
  zend_object_std_init(&self->std, ce);
  object_properties_init(&self->std, ce);
  self->std.handlers = &handlers;
  return &self->std;
}

// get_constructor handler: a record the GIR gives no constructor (GtkTextIter: iterators come
// from the buffer) has no `__construct`, and `new X()` would leave a handle whose data is
// nullptr - refuse it; classes with a constructor keep the standard lookup.
zend_function *get_constructor(zend_object *o) {
  if (o->ce->constructor != nullptr) return zend_std_get_constructor(o);
  zend_throw_error(nullptr, "%s instances are created by the extension, not with new",
                   ZSTR_VAL(o->ce->name));
  return nullptr;
}

// free_obj handler: releases the owned copy with g_boxed_free(), then the owner ref.
void free_obj(zend_object *o) {
  Boxed *self = boxed_from_zend(o);
  if (self->data != nullptr) g_boxed_free(self->type, self->data);
  if (self->owner != nullptr) g_object_unref(self->owner);
  zend_object_std_dtor(o);
}

// clone_obj handler: boxed values are copyable - `clone $rgba` is an independent g_boxed_copy().
zend_object *clone_obj(zend_object *o) {
  Boxed *self = boxed_from_zend(o);
  zend_object *copy = create_object(o->ce);
  Boxed *other = boxed_from_zend(copy);
  other->type = self->type;
  other->data = self->data != nullptr ? g_boxed_copy(self->type, self->data) : nullptr;
  other->owner = self->owner;
  if (other->owner != nullptr) g_object_ref(other->owner);
  zend_objects_clone_members(copy, o);
  return copy;
}

// read_property handler: struct fields via the class's reader, else standard properties.
zval *read_property(zend_object *o, zend_string *member, int type, void **cache_slot, zval *rv) {
  Boxed *self = boxed_from_zend(o);
  const BoxedClass *info = info_of(self);
  if (info != nullptr && self->data != nullptr && info->read(self->data, ZSTR_VAL(member), rv)) {
    return rv;
  }
  return zend_std_read_property(o, member, type, cache_slot, rv);
}

// write_property handler: struct fields via the class's writer, else standard properties.
zval *write_property(zend_object *o, zend_string *member, zval *value, void **cache_slot) {
  Boxed *self = boxed_from_zend(o);
  const BoxedClass *info = info_of(self);
  if (info != nullptr && self->data != nullptr &&
      info->write(self->data, ZSTR_VAL(member), value)) {
    return value;
  }
  return zend_std_write_property(o, member, value, cache_slot);
}

// has_property handler: isset()/empty()/property_exists() on struct fields.
int has_property(zend_object *o, zend_string *member, int has_set_exists, void **cache_slot) {
  Boxed *self = boxed_from_zend(o);
  const BoxedClass *info = info_of(self);
  zval rv;
  if (info != nullptr && self->data != nullptr && info->read(self->data, ZSTR_VAL(member), &rv)) {
    int result = 1;
    if (has_set_exists == ZEND_PROPERTY_NOT_EMPTY) result = zend_is_true(&rv);
    if (has_set_exists == ZEND_PROPERTY_ISSET) result = !Z_ISNULL(rv);
    zval_ptr_dtor(&rv);
    return result;
  }
  return zend_std_has_property(o, member, has_set_exists, cache_slot);
}

// True when `member` names a struct field of this handle's boxed class.
bool is_field(const Boxed *self, const zend_string *member) {
  const BoxedClass *info = info_of(self);
  if (info == nullptr) return false;
  for (const char *const *f = info->fields; *f != nullptr; f++) {
    if (strcmp(*f, ZSTR_VAL(member)) == 0) return true;
  }
  return false;
}

// get_property_ptr_ptr handler: fields have no zval slot, so compound assignments (`+=`, `++`)
// and by-reference access are routed through read_property/write_property.
zval *get_property_ptr_ptr(zend_object *o, zend_string *member, int type, void **cache_slot) {
  if (is_field(boxed_from_zend(o), member)) return nullptr;
  return zend_std_get_property_ptr_ptr(o, member, type, cache_slot);
}

// unset_property handler: struct fields cannot be unset.
void unset_property(zend_object *o, zend_string *member, void **cache_slot) {
  if (is_field(boxed_from_zend(o), member)) {
    zend_throw_error(nullptr, "Cannot unset boxed field %s::$%s", ZSTR_VAL(o->ce->name),
                     ZSTR_VAL(member));
    return;
  }
  zend_std_unset_property(o, member, cache_slot);
}

// get_debug_info handler: var_dump()/print_r() show every field.
HashTable *get_debug_info(zend_object *o, int *is_temp) {
  Boxed *self = boxed_from_zend(o);
  const BoxedClass *info = info_of(self);
  HashTable *ht = zend_new_array(4);
  *is_temp = 1;
  if (info == nullptr || self->data == nullptr) return ht;
  for (const char *const *f = info->fields; *f != nullptr; f++) {
    zval z;
    if (info->read(self->data, *f, &z)) zend_hash_str_update(ht, *f, strlen(*f), &z);
  }
  return ht;
}

// compare handler: same type and equal fields -> 0 (so == and <=> work by value).
int compare_objects(zval *a, zval *b) {
  ZEND_COMPARE_OBJECTS_FALLBACK(a, b);
  Boxed *x = boxed_from_zval(a);
  Boxed *y = boxed_from_zval(b);
  if (x->type != y->type) return 1;
  // Compare through the debug view: field by field.
  int ta = 0;
  int tb = 0;
  HashTable *ha = get_debug_info(Z_OBJ_P(a), &ta);
  HashTable *hb = get_debug_info(Z_OBJ_P(b), &tb);
  zval za;
  zval zb;
  ZVAL_ARR(&za, ha);
  ZVAL_ARR(&zb, hb);
  const int result = zend_compare(&za, &zb);
  zval_ptr_dtor(&za);
  zval_ptr_dtor(&zb);
  return result;
}

}  // namespace

// MINIT: build the shared handler table for all boxed classes.
void boxed_handlers_init() {
  memcpy(&handlers, &std_object_handlers, sizeof(zend_object_handlers));
  handlers.offset = XtOffsetOf(Boxed, std);
  handlers.free_obj = free_obj;
  handlers.get_constructor = get_constructor;
  handlers.clone_obj = clone_obj;
  handlers.read_property = read_property;
  handlers.write_property = write_property;
  handlers.has_property = has_property;
  handlers.get_property_ptr_ptr = get_property_ptr_ptr;
  handlers.unset_property = unset_property;
  handlers.get_debug_info = get_debug_info;
  handlers.compare = compare_objects;
}

// MINIT: bind a PHP class to a boxed GType with its field accessors.
void register_boxed(const BoxedClass &info) {
  info.ce->create_object = create_object;
  registry()[info.type] = info;
}

// Registry lookup by GType.
const BoxedClass *boxed_class_for_type(GType type) {
  auto it = registry().find(type);
  return it == registry().end() ? nullptr : &it->second;
}

// Constructor helper: give a fresh handle its (already allocated) data.
void boxed_adopt(Boxed *self, GType type, gpointer data) {
  if (self->data != nullptr) g_boxed_free(self->type, self->data);
  if (self->owner != nullptr) g_object_unref(self->owner);
  self->type = type;
  self->data = data;
  const BoxedClass *info = boxed_class_for_type(type);
  self->owner = info != nullptr && info->owner != nullptr ? info->owner(data) : nullptr;
  if (self->owner != nullptr) g_object_ref(self->owner);
}

// C -> PHP: new handle holding a copy of `data`; null for nullptr, TypeError for unregistered
// types.
void wrap_boxed(GType type, gconstpointer data, zval *rv) {
  if (data == nullptr) {
    ZVAL_NULL(rv);
    return;
  }
  const BoxedClass *info = boxed_class_for_type(type);
  if (info == nullptr) {
    ZVAL_NULL(rv);
    zend_type_error("to_php: unsupported boxed type %s", g_type_name(type));
    return;
  }
  object_init_ex(rv, info->ce);
  Boxed *self = boxed_from_zval(rv);
  self->type = type;
  self->data = g_boxed_copy(type, data);
  self->owner = info->owner != nullptr ? info->owner(self->data) : nullptr;
  if (self->owner != nullptr) g_object_ref(self->owner);
}

// PHP -> C: borrowed data pointer of a handle of exactly `expected`, else TypeError + nullptr.
gpointer unwrap_boxed(zval *zv, GType expected) {
  const BoxedClass *info = boxed_class_for_type(expected);
  if (info == nullptr || Z_TYPE_P(zv) != IS_OBJECT || Z_OBJCE_P(zv) != info->ce) {
    zend_type_error("expected %s, %s given", g_type_name(expected), zend_zval_value_name(zv));
    return nullptr;
  }
  return boxed_from_zval(zv)->data;
}

// $this of a boxed method: the data, or an Error when the handle never received any.
gpointer boxed_self(zend_execute_data *execute_data, const char *method) {
  Boxed *self = boxed_from_zval(ZEND_THIS);
  if (self->data == nullptr) {
    zend_throw_error(nullptr, "%s() on an uninitialized %s", method, ZSTR_VAL(self->std.ce->name));
    return nullptr;
  }
  return self->data;
}

}  // namespace phpgtk
