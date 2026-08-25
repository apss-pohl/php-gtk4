#include "Gtk.h"
#include "GtkWindow.h"
#include "../core/error.h"
#include "../core/wrap.h"
#include <gtk/gtk.h>

namespace phpgtk {

static GMainLoop *main_loop = nullptr;
static bool quit_pending = false;  // main_quit() called before main()

Php::Value Gtk_::init() {
  return (bool)gtk_init_check();
}

// GTK4 has no gtk_main(); run a GMainLoop on the default context.
void Gtk_::main() {
  if (main_loop != nullptr) throw Php::Exception("Gtk::main() is already running");
  if (quit_pending) {
    quit_pending = false;
    return;
  }
  main_loop = g_main_loop_new(nullptr, FALSE);
  g_main_loop_run(main_loop);
  g_main_loop_unref(main_loop);
  main_loop = nullptr;
}

void Gtk_::main_quit() {
  if (main_loop != nullptr) {
    g_main_loop_quit(main_loop);
  } else {
    quit_pending = true;
  }
}

void Gtk_::set_exception_handler(Php::Parameters &params) {
  if (params.empty() || params[0].isNull()) {
    phpgtk::set_exception_handler(nullptr);
    return;
  }
  if (!Php::call("is_callable", params[0]).boolValue()) {
    throw Php::Exception("Gtk::set_exception_handler() expects a callable or null");
  }
  phpgtk::set_exception_handler(params[0]);
}

void register_Gtk(Php::Namespace &ns) {
  // Root handle class - PHP name matches the GType name.
  Php::Class<GObjectWrapper> gobject("GObject");
  gobject.method<&GObjectWrapper::connect>("connect");
  gobject.method<&GObjectWrapper::connect_after>("connect_after");
  gobject.method<&GObjectWrapper::handler_disconnect>("handler_disconnect");
  gobject.method<&GObjectWrapper::get_property>("get_property");
  gobject.method<&GObjectWrapper::set_property>("set_property");

  Php::Class<Gtk_> gtk("Gtk");
  gtk.method<&Gtk_::init>("init");
  gtk.method<&Gtk_::main>("main");
  gtk.method<&Gtk_::main_quit>("main_quit");
  gtk.method<&Gtk_::set_exception_handler>("set_exception_handler");
  ns.add(std::move(gtk));

  // PHP-CPP initialises classes in add() order and a derived class added
  // before its base silently loses the base. So add the parent first (copy
  // overload), then pass it to children for extends().
  ns.add(gobject);
  register_GtkWindow(ns, gobject);
}

}  // namespace phpgtk
