#include "wrap.h"
#include "gsignal.h"
#include "marshal.h"
#include <string>

namespace phpgtk {

static GQuark wrapper_quark() {
  static GQuark q = g_quark_from_static_string("php-gtk4-wrapper");
  return q;
}

GObjectWrapper::~GObjectWrapper() {
  if (obj_ != nullptr) {
    g_object_weak_unref(obj_, on_finalized, this);
    g_object_set_qdata(obj_, wrapper_quark(), nullptr);
    g_object_unref(obj_);
    obj_ = nullptr;
  }
}

void GObjectWrapper::attach(GObject *obj) {
  if (obj_ != nullptr || obj == nullptr) {
    g_critical("php-gtk4: GObjectWrapper::attach() misuse");
    return;
  }
  obj_ = G_OBJECT(g_object_ref_sink(obj));  // NOLINT(modernize-type-traits) GLib cast macro
  g_object_set_qdata(obj_, wrapper_quark(), this);
  // If GTK finalizes the object behind our back (it shouldn't while we hold a
  // ref, but dispose paths exist), null the handle instead of dangling.
  g_object_weak_ref(obj_, on_finalized, this);
}

void GObjectWrapper::on_finalized(gpointer data, GObject *) {
  static_cast<GObjectWrapper *>(data)->obj_ = nullptr;
}

void GObjectWrapper::__clone() {
  throw Php::Exception("GObject handles cannot be cloned");
}

Php::Value GObjectWrapper::connect(Php::Parameters &params) {
  return signal_connect(this, params, false);
}
Php::Value GObjectWrapper::connect_after(Php::Parameters &params) {
  return signal_connect(this, params, true);
}
void GObjectWrapper::handler_disconnect(Php::Parameters &params) {
  if (obj_ == nullptr) return;
  gulong id = static_cast<gulong>(params[0].numericValue());
  if (g_signal_handler_is_connected(obj_, id)) g_signal_handler_disconnect(obj_, id);
}

Php::Value GObjectWrapper::get_property(Php::Parameters &params) {
  std::string name = params[0];
  GParamSpec *spec = g_object_class_find_property(G_OBJECT_GET_CLASS(obj_), name.c_str());
  if (spec == nullptr)
    throw Php::Exception("no property '" + name + "' on " + G_OBJECT_TYPE_NAME(obj_));
  GValue v = G_VALUE_INIT;
  g_value_init(&v, spec->value_type);
  g_object_get_property(obj_, name.c_str(), &v);
  Php::Value out = to_php(&v);
  g_value_unset(&v);
  return out;
}

void GObjectWrapper::set_property(Php::Parameters &params) {
  std::string name = params[0];
  GParamSpec *spec = g_object_class_find_property(G_OBJECT_GET_CLASS(obj_), name.c_str());
  if (spec == nullptr)
    throw Php::Exception("no property '" + name + "' on " + G_OBJECT_TYPE_NAME(obj_));
  GValue v = G_VALUE_INIT;
  to_gvalue(params[1], spec->value_type, &v);
  g_object_set_property(obj_, name.c_str(), &v);
  g_value_unset(&v);
}

Php::Value wrap(GObject *obj) {
  if (obj == nullptr) return nullptr;

  if (auto *existing = static_cast<GObjectWrapper *>(g_object_get_qdata(obj, wrapper_quark()))) {
    // TODO: return the existing zval directly
    return Php::Object(php_class_name(G_OBJECT_TYPE_NAME(obj)).c_str(), existing);
  }

  // Nearest registered PHP class walking up the GType chain.
  for (GType t = G_OBJECT_TYPE(obj); t != 0; t = g_type_parent(t)) {
    const std::string name = php_class_name(g_type_name(t));
    if (Php::call("class_exists", name, false).boolValue()) {
      auto *w = new GObjectWrapper();
      w->attach(obj);
      return Php::Object(name.c_str(), w);
    }
  }
  throw Php::Exception(std::string("no PHP class registered for ") + G_OBJECT_TYPE_NAME(obj));
}

GObject *unwrap(const Php::Value &value, GType expected) {
  // implementation() assumes a PHP-CPP-created object and crashes on anything
  // else (stdClass, closures, ...), so check the class first.
  if (!value.isObject() || !value.instanceOf(php_class_name("GObject"))) {
    throw Php::Exception("expected a GObject instance");
  }
  auto *w = dynamic_cast<GObjectWrapper *>(value.implementation());
  if (w == nullptr || w->obj() == nullptr) throw Php::Exception("expected a live GObject instance");
  if (g_type_is_a(G_OBJECT_TYPE(w->obj()), expected) == FALSE) {
    throw Php::Exception(std::string("expected ") + g_type_name(expected) + ", got " +
                         G_OBJECT_TYPE_NAME(w->obj()));
  }
  return w->obj();
}

}  // namespace phpgtk
