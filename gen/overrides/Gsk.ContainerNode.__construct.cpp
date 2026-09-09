/**
 * public function __construct(array $children)
 * A node drawing $children (a list of GskRenderNode) one after the other, in order.
 *
 * GIR takes a C array with its length; a PHP list is the same thing. The container references
 * every child, so the list may be dropped afterwards.
 */
ZEND_METHOD(Gtk4_GskContainerNode, __construct) {
  zval *children;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_ARRAY(children)
  ZEND_PARSE_PARAMETERS_END();
  std::vector<GskRenderNode *> nodes;
  zval *item;
  // NOLINTNEXTLINE(readability-math-missing-parentheses) Zend macro expansion
  ZEND_HASH_FOREACH_VAL(Z_ARRVAL_P(children), item) {
    gpointer node = unwrap_fundamental(item, GSK_TYPE_RENDER_NODE);
    if (node == nullptr) {
      if (EG(exception) != nullptr) zend_clear_exception();
      zend_argument_type_error(1, "must be a list of GskRenderNode, %s found in it",
                               zend_zval_value_name(item));
      RETURN_THROWS();
    }
    nodes.push_back(static_cast<GskRenderNode *>(node));
  }
  ZEND_HASH_FOREACH_END();
  // The count is a guint, not a gsize: MSVC's /W3 fails the Windows build on the implicit
  // narrowing (GCC does not warn), so it is spelled out here as everywhere else.
  GskRenderNode *self = gsk_container_node_new(nodes.data(), static_cast<guint>(nodes.size()));
  fundamental_adopt(fundamental_from_zval(ZEND_THIS), GSK_TYPE_CONTAINER_NODE, self);
}
