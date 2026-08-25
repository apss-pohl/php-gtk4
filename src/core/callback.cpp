#include "callback.h"

#include "error.h"

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

// GDestroyNotify-compatible release of a Callback.
void callback_free(gpointer p) {
  auto *cb = static_cast<Callback *>(p);
  zval_ptr_dtor(&cb->callable);
  efree(cb);
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
  if (EG(exception) != nullptr) {
    zval_ptr_dtor(retval);
    ZVAL_UNDEF(retval);
    report_pending_exception(cb->origin);
    return false;
  }
  return true;
}

}  // namespace phpgtk
