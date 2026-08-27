// Gtk4\GtkFilter, Gtk4\GtkCustomFilter (GtkCustomFilterFunc, notified scope)
// and Gtk4\GtkFilterListModel.
#include "php_gtk4.h"
#include "core/callback.h"
#include "Gio/listmodel.h"
#include "core/enums.h"
#include "core/object.h"
#include "core/teardown.h"

using namespace phpgtk;

namespace {

// GtkCustomFilterFunc trampoline: (GObject $item) -> bool. A failed call hides the item.
gboolean match_func(gpointer item, gpointer data) {
  auto *cb = static_cast<Callback *>(data);
  zval arg;
  wrap(static_cast<GObject *>(item), &arg);
  zval retval;
  const bool ok = callback_invoke(cb, 1, &arg, &retval);
  const gboolean match = ok && !Z_ISUNDEF(retval) && zend_is_true(&retval) ? TRUE : FALSE;
  zval_ptr_dtor(&retval);
  zval_ptr_dtor(&arg);
  return match;
}

// GDestroyNotify for the match func.
void match_func_free(gpointer data) {
  teardown_untrack_notified(data);
  callback_free(data);
}

// teardown clear hook: drop the PHP callable from the filter.
void match_func_clear(GObject *owner) {
  gtk_custom_filter_set_filter_func(GTK_CUSTOM_FILTER(owner), nullptr, nullptr, nullptr);
}

// Install (or with null, remove) a PHP match func on `filter`.
void install_match_func(GtkCustomFilter *filter, zend_fcall_info *fci, const char *origin) {
  if (!ZEND_FCI_INITIALIZED(*fci)) {
    gtk_custom_filter_set_filter_func(filter, nullptr, nullptr, nullptr);
  } else {
    Callback *cb = callback_new(&fci->function_name, origin);
    teardown_track_notified(cb, G_OBJECT(filter), match_func_clear);
    gtk_custom_filter_set_filter_func(filter, match_func, cb, match_func_free);
  }
  callback_drain();  // the previous func's notify ran inside the setter
}

}  // namespace

/**
 * Gtk4\GtkFilter::changed(GtkFilterChange $change = GtkFilterChange::Different): void
 *
 * Tell users of the filter that its decisions changed.
 */
ZEND_METHOD(Gtk4_GtkFilter, changed) {
  zval *change = nullptr;
  ZEND_PARSE_PARAMETERS_START(0, 1)
  Z_PARAM_OPTIONAL
  Z_PARAM_OBJECT_OF_CLASS(change, enum_class_for_type(GTK_TYPE_FILTER_CHANGE))
  ZEND_PARSE_PARAMETERS_END();
  GtkFilter *filter = PHPGTK_SELF(GtkFilter, GTK_TYPE_FILTER);
  gint value = GTK_FILTER_CHANGE_DIFFERENT;
  if (change != nullptr && !enum_from_php(change, GTK_TYPE_FILTER_CHANGE, &value)) RETURN_THROWS();
  gtk_filter_changed(filter, static_cast<GtkFilterChange>(value));
}

/**
 * Gtk4\GtkCustomFilter::__construct(?callable $match_func = null)
 */
ZEND_METHOD(Gtk4_GtkCustomFilter, __construct) {
  zend_fcall_info fci = {};
  zend_fcall_info_cache fcc;
  ZEND_PARSE_PARAMETERS_START(0, 1)
  Z_PARAM_OPTIONAL
  Z_PARAM_FUNC_OR_NULL(fci, fcc)
  ZEND_PARSE_PARAMETERS_END();
  GtkCustomFilter *filter = gtk_custom_filter_new(nullptr, nullptr, nullptr);
  attach_new(object_from_zval(ZEND_THIS), G_OBJECT(filter));
  install_match_func(filter, &fci, "GtkCustomFilter::__construct");
}

/**
 * Gtk4\GtkCustomFilter::set_filter_func(?callable $match_func): void
 *
 * Replace the callback (null = everything matches) and notify users.
 */
ZEND_METHOD(Gtk4_GtkCustomFilter, set_filter_func) {
  zend_fcall_info fci;
  zend_fcall_info_cache fcc;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_FUNC_OR_NULL(fci, fcc)
  ZEND_PARSE_PARAMETERS_END();
  GtkCustomFilter *filter = PHPGTK_SELF(GtkCustomFilter, GTK_TYPE_CUSTOM_FILTER);
  install_match_func(filter, &fci, "GtkCustomFilter::set_filter_func");
}

/**
 * Gtk4\GtkFilterListModel::__construct(?GListModel $model = null, ?GtkFilter $filter = null)
 */
ZEND_METHOD(Gtk4_GtkFilterListModel, __construct) {
  zval *model = nullptr;
  zval *filter = nullptr;
  ZEND_PARSE_PARAMETERS_START(0, 2)
  Z_PARAM_OPTIONAL
  Z_PARAM_OBJECT_OF_CLASS_OR_NULL(model, phpgtk::ce_GListModel)
  Z_PARAM_OBJECT_OF_CLASS_OR_NULL(filter, class_for_gtype(GTK_TYPE_FILTER))
  ZEND_PARSE_PARAMETERS_END();
  GObject *m = model != nullptr ? unwrap(model, G_TYPE_LIST_MODEL) : nullptr;
  if (model != nullptr && m == nullptr) RETURN_THROWS();
  GObject *f = filter != nullptr ? unwrap(filter, GTK_TYPE_FILTER) : nullptr;
  if (filter != nullptr && f == nullptr) RETURN_THROWS();
  // gtk_filter_list_model_new() takes ownership of both - hand it references.
  if (m != nullptr) g_object_ref(m);
  if (f != nullptr) g_object_ref(f);
  GtkFilterListModel *flm =
      gtk_filter_list_model_new(G_LIST_MODEL(m), f != nullptr ? GTK_FILTER(f) : nullptr);
  attach_new(object_from_zval(ZEND_THIS), G_OBJECT(flm));
}

/**
 * Gtk4\GtkFilterListModel::set_filter(?GtkFilter $filter): void
 */
ZEND_METHOD(Gtk4_GtkFilterListModel, set_filter) {
  zval *filter;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_OBJECT_OF_CLASS_OR_NULL(filter, class_for_gtype(GTK_TYPE_FILTER))
  ZEND_PARSE_PARAMETERS_END();
  GtkFilterListModel *flm = PHPGTK_SELF(GtkFilterListModel, GTK_TYPE_FILTER_LIST_MODEL);
  GObject *f = filter != nullptr ? unwrap(filter, GTK_TYPE_FILTER) : nullptr;
  if (filter != nullptr && f == nullptr) RETURN_THROWS();
  gtk_filter_list_model_set_filter(flm, f != nullptr ? GTK_FILTER(f) : nullptr);
}

/**
 * Gtk4\GtkFilterListModel::get_filter(): ?GtkFilter
 */
ZEND_METHOD(Gtk4_GtkFilterListModel, get_filter) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkFilterListModel *flm = PHPGTK_SELF(GtkFilterListModel, GTK_TYPE_FILTER_LIST_MODEL);
  wrap(G_OBJECT(gtk_filter_list_model_get_filter(flm)), return_value);
}

/**
 * Gtk4\GtkFilterListModel::set_model(?GListModel $model): void
 */
ZEND_METHOD(Gtk4_GtkFilterListModel, set_model) {
  zval *model;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_OBJECT_OF_CLASS_OR_NULL(model, phpgtk::ce_GListModel)
  ZEND_PARSE_PARAMETERS_END();
  GtkFilterListModel *flm = PHPGTK_SELF(GtkFilterListModel, GTK_TYPE_FILTER_LIST_MODEL);
  GObject *m = model != nullptr ? unwrap(model, G_TYPE_LIST_MODEL) : nullptr;
  if (model != nullptr && m == nullptr) RETURN_THROWS();
  gtk_filter_list_model_set_model(flm, m != nullptr ? G_LIST_MODEL(m) : nullptr);
}

/**
 * Gtk4\GtkFilterListModel::get_model(): ?GListModel
 */
ZEND_METHOD(Gtk4_GtkFilterListModel, get_model) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkFilterListModel *flm = PHPGTK_SELF(GtkFilterListModel, GTK_TYPE_FILTER_LIST_MODEL);
  wrap(G_OBJECT(gtk_filter_list_model_get_model(flm)), return_value);
}
