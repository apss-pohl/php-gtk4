/**
 * public function download(): CairoSurface
 * Download the texture's pixels into a cairo image surface.
 *
 * GIR's signature writes into a caller-allocated byte array, which PHP cannot hand over; GDK
 * documents the format it writes as CAIRO_FORMAT_ARGB32, so the buffer this fills is exactly a
 * cairo image surface. That is what makes an image paintable from a draw func at all - the GTK 4
 * answer to GTK 3's `gdk_cairo_set_source_pixbuf()`, together with
 * {@see CairoContext::set_source_surface()}.
 */
ZEND_METHOD(Gtk4_GdkTexture, download) {
  ZEND_PARSE_PARAMETERS_NONE();
  GdkTexture *self = PHPGTK_SELF(GdkTexture, GDK_TYPE_TEXTURE);
  const int width = gdk_texture_get_width(self);
  const int height = gdk_texture_get_height(self);
  cairo_surface_t *surface = cairo_image_surface_create(CAIRO_FORMAT_ARGB32, width, height);
  const cairo_status_t status = cairo_surface_status(surface);
  if (status != CAIRO_STATUS_SUCCESS) {
    cairo_surface_destroy(surface);
    zend_throw_error(nullptr, "Gtk4\\GdkTexture::download(): %s", cairo_status_to_string(status));
    RETURN_THROWS();
  }
  cairo_surface_flush(surface);
  gdk_texture_download(self, cairo_image_surface_get_data(surface),
                       static_cast<gsize>(cairo_image_surface_get_stride(surface)));
  cairo_surface_mark_dirty(surface);
  wrap_cairo_surface(surface, return_value);
  cairo_surface_destroy(surface);  // the handle took its own reference
}
