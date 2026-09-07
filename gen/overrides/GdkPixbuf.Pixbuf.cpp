// save_to_bufferv()/savev()/get_options()/get_file_info(): option maps as PHP arrays, the
// buffer as a PHP string, the file's format as a boxed handle.
#include "core/collections.h"

#include <vector>

namespace {

// A PHP map of option name => value (both strings) as the two parallel NULL-terminated arrays
// gdk_pixbuf_save*() take. False with an argument error naming `arg` pending; the vectors keep
// the strings alive for the call.
bool options_from_php(zval *options, uint32_t arg, std::vector<char *> &keys, std::vector<char *> &values) {
  zend_string *key = nullptr;
  zend_ulong index = 0;
  zval *value;
  // NOLINTNEXTLINE(readability-math-missing-parentheses) Zend macro expansion
  ZEND_HASH_FOREACH_KEY_VAL(Z_ARRVAL_P(options), index, key, value) {
    (void)index;
    if (key == nullptr || Z_TYPE_P(value) != IS_STRING) {
      zend_argument_value_error(arg, "must map option names to string values ('quality' => '90')");
      return false;
    }
    keys.push_back(ZSTR_VAL(key));
    values.push_back(Z_STRVAL_P(value));
  }
  ZEND_HASH_FOREACH_END();
  keys.push_back(nullptr);
  values.push_back(nullptr);
  return true;
}

}  // namespace

