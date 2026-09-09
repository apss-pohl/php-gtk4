/**
 * public function register_uri_scheme(string $scheme, callable $handler): void
 * Answer `$scheme://` requests from PHP: `function (WebKitURISchemeRequest $request): void`,
 * which finishes the request with {@see WebKitURISchemeRequest::finish()},
 * {@see WebKitURISchemeRequest::finish_with_response()} or
 * {@see WebKitURISchemeRequest::finish_error()} - the way an application serves its own pages
 * to a web view without a server.
 *
 * A scheme belongs to the context for good: WebKit has no unregister call and refuses a second
 * registration of the same name, which is a `ValueError` here rather than a warning from
 * WebKit. Schemes WebKit reserves (http, https, file, ...) are refused by its network process
 * instead, and a handler that never finishes its request leaves the load hanging.
 */
ZEND_METHOD(Gtk4_WebKitWebContext, register_uri_scheme) {
  zend_string *scheme;
  zend_fcall_info fci;
  zend_fcall_info_cache fcc;
  ZEND_PARSE_PARAMETERS_START(2, 2)
  Z_PARAM_STR(scheme)
  Z_PARAM_FUNC(fci, fcc)
  ZEND_PARSE_PARAMETERS_END();
  WebKitWebContext *self = PHPGTK_SELF(WebKitWebContext, WEBKIT_TYPE_WEB_CONTEXT);
  if (!phpgtk::check_utf8(scheme, 1)) RETURN_THROWS();
  if (!scheme_is_wellformed(scheme)) {
    zend_argument_value_error(
        1, "must be a URI scheme: a letter followed by letters, digits, '+', '-' or '.'");
    RETURN_THROWS();
  }
  if (scheme_is_registered(G_OBJECT(self), ZSTR_VAL(scheme))) {
    zend_argument_value_error(1, "is already registered on this context");
    RETURN_THROWS();
  }
  pin_this_module();  // WebKit calls the destroy notify below from an atexit handler
  auto *handler = g_new0(SchemeHandler, 1);
  handler->cb = callback_new(&fci.function_name, "WebKitWebContext::register_uri_scheme");
  handler->context = G_OBJECT(self);
  handler->scheme = g_strdup(ZSTR_VAL(scheme));
  g_ptr_array_add(scheme_handlers(G_OBJECT(self), true), handler);
  teardown_track_notified(handler, G_OBJECT(self), uri_scheme_request_clear);
  webkit_web_context_register_uri_scheme(self, ZSTR_VAL(scheme), uri_scheme_request, handler,
                                         uri_scheme_request_free);
}
