/**
 * public static function deserialize(string $bytes): GskRenderNode
 * The node tree serialize() wrote, read back; text that is not one is a ValueError.
 *
 * GIR takes an error callback next to the bytes; the first error it reports (line, column,
 * message) is what the ValueError says, so nothing has to be wired up to find out why a
 * `.node` file did not load.
 */
ZEND_METHOD(Gtk4_GskRenderNode, deserialize) {
  zend_string *text;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_STR(text)
  ZEND_PARSE_PARAMETERS_END();
  GBytes *bytes = g_bytes_new(ZSTR_VAL(text), ZSTR_LEN(text));
  std::string first;
  GskRenderNode *node = gsk_render_node_deserialize(bytes, parse_error, &first);
  g_bytes_unref(bytes);
  // GSK's parser is lenient - it reports what it could not read and answers with the rest -
  // but a node file that provoked an error is not the file that was written, so it is refused.
  if (node == nullptr || !first.empty()) {
    if (node != nullptr) gsk_render_node_unref(node);
    zend_argument_value_error(1, "is not a serialized render node (%s)",
                              first.empty() ? "empty" : first.c_str());
    RETURN_THROWS();
  }
  wrap_fundamental(GSK_TYPE_RENDER_NODE, node, return_value);
  gsk_render_node_unref(node);
}
