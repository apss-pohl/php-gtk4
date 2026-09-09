/**
 * public function get_bounding_box_center(): ?array
 * The centre of the box containing every active touch, or null when the gesture is not active.
 *
 * GTK walks the gesture's last event to find it, and with no sequence in flight that event is
 * NULL - `gdk_event_get_event_type()` then CRITICALs before the call answers false. Asking the
 * gesture whether it is active first keeps GTK out of it; the answer is the same null.
 *
 * @return array{float, float}|null
 */
ZEND_METHOD(Gtk4_GtkGesture, get_bounding_box_center) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkGesture *self = PHPGTK_SELF(GtkGesture, GTK_TYPE_GESTURE);
  double x = 0;
  double y = 0;
  if (gtk_gesture_is_active(self) == FALSE
      || gtk_gesture_get_bounding_box_center(self, &x, &y) == FALSE) {
    RETURN_NULL();
  }
  array_init_size(return_value, 2);
  add_next_index_double(return_value, x);
  add_next_index_double(return_value, y);
}
