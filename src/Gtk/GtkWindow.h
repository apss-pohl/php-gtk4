#pragma once
#include <phpcpp.h>
#include "../core/wrap.h"

// GtkWindow — the single hand-written element of milestone 1.
// NOTE: extends GObject directly for now; the GtkWidget layer will be inserted
// once the generator (milestone 3) produces the full hierarchy.
namespace phpgtk {

class GtkWindow_ : public GObjectWrapper {
 public:
  void __construct();
  void set_title(Php::Parameters &params);
  Php::Value get_title();
  void set_default_size(Php::Parameters &params);
  void set_child(Php::Parameters &params);
  void present();
  void close();
  void destroy();
};

void register_GtkWindow(Php::Namespace &ns, const Php::Class<GObjectWrapper> &gobject);

}  // namespace phpgtk
