// The four callbacks a GtkListBox takes (all GIR scope "notified"): the PHP callable lives in a
// Callback owned by GTK, and the box can be told to drop each of them again - which is what the
// teardown clear hooks do, so nothing is finalized after Zend is gone.
#include "core/callback.h"
#include "core/teardown.h"
#include "core/error.h"

#include <array>

namespace {

// GtkListBoxFilterFunc trampoline: (GtkListBoxRow $row) -> bool. A failed call hides the row.
gboolean list_box_filter_func(GtkListBoxRow *row, gpointer data) {
  auto *cb = static_cast<Callback *>(data);
  zval arg;
  wrap(G_OBJECT(row), &arg);
  zval retval;
  const bool ok = callback_invoke(cb, 1, &arg, &retval);
  const gboolean keep = ok && !Z_ISUNDEF(retval) && zend_is_true(&retval) ? TRUE : FALSE;
  zval_ptr_dtor(&retval);
  zval_ptr_dtor(&arg);
  return keep;
}

// GDestroyNotify for the filter func.
void list_box_filter_func_free(gpointer data) {
  teardown_untrack_notified(data);
  callback_free(data);
}

// teardown clear hook: drop the PHP callable from the box.
void list_box_filter_func_clear(GObject *owner) {
  gtk_list_box_set_filter_func(GTK_LIST_BOX(owner), nullptr, nullptr, nullptr);
}

// GtkListBoxSortFunc trampoline: (GtkListBoxRow $row1, GtkListBoxRow $row2) -> int, the usual
// negative/zero/positive. A failed call leaves the two rows in the order they were in.
int list_box_sort_func(GtkListBoxRow *row1, GtkListBoxRow *row2, gpointer data) {
  auto *cb = static_cast<Callback *>(data);
  std::array<zval, 2> args{};
  wrap(G_OBJECT(row1), args.data());
  wrap(G_OBJECT(row2), &args[1]);
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
void list_box_sort_func_free(gpointer data) {
  teardown_untrack_notified(data);
  callback_free(data);
}

// teardown clear hook: drop the PHP callable from the box.
void list_box_sort_func_clear(GObject *owner) {
  gtk_list_box_set_sort_func(GTK_LIST_BOX(owner), nullptr, nullptr, nullptr);
}

// GtkListBoxUpdateHeaderFunc trampoline: (GtkListBoxRow $row, ?GtkListBoxRow $before) -> void.
// PHP decides by calling GtkListBoxRow::set_header() on $row, which is what GTK asks for here.
void list_box_header_func(GtkListBoxRow *row, GtkListBoxRow *before, gpointer data) {
  auto *cb = static_cast<Callback *>(data);
  std::array<zval, 2> args{};
  wrap(G_OBJECT(row), args.data());
  wrap(before != nullptr ? G_OBJECT(before) : nullptr, &args[1]);
  zval retval;
  callback_invoke(cb, 2, args.data(), &retval);
  zval_ptr_dtor(&retval);
  zval_ptr_dtor(args.data());
  zval_ptr_dtor(&args[1]);
}

// GDestroyNotify for the header func.
void list_box_header_func_free(gpointer data) {
  teardown_untrack_notified(data);
  callback_free(data);
}

// teardown clear hook: drop the PHP callable from the box.
void list_box_header_func_clear(GObject *owner) {
  gtk_list_box_set_header_func(GTK_LIST_BOX(owner), nullptr, nullptr, nullptr);
}

// GtkListBoxCreateWidgetFunc trampoline: (GObject $item) -> GtkWidget, one row per model item.
// The widget is transfer-full, so GTK is handed a reference of its own; anything else - a
// throw, a return that is not a widget - is a TypeError through the boundary and an empty row,
// because GTK dereferences a NULL return.
GtkWidget *list_box_create_widget_func(gpointer item, gpointer data) {
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
    widget = gtk_label_new(nullptr);  // GTK would dereference a NULL row
  }
  zval_ptr_dtor(&retval);
  zval_ptr_dtor(&arg);
  return widget;
}

// GDestroyNotify for the create-widget func.
void list_box_create_widget_func_free(gpointer data) {
  teardown_untrack_notified(data);
  callback_free(data);
}

// teardown clear hook: unbind the model, which drops the PHP callable with it.
void list_box_create_widget_func_clear(GObject *owner) {
  gtk_list_box_bind_model(GTK_LIST_BOX(owner), nullptr, nullptr, nullptr, nullptr);
}

}  // namespace
