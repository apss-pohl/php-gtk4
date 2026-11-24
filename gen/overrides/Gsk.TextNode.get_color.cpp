/**
 * public function get_color(): GdkRGBA
 * Retrieves the color used by the text $node.
 */
ZEND_METHOD(Gtk4_GskTextNode, get_color) {
  ZEND_PARSE_PARAMETERS_NONE();
  auto *self = static_cast<GskRenderNode *>(fundamental_self(execute_data));
  if (self == nullptr) RETURN_THROWS();
  const GdkRGBA *color = gsk_text_node_get_color(self);
  // GTK 4.16 to 4.21 answer with the address of the node's GdkColor, not of its four floats
  // (fixed in 4.22 by GTK commit 11704c30 "textnode: Fix get_color()"): red and green were the
  // bytes of its color state pointer. Checked on the GTK that runs, not the one built against.
  const guint minor = gtk_get_minor_version();
  if (minor >= 16 && minor < 22) {
    // GTK's private GdkColor (gdk/gdkcolorprivate.h): the color state, then r, g, b, a as floats
    struct GdkColorLayout {
      const void *color_state;
      GdkRGBA rgba;
    };
    color = &reinterpret_cast<const GdkColorLayout *>(color)->rgba;
  }
  wrap_boxed(GDK_TYPE_RGBA, color, return_value);
}
