/**
 * public function download_bytes(): array
 * The texture's pixels in the downloader's format (set_format(); GdkMemoryFormat::B8g8r8a8Premultiplied
 * by default) as `[string $bytes, int $stride]` - the row stride is what GdkPixbuf::new_from_bytes()
 * or GdkMemoryTexture::new() need next to the bytes.
 *
 * @return array{string, int}
 */
ZEND_METHOD(Gtk4_GdkTextureDownloader, download_bytes) {
  ZEND_PARSE_PARAMETERS_NONE();
  GdkTextureDownloader *self = PHPGTK_BOXED_SELF(GdkTextureDownloader);
  gsize stride = 0;
  GBytes *bytes = gdk_texture_downloader_download_bytes(self, &stride);
  gsize length = 0;
  const auto *data = static_cast<const char *>(g_bytes_get_data(bytes, &length));
  array_init_size(return_value, 2);
  add_next_index_stringl(return_value, data == nullptr ? "" : data, length);
  add_next_index_long(return_value, static_cast<zend_long>(stride));
  g_bytes_unref(bytes);
}
