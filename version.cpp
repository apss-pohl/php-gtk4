// Build metadata. This translation unit is force-rebuilt by the Makefile so
// the git hash / build date are always current.
#include "src/core/version.h"

#ifndef PHPGTK_GIT_HASH
#define PHPGTK_GIT_HASH "unknown"
#endif
#ifndef PHPGTK_BUILD_DATE
#define PHPGTK_BUILD_DATE "unknown"
#endif

const char *phpgtk_build_info() {
  return "built " PHPGTK_BUILD_DATE ", git " PHPGTK_GIT_HASH;
}

const char *phpgtk_build_features() {
  return "webkit="
#ifdef WITH_WEBKIT
         "yes";
#else
         "no";
#endif
}
