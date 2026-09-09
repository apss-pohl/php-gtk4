// Gtk4\GdkRGBA (boxed value type)
#include "php_gtk4.h"
#include "classes.h"
#include "core/boxed.h"

#include <cstring>

using namespace phpgtk;

namespace {

const char *const fields[] = {"red", "green", "blue", "alpha", nullptr};

// Boxed field reader: red/green/blue/alpha as float.
bool read(gpointer data, const char *f, zval *rv) {
  auto *c = static_cast<GdkRGBA *>(data);
  float v;
  if (strcmp(f, "red") == 0)
    v = c->red;
  else if (strcmp(f, "green") == 0)
    v = c->green;
  else if (strcmp(f, "blue") == 0)
    v = c->blue;
  else if (strcmp(f, "alpha") == 0)
    v = c->alpha;
  else
    return false;
  ZVAL_DOUBLE(rv, v);
  return true;
}

// Boxed field writer: accepts int/float, stored as float.
bool write(gpointer data, const char *f, zval *value) {
  auto *c = static_cast<GdkRGBA *>(data);
  float *p = nullptr;
  if (strcmp(f, "red") == 0) {
    p = &c->red;
  } else if (strcmp(f, "green") == 0) {
    p = &c->green;
  } else if (strcmp(f, "blue") == 0) {
    p = &c->blue;
  } else if (strcmp(f, "alpha") == 0) {
    p = &c->alpha;
  }
  if (p == nullptr) return false;
  // like a float parameter (core/boxed); true for a field even when the value was refused, so
  // the write never falls through to a dynamic PHP property of the same name
  double d = 0;
  if (boxed_field_double(value, "Gtk4\\GdkRGBA", f, &d)) *p = static_cast<float>(d);
  return true;
}

}  // namespace

/**
 * Gtk4\GdkRGBA::__construct(?string $css = null)
 */
ZEND_METHOD(Gtk4_GdkRGBA, __construct) {
  zend_string *css = nullptr;
  ZEND_PARSE_PARAMETERS_START(0, 1)
  Z_PARAM_OPTIONAL
  Z_PARAM_STR_OR_NULL(css)
  ZEND_PARSE_PARAMETERS_END();
  auto *c = g_new0(GdkRGBA, 1);
  if (css != nullptr && !check_utf8(css, 1)) {
    g_free(c);
    RETURN_THROWS();
  }
  if (css != nullptr && !gdk_rgba_parse(c, ZSTR_VAL(css))) {
    g_free(c);
    zend_argument_value_error(1, "is not a valid CSS colour");
    RETURN_THROWS();
  }
  boxed_adopt(boxed_from_zval(ZEND_THIS), GDK_TYPE_RGBA, c);
}

/**
 * Gtk4\GdkRGBA::parse(string $css): bool
 *
 * Parse a CSS colour into this value; false if it is not valid.
 */
ZEND_METHOD(Gtk4_GdkRGBA, parse) {
  zend_string *css;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_STR(css)
  ZEND_PARSE_PARAMETERS_END();
  GdkRGBA *self = PHPGTK_BOXED_SELF(GdkRGBA);
  GdkRGBA tmp;
  if (!check_utf8(css, 1)) RETURN_THROWS();
  if (!gdk_rgba_parse(&tmp, ZSTR_VAL(css))) RETURN_FALSE;
  *self = tmp;
  RETURN_TRUE;
}

/**
 * Gtk4\GdkRGBA::to_string(): string
 *
 * CSS representation, e.g. `rgb(255,0,0)` or `rgba(255,0,0,0.5)`.
 */
ZEND_METHOD(Gtk4_GdkRGBA, to_string) {
  ZEND_PARSE_PARAMETERS_NONE();
  GdkRGBA *self = PHPGTK_BOXED_SELF(GdkRGBA);
  gchar *s = gdk_rgba_to_string(self);
  RETVAL_STRING(s);
  g_free(s);
}

/**
 * Gtk4\GdkRGBA::equal(GdkRGBA $other): bool
 *
 * Component-wise equality (`==` compares handles by value too).
 */
ZEND_METHOD(Gtk4_GdkRGBA, equal) {
  zval *other;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_OBJECT_OF_CLASS(other, boxed_class_for_type(GDK_TYPE_RGBA)->ce)
  ZEND_PARSE_PARAMETERS_END();
  GdkRGBA *self = PHPGTK_BOXED_SELF(GdkRGBA);
  RETURN_BOOL(gdk_rgba_equal(self, unwrap_boxed(other, GDK_TYPE_RGBA)));
}

/**
 * Gtk4\GdkRGBA::is_opaque(): bool
 *
 * True when alpha is 1.
 */
ZEND_METHOD(Gtk4_GdkRGBA, is_opaque) {
  ZEND_PARSE_PARAMETERS_NONE();
  GdkRGBA *self = PHPGTK_BOXED_SELF(GdkRGBA);
  RETURN_BOOL(gdk_rgba_is_opaque(self));
}

namespace phpgtk {
// MINIT: bind the PHP class to GDK_TYPE_RGBA with its field table.
void register_GdkRGBA(zend_class_entry *ce) {
  register_boxed(
      BoxedClass{.type = GDK_TYPE_RGBA, .ce = ce, .fields = fields, .read = read, .write = write});
}
}  // namespace phpgtk
