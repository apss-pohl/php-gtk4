#pragma once
#include <phpcpp.h>
#include <string>

// Typed parameter accessors. Required -> throw, optional -> default.
namespace phpgtk {

inline void require(Php::Parameters &p, size_t n, const char *sig) {
  if (p.size() < n) throw Php::Exception(std::string("expected ") + sig);
}
inline std::string arg_string(Php::Parameters &p, size_t i, const char *def = "") {
  return i < p.size() && !p[i].isNull() ? p[i].stringValue() : std::string(def);
}
inline int64_t arg_int(Php::Parameters &p, size_t i, int64_t def = 0) {
  return i < p.size() ? p[i].numericValue() : def;
}
inline bool arg_bool(Php::Parameters &p, size_t i, bool def = false) {
  return i < p.size() ? p[i].boolValue() : def;
}

}  // namespace phpgtk
