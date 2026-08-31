// The insert family's (text, len) pairs: GTK reads exactly `len` bytes and trusts them to
// be valid UTF-8, so an unchecked count read past the PHP string (or cut a character in
// half) with nothing to notice. A PHP string knows its length: `len` defaults to -1 (all
// of it) where the signature allows, and any given count must be a prefix length on a
// character boundary.
namespace {

// Validate a byte count against `text` (already check_utf8()d): -1, or a prefix length
// ending on a UTF-8 character boundary. ValueError naming argument `arg` otherwise.
bool check_text_len(const zend_string *text, zend_long len, uint32_t arg) {
  const auto size = static_cast<zend_long>(ZSTR_LEN(text));
  if (len == -1) return true;
  if (len < 0 || len > size) {
    zend_argument_value_error(arg,
                              "must be -1 (all of the text) or between 0 and the text's byte "
                              "length (" ZEND_LONG_FMT ")",
                              size);
    return false;
  }
  if (len < size && (static_cast<unsigned char>(*(ZSTR_VAL(text) + len)) & 0xC0U) == 0x80U) {
    zend_argument_value_error(arg, "must not cut a UTF-8 character in half");
    return false;
  }
  return true;
}

}  // namespace
