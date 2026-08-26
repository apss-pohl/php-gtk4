// Gtk4\GListStore (the GListModel interface methods come from Gio/GListModel.cpp)
#include "php_gtk4.h"
#include "core/object.h"

using namespace phpgtk;

// Resolve "Gtk4\PhpValue" / "PhpValue" / any registered class name to a GObject GType.
static GType item_type_from_string(zend_string *name) {
  const char *s = ZSTR_VAL(name);
  const char *slash = strrchr(s, '\\');
  const char *gtype_name = slash != nullptr ? slash + 1 : s;
  zend_class_entry *ce = class_for_gtype_name(gtype_name);
  GType t = ce != nullptr ? gtype_for_class(ce) : 0;
  if (t == 0 || g_type_is_a(t, G_TYPE_OBJECT) == FALSE) {
    zend_argument_value_error(1, "must name a registered GObject class, \"%s\" given", s);
    return 0;
  }
  return t;
}

/**
 * Gtk4\GListStore::__construct(string $item_type = GObject::class)
 */
ZEND_METHOD(Gtk4_GListStore, __construct) {
  zend_string *type = nullptr;
  ZEND_PARSE_PARAMETERS_START(0, 1)
  Z_PARAM_OPTIONAL
  Z_PARAM_STR(type)
  ZEND_PARSE_PARAMETERS_END();
  GType t = G_TYPE_OBJECT;
  if (type != nullptr) {
    t = item_type_from_string(type);
    if (t == 0) RETURN_THROWS();
  }
  attach_new(object_from_zval(ZEND_THIS), G_OBJECT(g_list_store_new(t)));
}

// Validate an item against the store's item type; nullptr + TypeError if it does not fit.
static GObject *item_for(GListStore *store, zval *item) {
  GType t = g_list_model_get_item_type(G_LIST_MODEL(store));
  return unwrap(item, t);
}

/**
 * Gtk4\GListStore::append(GObject $item): void
 */
ZEND_METHOD(Gtk4_GListStore, append) {
  zval *item;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_OBJECT_OF_CLASS(item, class_for_gtype_name("GObject"))
  ZEND_PARSE_PARAMETERS_END();
  GListStore *store = PHPGTK_SELF(GListStore, G_TYPE_LIST_STORE);
  GObject *o = item_for(store, item);
  if (o == nullptr) RETURN_THROWS();
  g_list_store_append(store, o);
}

/**
 * Gtk4\GListStore::insert(int $position, GObject $item): void
 */
ZEND_METHOD(Gtk4_GListStore, insert) {
  zend_long position;
  zval *item;
  ZEND_PARSE_PARAMETERS_START(2, 2)
  Z_PARAM_LONG(position)
  Z_PARAM_OBJECT_OF_CLASS(item, class_for_gtype_name("GObject"))
  ZEND_PARSE_PARAMETERS_END();
  GListStore *store = PHPGTK_SELF(GListStore, G_TYPE_LIST_STORE);
  const guint n = g_list_model_get_n_items(G_LIST_MODEL(store));
  if (position < 0 || position > n) {
    zend_argument_value_error(1, "must be between 0 and %u", n);
    RETURN_THROWS();
  }
  GObject *o = item_for(store, item);
  if (o == nullptr) RETURN_THROWS();
  g_list_store_insert(store, static_cast<guint>(position), o);
}

/**
 * Gtk4\GListStore::remove(int $position): void
 */
ZEND_METHOD(Gtk4_GListStore, remove) {
  zend_long position;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_LONG(position)
  ZEND_PARSE_PARAMETERS_END();
  GListStore *store = PHPGTK_SELF(GListStore, G_TYPE_LIST_STORE);
  const guint n = g_list_model_get_n_items(G_LIST_MODEL(store));
  if (position < 0 || position >= n) {
    zend_argument_value_error(1, "must be between 0 and %u", n > 0 ? n - 1 : 0);
    RETURN_THROWS();
  }
  g_list_store_remove(store, static_cast<guint>(position));
}

/**
 * Gtk4\GListStore::remove_all(): void
 */
ZEND_METHOD(Gtk4_GListStore, remove_all) {
  ZEND_PARSE_PARAMETERS_NONE();
  GListStore *store = PHPGTK_SELF(GListStore, G_TYPE_LIST_STORE);
  g_list_store_remove_all(store);
}

/**
 * Gtk4\GListStore::find(GObject $item): ?int
 *
 * Position of $item, or null if it is not in the store.
 */
ZEND_METHOD(Gtk4_GListStore, find) {
  zval *item;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_OBJECT_OF_CLASS(item, class_for_gtype_name("GObject"))
  ZEND_PARSE_PARAMETERS_END();
  GListStore *store = PHPGTK_SELF(GListStore, G_TYPE_LIST_STORE);
  GObject *o = unwrap(item, G_TYPE_OBJECT);
  if (o == nullptr) RETURN_THROWS();
  guint position = 0;
  if (!g_list_store_find(store, o, &position)) RETURN_NULL();
  RETURN_LONG(position);
}
