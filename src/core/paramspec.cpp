#include "paramspec.h"

#include "../classes.h"
#include "fundamental.h"

namespace phpgtk {

// g_param_spec_ref/unref with the registry's gpointer signature.
static gpointer spec_ref(gpointer p) {
  return g_param_spec_ref(static_cast<GParamSpec *>(p));
}
// Unref counterpart for the registry.
static void spec_unref(gpointer p) {
  g_param_spec_unref(static_cast<GParamSpec *>(p));
}

// MINIT: GParamSpec is the first fundamental handle type.
void register_GParamSpec(zend_class_entry *ce) {
  register_fundamental(
      FundamentalClass{.type = G_TYPE_PARAM, .ce = ce, .ref = spec_ref, .unref = spec_unref});
}

// C -> PHP through the fundamental registry.
void wrap_param_spec(GParamSpec *spec, zval *rv) {
  wrap_fundamental(G_TYPE_PARAM, spec, rv);
}

}  // namespace phpgtk
