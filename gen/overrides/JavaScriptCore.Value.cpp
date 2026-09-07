// function_call() / constructor_call() / object_invoke_method(): the arguments come as a PHP
// list of JSCValue, not as a C array with its length in front of it.
#include <vector>

namespace {

// The JSCValue behind every element of the list `list` (argument number `pos`) into `out`; a
// wrong element is a TypeError naming that argument. False, with the exception pending, on one.
bool jsc_values_from_list(zval *list, uint32_t pos, std::vector<JSCValue *> &out) {
  zval *item;
  // NOLINTNEXTLINE(readability-math-missing-parentheses) Zend macro expansion
  ZEND_HASH_FOREACH_VAL(Z_ARRVAL_P(list), item) {
    GObject *value = unwrap(item, JSC_TYPE_VALUE);
    if (value == nullptr) {
      if (EG(exception) != nullptr) zend_clear_exception();
      zend_argument_type_error(pos, "must be a list of JSCValue, %s found in it", zend_zval_value_name(item));
      return false;
    }
    out.push_back(JSC_VALUE(value));
  }
  ZEND_HASH_FOREACH_END();
  return true;
}

}  // namespace
