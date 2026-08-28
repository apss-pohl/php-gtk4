// GCompareDataFunc (notified scope): the PHP callable lives in a Callback owned by GTK.
#include "core/callback.h"
#include "core/teardown.h"

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
