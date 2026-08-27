// Gtk4\GtkSorter, Gtk4\GtkCustomSorter (GCompareDataFunc, notified scope)
// and Gtk4\GtkSortListModel.
#include "php_gtk4.h"
#include "core/callback.h"
#include "Gio/listmodel.h"
#include "core/enums.h"
#include "core/object.h"
#include "core/teardown.h"

using namespace phpgtk;

namespace {

// GCompareDataFunc trampoline: (GObject $a, GObject $b) -> int. A failed call compares equal.
gint compare_func(gconstpointer a, gconstpointer b, gpointer data) {
  auto *cb = static_cast<Callback *>(data);
  zval args[2];
  // NOLINTNEXTLINE(cppcoreguidelines-pro-type-const-cast) GLib's gconstpointer, items are GObjects
  wrap(G_OBJECT(const_cast<gpointer>(a)), &args[0]);
  // NOLINTNEXTLINE(cppcoreguidelines-pro-type-const-cast)
  wrap(G_OBJECT(const_cast<gpointer>(b)), &args[1]);
  zval retval;
  const bool ok = callback_invoke(cb, 2, args, &retval);
  gint result = 0;
  if (ok && !Z_ISUNDEF(retval)) {
    const zend_long v = zval_get_long(&retval);
    if (v < 0) result = -1;
    if (v > 0) result = 1;
  }
  zval_ptr_dtor(&retval);
  zval_ptr_dtor(&args[0]);
  zval_ptr_dtor(&args[1]);
  return result;
}

// GDestroyNotify for the compare func.
void compare_func_free(gpointer data) {
  teardown_untrack_notified(data);
  callback_free(data);
}

// teardown clear hook: drop the PHP callable from the sorter.
void compare_func_clear(GObject *owner) {
  gtk_custom_sorter_set_sort_func(GTK_CUSTOM_SORTER(owner), nullptr, nullptr, nullptr);
}

// Install (or with null, remove) a PHP compare func on `sorter`.
void install_compare_func(GtkCustomSorter *sorter, zend_fcall_info *fci, const char *origin) {
  if (!ZEND_FCI_INITIALIZED(*fci)) {
    gtk_custom_sorter_set_sort_func(sorter, nullptr, nullptr, nullptr);
  } else {
    Callback *cb = callback_new(&fci->function_name, origin);
    teardown_track_notified(cb, G_OBJECT(sorter), compare_func_clear);
    gtk_custom_sorter_set_sort_func(sorter, compare_func, cb, compare_func_free);
  }
  callback_drain();  // the previous func's notify ran inside the setter
}

}  // namespace

/**
 * Gtk4\GtkSorter::changed(GtkSorterChange $change = GtkSorterChange::Different): void
 */
ZEND_METHOD(Gtk4_GtkSorter, changed) {
  zval *change = nullptr;
  ZEND_PARSE_PARAMETERS_START(0, 1)
  Z_PARAM_OPTIONAL
  Z_PARAM_OBJECT_OF_CLASS(change, enum_class_for_type(GTK_TYPE_SORTER_CHANGE))
  ZEND_PARSE_PARAMETERS_END();
  GtkSorter *sorter = PHPGTK_SELF(GtkSorter, GTK_TYPE_SORTER);
  gint value = GTK_SORTER_CHANGE_DIFFERENT;
  if (change != nullptr && !enum_from_php(change, GTK_TYPE_SORTER_CHANGE, &value)) RETURN_THROWS();
  gtk_sorter_changed(sorter, static_cast<GtkSorterChange>(value));
}

/**
 * Gtk4\GtkCustomSorter::__construct(?callable $compare = null)
 */
ZEND_METHOD(Gtk4_GtkCustomSorter, __construct) {
  zend_fcall_info fci = {};
  zend_fcall_info_cache fcc;
  ZEND_PARSE_PARAMETERS_START(0, 1)
  Z_PARAM_OPTIONAL
  Z_PARAM_FUNC_OR_NULL(fci, fcc)
  ZEND_PARSE_PARAMETERS_END();
  GtkCustomSorter *sorter = gtk_custom_sorter_new(nullptr, nullptr, nullptr);
  attach_new(object_from_zval(ZEND_THIS), G_OBJECT(sorter));
  install_compare_func(sorter, &fci, "GtkCustomSorter::__construct");
}

/**
 * Gtk4\GtkCustomSorter::set_sort_func(?callable $compare): void
 *
 * Replace the callback (null = keep original order) and notify users.
 */
ZEND_METHOD(Gtk4_GtkCustomSorter, set_sort_func) {
  zend_fcall_info fci;
  zend_fcall_info_cache fcc;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_FUNC_OR_NULL(fci, fcc)
  ZEND_PARSE_PARAMETERS_END();
  GtkCustomSorter *sorter = PHPGTK_SELF(GtkCustomSorter, GTK_TYPE_CUSTOM_SORTER);
  install_compare_func(sorter, &fci, "GtkCustomSorter::set_sort_func");
}

/**
 * Gtk4\GtkSortListModel::__construct(?GListModel $model = null, ?GtkSorter $sorter = null)
 */
ZEND_METHOD(Gtk4_GtkSortListModel, __construct) {
  zval *model = nullptr;
  zval *sorter = nullptr;
  ZEND_PARSE_PARAMETERS_START(0, 2)
  Z_PARAM_OPTIONAL
  Z_PARAM_OBJECT_OF_CLASS_OR_NULL(model, phpgtk::ce_GListModel)
  Z_PARAM_OBJECT_OF_CLASS_OR_NULL(sorter, class_for_gtype(GTK_TYPE_SORTER))
  ZEND_PARSE_PARAMETERS_END();
  GObject *m = model != nullptr ? unwrap(model, G_TYPE_LIST_MODEL) : nullptr;
  if (model != nullptr && m == nullptr) RETURN_THROWS();
  GObject *s = sorter != nullptr ? unwrap(sorter, GTK_TYPE_SORTER) : nullptr;
  if (sorter != nullptr && s == nullptr) RETURN_THROWS();
  // gtk_sort_list_model_new() takes ownership of both - hand it references.
  if (m != nullptr) g_object_ref(m);
  if (s != nullptr) g_object_ref(s);
  GtkSortListModel *slm =
      gtk_sort_list_model_new(G_LIST_MODEL(m), s != nullptr ? GTK_SORTER(s) : nullptr);
  attach_new(object_from_zval(ZEND_THIS), G_OBJECT(slm));
}

/**
 * Gtk4\GtkSortListModel::set_sorter(?GtkSorter $sorter): void
 */
ZEND_METHOD(Gtk4_GtkSortListModel, set_sorter) {
  zval *sorter;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_OBJECT_OF_CLASS_OR_NULL(sorter, class_for_gtype(GTK_TYPE_SORTER))
  ZEND_PARSE_PARAMETERS_END();
  GtkSortListModel *slm = PHPGTK_SELF(GtkSortListModel, GTK_TYPE_SORT_LIST_MODEL);
  GObject *s = sorter != nullptr ? unwrap(sorter, GTK_TYPE_SORTER) : nullptr;
  if (sorter != nullptr && s == nullptr) RETURN_THROWS();
  gtk_sort_list_model_set_sorter(slm, s != nullptr ? GTK_SORTER(s) : nullptr);
}

/**
 * Gtk4\GtkSortListModel::get_sorter(): ?GtkSorter
 */
ZEND_METHOD(Gtk4_GtkSortListModel, get_sorter) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkSortListModel *slm = PHPGTK_SELF(GtkSortListModel, GTK_TYPE_SORT_LIST_MODEL);
  wrap(G_OBJECT(gtk_sort_list_model_get_sorter(slm)), return_value);
}

/**
 * Gtk4\GtkSortListModel::set_model(?GListModel $model): void
 */
ZEND_METHOD(Gtk4_GtkSortListModel, set_model) {
  zval *model;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_OBJECT_OF_CLASS_OR_NULL(model, phpgtk::ce_GListModel)
  ZEND_PARSE_PARAMETERS_END();
  GtkSortListModel *slm = PHPGTK_SELF(GtkSortListModel, GTK_TYPE_SORT_LIST_MODEL);
  GObject *m = model != nullptr ? unwrap(model, G_TYPE_LIST_MODEL) : nullptr;
  if (model != nullptr && m == nullptr) RETURN_THROWS();
  gtk_sort_list_model_set_model(slm, m != nullptr ? G_LIST_MODEL(m) : nullptr);
}

/**
 * Gtk4\GtkSortListModel::get_model(): ?GListModel
 */
ZEND_METHOD(Gtk4_GtkSortListModel, get_model) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkSortListModel *slm = PHPGTK_SELF(GtkSortListModel, GTK_TYPE_SORT_LIST_MODEL);
  wrap(G_OBJECT(gtk_sort_list_model_get_model(slm)), return_value);
}
