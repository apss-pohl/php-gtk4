// Gtk4\GdkTexture - also the first user of GError -> exception and GBytes <-> string.
#include "php_gtk4.h"
#include "core/gerror.h"
#include "core/object.h"

using namespace phpgtk;

/**
 * static Gtk4\GdkTexture::new_from_filename(string $path): GdkTexture
 */
ZEND_METHOD(Gtk4_GdkTexture, new_from_filename) {
  zend_string *path;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_PATH_STR(path)
  ZEND_PARSE_PARAMETERS_END();
  GError *error = nullptr;
  GdkTexture *t = gdk_texture_new_from_filename(ZSTR_VAL(path), &error);
  if (t == nullptr) {
    throw_gerror(error);
    RETURN_THROWS();
  }
  wrap(G_OBJECT(t), return_value);
  g_object_unref(t);  // the handle took its own reference
}

/**
 * static Gtk4\GdkTexture::new_from_bytes(string $bytes): GdkTexture
 */
ZEND_METHOD(Gtk4_GdkTexture, new_from_bytes) {
  zend_string *data;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_STR(data)
  ZEND_PARSE_PARAMETERS_END();
  GBytes *bytes = g_bytes_new(ZSTR_VAL(data), ZSTR_LEN(data));
  GError *error = nullptr;
  GdkTexture *t = gdk_texture_new_from_bytes(bytes, &error);
  g_bytes_unref(bytes);
  if (t == nullptr) {
    throw_gerror(error);
    RETURN_THROWS();
  }
  wrap(G_OBJECT(t), return_value);
  g_object_unref(t);
}

/**
 * Gtk4\GdkTexture::get_width(): int
 */
ZEND_METHOD(Gtk4_GdkTexture, get_width) {
  ZEND_PARSE_PARAMETERS_NONE();
  GdkTexture *t = PHPGTK_SELF(GdkTexture, GDK_TYPE_TEXTURE);
  RETURN_LONG(gdk_texture_get_width(t));
}

/**
 * Gtk4\GdkTexture::get_height(): int
 */
ZEND_METHOD(Gtk4_GdkTexture, get_height) {
  ZEND_PARSE_PARAMETERS_NONE();
  GdkTexture *t = PHPGTK_SELF(GdkTexture, GDK_TYPE_TEXTURE);
  RETURN_LONG(gdk_texture_get_height(t));
}

/**
 * Gtk4\GdkTexture::save_to_png_bytes(): string
 *
 * The texture encoded as PNG.
 */
ZEND_METHOD(Gtk4_GdkTexture, save_to_png_bytes) {
  ZEND_PARSE_PARAMETERS_NONE();
  GdkTexture *t = PHPGTK_SELF(GdkTexture, GDK_TYPE_TEXTURE);
  GBytes *bytes = gdk_texture_save_to_png_bytes(t);
  gsize size = 0;
  const auto *data = static_cast<const char *>(g_bytes_get_data(bytes, &size));
  RETVAL_STRINGL(data, size);
  g_bytes_unref(bytes);
}

/**
 * Gtk4\GdkTexture::save_to_png(string $path): void
 */
ZEND_METHOD(Gtk4_GdkTexture, save_to_png) {
  zend_string *path;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_PATH_STR(path)
  ZEND_PARSE_PARAMETERS_END();
  GdkTexture *t = PHPGTK_SELF(GdkTexture, GDK_TYPE_TEXTURE);
  // gdk_texture_save_to_png() has no GError: report the failure ourselves.
  if (!gdk_texture_save_to_png(t, ZSTR_VAL(path))) {
    GError *error = g_error_new(G_FILE_ERROR, G_FILE_ERROR_FAILED, "could not write PNG to \"%s\"",
                                ZSTR_VAL(path));
    throw_gerror(error);
    RETURN_THROWS();
  }
}
