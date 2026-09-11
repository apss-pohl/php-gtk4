// The reply methods: the reply typed from the method's introspection (core/variant tuple),
// and GLib's reference on the invocation consumed exactly once. GLib hands the method-call
// handler an invocation it expects a single return_*() to take; the handle holds a reference
// of its own, so a second answer would eat that one and leave the handle on a dead object.
#include "core/variant.h"
#include "core/gerror.h"

#include <string>

namespace {

// qdata marking an invocation that answered.
GQuark answered_quark() {
  static const GQuark quark = g_quark_from_static_string("php-gtk4-invocation-answered");
  return quark;
}

// False (LogicException pending) once the invocation answered; check before converting the
// reply, so a wrong second reply is still reported as the second one.
bool invocation_open(GDBusMethodInvocation *invocation, const char *method) {
  if (g_object_get_qdata(G_OBJECT(invocation), answered_quark()) != nullptr) {
    zend_throw_exception_ex(spl_ce_LogicException, 0,
                            "Gtk4\\GDBusMethodInvocation::%s(): this invocation was answered already",
                            method);
    return false;
  }
  return true;
}

// Marks the invocation answered: the next return_*() is refused. Any non-null pointer does;
// the object itself saves an int-to-pointer cast.
void invocation_answered(GDBusMethodInvocation *invocation) {
  g_object_set_qdata(G_OBJECT(invocation), answered_quark(), invocation);
}

}  // namespace
