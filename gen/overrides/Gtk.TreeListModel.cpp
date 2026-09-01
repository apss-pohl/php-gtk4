// GtkTreeListModelCreateModelFunc (notified scope): the PHP callable that answers with a row's
// children lives in a Callback owned by GTK. Unlike a filter or sorter func, a tree model has no
// setter to take it back, so the Callback is also kept as qdata on the model: request teardown
// looks it up there and parks the callable (the struct itself stays until GTK's notify runs).
#include "core/callback.h"
#include "core/teardown.h"
#include "core/error.h"

namespace {

// Where the model keeps its Callback for the teardown hook below.
const char *const CREATE_FUNC_KEY = "php-gtk4-create-model-func";

// GtkTreeListModelCreateModelFunc trampoline: (GObject $item) -> ?GListModel. Null means "$item
// is a leaf"; anything else that is not a GListModel is a TypeError through the boundary, and
// the row stays a leaf.
GListModel *create_model_func(gpointer item, gpointer data) {
  auto *cb = static_cast<Callback *>(data);
  if (Z_ISUNDEF(cb->callable)) return nullptr;  // disarmed by request teardown
  zval arg;
  wrap(static_cast<GObject *>(item), &arg);
  zval retval;
  const bool ok = callback_invoke(cb, 1, &arg, &retval);
  GListModel *children = nullptr;
  if (ok && !Z_ISUNDEF(retval) && Z_TYPE(retval) != IS_NULL) {
    GObject *obj = Z_TYPE(retval) == IS_OBJECT ? unwrap(&retval, G_TYPE_LIST_MODEL) : nullptr;
    if (obj != nullptr) {
      children = G_LIST_MODEL(g_object_ref(obj));  // transfer full
    } else {
      if (EG(exception) == nullptr) {
        zend_type_error("%s(): the create function must return a GListModel or null", cb->origin);
      }
      report_pending_exception(cb->origin);
    }
  }
  zval_ptr_dtor(&retval);
  zval_ptr_dtor(&arg);
  return children;
}

// GDestroyNotify: GTK dropped the create func (the model was finalized).
void create_model_func_free(gpointer data) {
  teardown_untrack_notified(data);
  callback_free(data);
}

// teardown clear hook: the model keeps its create func to the grave, so release the callable
// while Zend is up and leave an inert Callback behind for GTK's notify.
void create_model_func_clear(GObject *owner) {
  auto *cb = static_cast<Callback *>(g_object_get_data(owner, CREATE_FUNC_KEY));
  if (cb == nullptr || Z_ISUNDEF(cb->callable)) return;
  zval callable = cb->callable;
  ZVAL_UNDEF(&cb->callable);
  callback_park(&callable);
}

}  // namespace
