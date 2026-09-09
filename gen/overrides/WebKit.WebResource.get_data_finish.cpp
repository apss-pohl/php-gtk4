/**
 * public function get_data_finish(GAsyncResult $result): string
 * Finish an asynchronous operation started with get_data(): the resource's data as bytes
 * (a page's HTML, an image's file). Throws the GError the load failed with.
 */
ZEND_METHOD(Gtk4_WebKitWebResource, get_data_finish) {
  zval *result;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_OBJECT_OF_CLASS(result, class_for_gtype(G_TYPE_ASYNC_RESULT))
  ZEND_PARSE_PARAMETERS_END();
  WebKitWebResource *self = PHPGTK_SELF(WebKitWebResource, WEBKIT_TYPE_WEB_RESOURCE);
  GObject *result_o = unwrap(result, G_TYPE_ASYNC_RESULT);
  if (result_o == nullptr) RETURN_THROWS();
  GError *error = nullptr;
  gsize length = 0;
  guchar *data = webkit_web_resource_get_data_finish(self, G_ASYNC_RESULT(result_o), &length, &error);
  if (error != nullptr) {
    g_free(data);
    throw_gerror(error);
    RETURN_THROWS();
  }
  if (data == nullptr) RETURN_EMPTY_STRING();
  RETVAL_STRINGL(reinterpret_cast<const char *>(data), length);
  g_free(data);
}
