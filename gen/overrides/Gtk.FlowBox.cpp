// The three callbacks a GtkFlowBox takes (all GIR scope "notified"), the same shape as
// GtkListBox's: the PHP callable lives in a Callback owned by GTK, and the box can be told to
// drop it again - which is what the teardown clear hooks do.
#include "core/callback.h"
#include "core/teardown.h"
#include "core/error.h"

#include <array>

namespace {

// GtkFlowBoxFilterFunc trampoline: (GtkFlowBoxChild $child) -> bool. A failed call hides it.
gboolean flow_box_filter_func(GtkFlowBoxChild *child, gpointer data) {
  auto *cb = static_cast<Callback *>(data);
  zval arg;
  wrap(G_OBJECT(child), &arg);
  zval retval;
  const bool ok = callback_invoke(cb, 1, &arg, &retval);
  const gboolean keep = ok && !Z_ISUNDEF(retval) && zend_is_true(&retval) ? TRUE : FALSE;
  zval_ptr_dtor(&retval);
  zval_ptr_dtor(&arg);
  return keep;
}

// GDestroyNotify for the filter func.
void flow_box_filter_func_free(gpointer data) {
  teardown_untrack_notified(data);
  callback_free(data);
}

// teardown clear hook: drop the PHP callable from the box.
void flow_box_filter_func_clear(GObject *owner) {
  gtk_flow_box_set_filter_func(GTK_FLOW_BOX(owner), nullptr, nullptr, nullptr);
}

// GtkFlowBoxSortFunc trampoline: (GtkFlowBoxChild $child1, GtkFlowBoxChild $child2) -> int, the
// usual negative/zero/positive. A failed call leaves the two where they were.
int flow_box_sort_func(GtkFlowBoxChild *child1, GtkFlowBoxChild *child2, gpointer data) {
  auto *cb = static_cast<Callback *>(data);
  std::array<zval, 2> args{};
  wrap(G_OBJECT(child1), args.data());
  wrap(G_OBJECT(child2), &args[1]);
  zval retval;
  const bool ok = callback_invoke(cb, 2, args.data(), &retval);
  const zend_long order = ok && !Z_ISUNDEF(retval) ? zval_get_long(&retval) : 0;
  int sorted = 0;
  if (order < 0) sorted = -1;
  if (order > 0) sorted = 1;
  zval_ptr_dtor(&retval);
  zval_ptr_dtor(args.data());
  zval_ptr_dtor(&args[1]);
  return sorted;
}

// GDestroyNotify for the sort func.
void flow_box_sort_func_free(gpointer data) {
  teardown_untrack_notified(data);
  callback_free(data);
}

// teardown clear hook: drop the PHP callable from the box.
void flow_box_sort_func_clear(GObject *owner) {
  gtk_flow_box_set_sort_func(GTK_FLOW_BOX(owner), nullptr, nullptr, nullptr);
}

// GtkFlowBoxCreateWidgetFunc trampoline: (GObject $item) -> GtkWidget, one child per model item.
// Transfer full, so GTK is handed a reference of its own; a throw or a return that is not a
// widget is reported and answered with an empty label, because GTK dereferences a NULL child.
GtkWidget *flow_box_create_widget_func(gpointer item, gpointer data) {
  auto *cb = static_cast<Callback *>(data);
  zval arg;
  wrap(static_cast<GObject *>(item), &arg);
  zval retval;
  const bool ok = callback_invoke(cb, 1, &arg, &retval);
  GtkWidget *widget = nullptr;
  if (ok && !Z_ISUNDEF(retval) && Z_TYPE(retval) == IS_OBJECT) {
    GObject *obj = unwrap(&retval, GTK_TYPE_WIDGET);
    if (obj != nullptr) widget = GTK_WIDGET(g_object_ref(obj));  // transfer full
  }
  if (widget == nullptr) {
    if (EG(exception) == nullptr && ok) {
      zend_type_error("%s(): the create function must return a GtkWidget", cb->origin);
    }
    report_pending_exception(cb->origin);
    widget = gtk_label_new(nullptr);
  }
  zval_ptr_dtor(&retval);
  zval_ptr_dtor(&arg);
  return widget;
}

// GDestroyNotify for the create-widget func.
void flow_box_create_widget_func_free(gpointer data) {
  teardown_untrack_notified(data);
  callback_free(data);
}

// teardown clear hook: unbind the model, which drops the PHP callable with it.
void flow_box_create_widget_func_clear(GObject *owner) {
  gtk_flow_box_bind_model(GTK_FLOW_BOX(owner), nullptr, nullptr, nullptr, nullptr);
}

}  // namespace
