// Gtk4\GListModel: one implementation of the interface methods for every implementing
// class. The stub aliases each class's get_item_type/get_n_items/get_item here
// (`@implementation-alias Gtk4\GListModel::x`), so a new GListModel needs no C++ for them.
#include "php_gtk4.h"
#include "core/object.h"
#include "GListModel.h"

using namespace phpgtk;

namespace phpgtk {
zend_class_entry *ce_GListModel = nullptr;
}  // namespace phpgtk

/**
 * Gtk4\GListModel::get_item_type(): string
 *
 * GType name of the items, e.g. "PhpValue" or "GObject".
 */
ZEND_METHOD(Gtk4_GListModel, get_item_type) {
  ZEND_PARSE_PARAMETERS_NONE();
  GListModel *m = PHPGTK_SELF(GListModel, G_TYPE_LIST_MODEL);
  RETURN_STRING(g_type_name(g_list_model_get_item_type(m)));
}

/**
 * Gtk4\GListModel::get_n_items(): int
 */
ZEND_METHOD(Gtk4_GListModel, get_n_items) {
  ZEND_PARSE_PARAMETERS_NONE();
  GListModel *m = PHPGTK_SELF(GListModel, G_TYPE_LIST_MODEL);
  RETURN_LONG(g_list_model_get_n_items(m));
}

/**
 * Gtk4\GListModel::get_item(int $position): ?GObject
 *
 * The item at $position, or null past the end.
 */
ZEND_METHOD(Gtk4_GListModel, get_item) {
  zend_long position;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_LONG(position)
  ZEND_PARSE_PARAMETERS_END();
  GListModel *m = PHPGTK_SELF(GListModel, G_TYPE_LIST_MODEL);
  if (position < 0) RETURN_NULL();
  gpointer item = g_list_model_get_item(m, static_cast<guint>(position));  // transfer full
  wrap(static_cast<GObject *>(item), return_value);
  if (item != nullptr) g_object_unref(item);
  // Filter/sort models run PHP callbacks while producing the item (Rethrow mode).
  if (EG(exception) != nullptr) RETURN_THROWS();
}
