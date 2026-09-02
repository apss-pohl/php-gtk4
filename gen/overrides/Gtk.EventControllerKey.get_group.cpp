/**
 * public function get_group(): int
 * The keyboard group of the key event being handled.
 *
 * Only meaningful while the controller is handling an event: GTK reads it off
 * `controller->current_event` and CRITICALs when there is none, then answers 0. Every other
 * "current event" getter on GtkEventController is nullable and says so; this one returns an int,
 * so it refuses instead.
 */
ZEND_METHOD(Gtk4_GtkEventControllerKey, get_group) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkEventControllerKey *self = PHPGTK_SELF(GtkEventControllerKey, GTK_TYPE_EVENT_CONTROLLER_KEY);
  if (gtk_event_controller_get_current_event(GTK_EVENT_CONTROLLER(self)) == nullptr) {
    zend_throw_exception(spl_ce_LogicException,
                         "Gtk4\\GtkEventControllerKey::get_group(): only while the controller is "
                         "handling an event",
                         0);
    RETURN_THROWS();
  }
  RETURN_LONG(static_cast<zend_long>(gtk_event_controller_key_get_group(self)));
}
