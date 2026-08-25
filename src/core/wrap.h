#pragma once
#include <phpcpp.h>
#include <glib-object.h>
#include <string>

// PHP handle for a GObject.
//
// Unlike php-gtk3 the wrapper OWNS a reference (ref_sink on attach, unref on
// destruction) and registers itself in the GObject's qdata so that wrapping
// the same C object twice yields the same PHP object (identity, `===`).
namespace phpgtk {

// All PHP classes live in the `Gtk4` namespace so the extension can coexist
// with php-gtk3 (flat names) in one process. PHP class name = Gtk4\<GTypeName>.
constexpr const char *PHP_NAMESPACE = "Gtk4";
inline std::string php_class_name(const char *gtype_name) {
  return std::string(PHP_NAMESPACE) + "\\" + gtype_name;
}

class GObjectWrapper : public Php::Base {
 public:
  GObjectWrapper() = default;
  ~GObjectWrapper() override;
  GObjectWrapper(const GObjectWrapper &) = delete;
  GObjectWrapper &operator=(const GObjectWrapper &) = delete;

  GObject *obj() const { return obj_; }
  template <typename T>
  T *as(GType type) const {
    return reinterpret_cast<T *>(
        g_type_check_instance_cast(reinterpret_cast<GTypeInstance *>(obj_), type));
  }

  // Take (ref_sink) ownership of obj and bind this wrapper to it.
  void attach(GObject *obj);

  void __clone();

  // GObject-level PHP methods
  Php::Value connect(Php::Parameters &params);
  Php::Value connect_after(Php::Parameters &params);
  void handler_disconnect(Php::Parameters &params);
  Php::Value get_property(Php::Parameters &params);
  void set_property(Php::Parameters &params);

 private:
  GObject *obj_ = nullptr;
  static void on_finalized(gpointer data, GObject *where_the_object_was);
};

// Registry GType name -> factory creating the matching C++ wrapper type.
// Every registered PHP class adds itself here so that wrap() constructs the
// exact C++ type PHP-CPP expects for that PHP class (PHP-CPP static_casts the
// Php::Base to Class<T>'s T; allocating only the base class would be UB).
using WrapperFactory = GObjectWrapper *(*)();
void register_wrapper(const char *gtype_name, WrapperFactory factory);
template <typename T>
void register_wrapper(const char *gtype_name) {
  register_wrapper(gtype_name, []() -> GObjectWrapper * { return new T(); });
}

// C -> PHP: return the existing PHP object for obj, or create a new wrapper
// whose PHP class is the nearest registered ancestor of obj's GType.
Php::Value wrap(GObject *obj);

// PHP -> C: extract the GObject* from a PHP value (throws if not a wrapper
// or not an instance of `expected`).
GObject *unwrap(const Php::Value &value, GType expected = G_TYPE_OBJECT);

}  // namespace phpgtk
