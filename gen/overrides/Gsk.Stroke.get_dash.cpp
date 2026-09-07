/**
 * public function get_dash(): array
 * The dash pattern set_dash() took; an empty list for a solid line.
 *
 * @return list<float>
 */
ZEND_METHOD(Gtk4_GskStroke, get_dash) {
  ZEND_PARSE_PARAMETERS_NONE();
  GskStroke *self = PHPGTK_BOXED_SELF(GskStroke);
  gsize n = 0;
  const float *dash = gsk_stroke_get_dash(self, &n);
  array_init_size(return_value, static_cast<uint32_t>(n));
  for (gsize i = 0; i < n; i++) {
    // NOLINTNEXTLINE(cppcoreguidelines-pro-bounds-pointer-arithmetic) a C array of n floats
    add_next_index_double(return_value, dash[i]);
  }
}
