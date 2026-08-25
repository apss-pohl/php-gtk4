#pragma once
#include <phpcpp.h>

// Static `Gtk` class: init / main loop / exception handler.
namespace phpgtk {

class Gtk_ : public Php::Base {
 public:
  static Php::Value init();
  static void main();
  static void main_quit();
  static void set_exception_handler(Php::Parameters &params);
};

void register_Gtk(Php::Namespace &ns);

}  // namespace phpgtk
