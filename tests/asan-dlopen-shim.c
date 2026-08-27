/*
 * LD_PRELOAD shim for the asan stage (ci.sh): strips RTLD_DEEPBIND from dlopen().
 *
 * PHP builds configured with PHP_USE_RTLD_DEEPBIND (setup-php's binaries on the CI
 * runners) load extensions with RTLD_DEEPBIND, and the sanitizer runtime refuses to
 * dlopen anything with that flag (sanitizers issue #611) - there is no opt-out. The
 * sanitized gtk4-asan.so is loaded into a stock, uninstrumented php, so this shim is
 * preloaded *before* libasan; it hands the call on to the next dlopen (libasan's
 * interceptor) without the flag. DEEPBIND only changes symbol lookup order for the
 * loaded library, which nothing in gtk4.so relies on.
 */
#define _GNU_SOURCE
#include <dlfcn.h>

void *dlopen(const char *filename, int flags) {
  static void *(*next)(const char *, int) = 0;
  if (!next) next = (void *(*)(const char *, int))dlsym(RTLD_NEXT, "dlopen");
  return next(filename, flags & ~RTLD_DEEPBIND);
}
