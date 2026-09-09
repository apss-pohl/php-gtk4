// GtkScaleFormatValueFunc (notified scope): the PHP callable lives in a Callback owned by GTK.
#include "core/callback.h"
#include "core/teardown.h"

#include <array>

namespace {

// GtkScaleFormatValueFunc trampoline: (GtkScale $scale, float $value) -> string. GTK frees the
// returned string; a failed call (exception reported through the boundary) shows an empty label.
char *format_value_func(GtkScale *scale, double value, gpointer data) {
  auto *cb = static_cast<Callback *>(data);
  std::array<zval, 2> args{};
  wrap(G_OBJECT(scale), args.data());
  ZVAL_DOUBLE(&args[1], value);
  zval retval;
  const bool ok = callback_invoke(cb, 2, args.data(), &retval);
  char *text = nullptr;
  if (ok && !Z_ISUNDEF(retval)) {
    zend_string *s = zval_get_string(&retval);
    text = g_strdup(ZSTR_VAL(s));
    zend_string_release(s);
  }
  zval_ptr_dtor(&retval);
  zval_ptr_dtor(args.data());
  return text != nullptr ? text : g_strdup("");
}

// GDestroyNotify for the format func.
void format_value_func_free(gpointer data) {
  teardown_untrack_notified(data);
  callback_free(data);
}

// teardown clear hook: drop the PHP callable from the scale.
void format_value_func_clear(GObject *owner) {
  gtk_scale_set_format_value_func(GTK_SCALE(owner), nullptr, nullptr, nullptr);
}

// Install (or with null, remove) a PHP format func on `scale`.
void install_format_value_func(GtkScale *scale, zend_fcall_info *fci, const char *origin) {
  if (!ZEND_FCI_INITIALIZED(*fci)) {
    gtk_scale_set_format_value_func(scale, nullptr, nullptr, nullptr);
  } else {
    Callback *cb = callback_new(&fci->function_name, origin);
    teardown_track_notified(cb, G_OBJECT(scale), format_value_func_clear);
    gtk_scale_set_format_value_func(scale, format_value_func, cb, format_value_func_free);
  }
  callback_drain();  // the previous func's notify ran inside the setter
}

}  // namespace
