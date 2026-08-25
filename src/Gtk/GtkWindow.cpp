#include "GtkWindow.h"
#include "../core/params.h"
#include <gtk/gtk.h>

namespace phpgtk {

#define SELF as<GtkWindow>(GTK_TYPE_WINDOW)

void GtkWindow_::__construct() {
  attach(G_OBJECT(gtk_window_new()));
}

void GtkWindow_::set_title(Php::Parameters &p) {
  require(p, 1, "set_title(string $title)");
  gtk_window_set_title(SELF, arg_string(p, 0).c_str());
}

Php::Value GtkWindow_::get_title() {
  const char *t = gtk_window_get_title(SELF);
  return t ? Php::Value(t) : Php::Value(nullptr);
}

void GtkWindow_::set_default_size(Php::Parameters &p) {
  require(p, 2, "set_default_size(int $width, int $height)");
  gtk_window_set_default_size(SELF, (int)arg_int(p, 0), (int)arg_int(p, 1));
}

void GtkWindow_::set_child(Php::Parameters &p) {
  require(p, 1, "set_child(?GtkWidget $child)");
  GObject *child = p[0].isNull() ? nullptr : unwrap(p[0], GTK_TYPE_WIDGET);
  gtk_window_set_child(SELF, child ? GTK_WIDGET(child) : nullptr);
}

void GtkWindow_::present() {
  gtk_window_present(SELF);
}
void GtkWindow_::close() {
  gtk_window_close(SELF);
}
void GtkWindow_::destroy() {
  gtk_window_destroy(SELF);
}

void register_GtkWindow(Php::Extension &ext, const Php::Class<GObjectWrapper> &gobject) {
  Php::Class<GtkWindow_> c("GtkWindow");
  c.extends(gobject);  // TODO: extends GtkWidget once the hierarchy exists
  c.method<&GtkWindow_::__construct>("__construct");
  c.method<&GtkWindow_::set_title>("set_title", {Php::ByVal("title", Php::Type::String)});
  c.method<&GtkWindow_::get_title>("get_title");
  c.method<&GtkWindow_::set_default_size>("set_default_size");
  c.method<&GtkWindow_::set_child>("set_child");
  c.method<&GtkWindow_::present>("present");
  c.method<&GtkWindow_::close>("close");
  c.method<&GtkWindow_::destroy>("destroy");
  ext.add(std::move(c));
}

}  // namespace phpgtk
