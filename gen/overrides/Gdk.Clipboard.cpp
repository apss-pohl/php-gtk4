// The typed payload half of the clipboard: GIR's GValue and GType parameters, as an ordinary PHP
// value and a type name (core/marshal, gtype_from_php_name).
#include "core/marshal.h"

namespace {

// AsyncReadyCallback trampoline for GdkClipboard::read_async(): wraps the C arguments, invokes the
// PHP callable once and releases it (async scope). It lives here rather than beside the method
// because read_async() is an override (Gdk.Clipboard.read_async.cpp) and the generator emits a
// trampoline only for the methods it writes itself.
void cb_read_async_callback(GObject *source_object, GAsyncResult *res, gpointer data) {
  auto *cb = static_cast<Callback *>(data);
  std::array<zval, 2> args{};
  zval *argv = args.data();
  wrap(source_object != nullptr ? G_OBJECT(source_object) : nullptr, &argv[0]);
  wrap(res != nullptr ? G_OBJECT(res) : nullptr, &argv[1]);
  zval ret;
  callback_invoke(cb, 2, argv, &ret);
  if (!Z_ISUNDEF(ret)) zval_ptr_dtor(&ret);
  for (zval &arg : args) zval_ptr_dtor(&arg);
  callback_free(cb);
  callback_drain();
}

}  // namespace
