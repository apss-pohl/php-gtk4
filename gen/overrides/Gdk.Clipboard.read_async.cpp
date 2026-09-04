/**
 * public function read_async(array $mime_types, int $io_priority, ?GCancellable $cancellable, callable $callback): void
 * Asynchronously requests an input stream to read the $clipboard's contents from.
 *
 * Generated but for the empty-list check: GDK asserts `mime_types[0] != NULL` and never calls
 * the callback, so an empty list - what an unfiltered `array_filter()` leaves behind - is a read
 * that silently never finishes.
 */
ZEND_METHOD(Gtk4_GdkClipboard, read_async) {
  zval *mime_types;
  zend_long io_priority;
  zval *cancellable = nullptr;
  zend_fcall_info fci_callback = empty_fcall_info;
  zend_fcall_info_cache fcc_callback = empty_fcall_info_cache;
  ZEND_PARSE_PARAMETERS_START(4, 4)
  Z_PARAM_ARRAY(mime_types)
  Z_PARAM_LONG(io_priority)
  Z_PARAM_OBJECT_OF_CLASS_OR_NULL(cancellable, class_for_gtype(G_TYPE_CANCELLABLE))
  Z_PARAM_FUNC(fci_callback, fcc_callback)
  ZEND_PARSE_PARAMETERS_END();
  GdkClipboard *self = PHPGTK_SELF(GdkClipboard, GDK_TYPE_CLIPBOARD);
  if (zend_hash_num_elements(Z_ARRVAL_P(mime_types)) == 0) {
    zend_argument_value_error(1, "must name at least one mime type");
    RETURN_THROWS();
  }
  char **mime_types_v = strv_from_php(mime_types);
  if (mime_types_v == nullptr) RETURN_THROWS();
  if (!phpgtk::check_range<int>(io_priority, 2)) RETURN_THROWS();
  GObject *cancellable_o = nullptr;
  if (cancellable != nullptr) {
    cancellable_o = unwrap(cancellable, G_TYPE_CANCELLABLE);
    if (cancellable_o == nullptr) RETURN_THROWS();
  }
  Callback *cb_callback = callback_new(&fci_callback.function_name, "GdkClipboard::read_async");
  gdk_clipboard_read_async(self, const_cast<const char **>(mime_types_v),
                           static_cast<int>(io_priority),
                           cancellable_o != nullptr ? G_CANCELLABLE(cancellable_o) : nullptr,
                           cb_read_async_callback, cb_callback);
  g_strfreev(mime_types_v);
}
