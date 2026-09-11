/**
 * public function destroy(): void
 * Destroys the window system resources associated with surface and decrements surface's
 * reference count.
 *
 * The reference it decrements is the creator's: on a surface PHP built with
 * {@see new_toplevel()} that is the handle's own, and GDK would finalize the object under the
 * handle (every later use of it, or its release, then touched freed memory). The handle keeps
 * its reference and the surface stays a valid, destroyed object - {@see is_destroyed()} says so -
 * until the handle goes. A GtkWindow's surface is GTK's to destroy, not the script's.
 */
ZEND_METHOD(Gtk4_GdkSurface, destroy) {
  ZEND_PARSE_PARAMETERS_NONE();
  GdkSurface *self = PHPGTK_SELF(GdkSurface, GDK_TYPE_SURFACE);
  g_object_ref(self);  // what gdk_surface_destroy() consumes; the handle's own stays
  gdk_surface_destroy(self);
}
