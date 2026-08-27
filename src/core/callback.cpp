#include "callback.h"

#include "error.h"
#include "globals.h"

#include <vector>

namespace {
// Callables whose release was deferred by callback_free().
std::vector<zval> &graveyard() {
  return GTK4_G(callback_graveyard);
}
}  // namespace

namespace phpgtk {

// Allocate a Callback holding an ADDREF'd copy of the (already validated) callable.
Callback *callback_new(zval *callable, const char *origin) {
  // NOLINTNEXTLINE(bugprone-implicit-widening-of-multiplication-result) Zend emalloc macro
  auto *cb = static_cast<Callback *>(emalloc(sizeof(Callback)));
  ZVAL_COPY(&cb->callable, callable);
  cb->origin = origin;
  cb->source_id = 0;
  return cb;
}

// GDestroyNotify-compatible: park the callable for callback_drain(), free the struct.
void callback_free(gpointer p) {
  auto *cb = static_cast<Callback *>(p);
  graveyard().push_back(cb->callable);
  efree(cb);
}

// Release parked callables; re-entrant (a release may park more).
void callback_drain() {
  while (!graveyard().empty()) {
    std::vector<zval> batch;
    batch.swap(graveyard());
    for (zval &zv : batch) zval_ptr_dtor(&zv);
  }
}

// Invoke the callable; a Throwable goes through the exception policy with cb->origin.
bool callback_invoke(Callback *cb, uint32_t argc, zval *args, zval *retval) {
  ZVAL_UNDEF(retval);
  if (EG(exception) != nullptr) return false;  // Rethrow mode: an earlier callback threw
  zend_fcall_info fci;
  zend_fcall_info_cache fcc;
  if (zend_fcall_info_init(&cb->callable, 0, &fci, &fcc, nullptr, nullptr) != SUCCESS) {
    g_critical("php-gtk4: callback installed by %s is no longer callable", cb->origin);
    return false;
  }
  fci.retval = retval;
  fci.params = args;
  fci.param_count = argc;
  zend_call_function(&fci, &fcc);
  const bool ok = EG(exception) == nullptr;
  if (!ok) {
    zval_ptr_dtor(retval);
    ZVAL_UNDEF(retval);
    report_pending_exception(cb->origin);
  }
  callback_drain();  // the call may have replaced/removed callbacks
  return ok;
}

}  // namespace phpgtk
