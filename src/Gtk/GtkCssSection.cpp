// Gtk4\GtkCssSection: a GtkCssSection handle on the fundamental registry
// (GTK_TYPE_CSS_SECTION is a refcounted boxed type, gtk_css_section_ref/unref).
// Sections reach PHP as the first argument of GtkCssProvider's `parsing-error`
// signal, which marshal converts through this registry.
#include "php_gtk4.h"

#include "classes.h"
#include "core/fundamental.h"

using namespace phpgtk;

namespace {

// gtk_css_section_ref() as a RefFn.
gpointer section_ref(gpointer p) {
  return gtk_css_section_ref(static_cast<GtkCssSection *>(p));
}

// gtk_css_section_unref() as an UnrefFn.
void section_unref(gpointer p) {
  gtk_css_section_unref(static_cast<GtkCssSection *>(p));
}

// GtkCssLocation -> the PHP array both location getters return (the struct's fields).
void location_to_php(const GtkCssLocation *loc, zval *rv) {
  array_init_size(rv, 5);
  add_assoc_long(rv, "bytes", static_cast<zend_long>(loc->bytes));
  add_assoc_long(rv, "chars", static_cast<zend_long>(loc->chars));
  add_assoc_long(rv, "lines", static_cast<zend_long>(loc->lines));
  add_assoc_long(rv, "line_bytes", static_cast<zend_long>(loc->line_bytes));
  add_assoc_long(rv, "line_chars", static_cast<zend_long>(loc->line_chars));
}

}  // namespace

namespace phpgtk {

// MINIT: put GtkCssSection on the fundamental registry (marshal's boxed arm falls back to it).
void register_GtkCssSection(zend_class_entry *ce) {
  register_fundamental(FundamentalClass{
      .type = GTK_TYPE_CSS_SECTION, .ce = ce, .ref = section_ref, .unref = section_unref});
}

}  // namespace phpgtk

// The GtkCssSection behind $this.
#define SELF_SECTION GtkCssSection *self = PHPGTK_FUNDAMENTAL_SELF(GtkCssSection)

/**
 * Gtk4\GtkCssSection::to_string(): string
 *
 * "<file>:<start line>:<start column>-<end line>:<end column>", 1-based, as GTK prints it.
 */
ZEND_METHOD(Gtk4_GtkCssSection, to_string) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_SECTION;
  char *text = gtk_css_section_to_string(self);
  RETVAL_STRING(text);
  g_free(text);
}

/**
 * Gtk4\GtkCssSection::get_parent(): ?GtkCssSection
 *
 * The section this one is nested in, or null when there is none - which is what GTK 4.14 reports
 * for every parsing error, nested or not.
 */
ZEND_METHOD(Gtk4_GtkCssSection, get_parent) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_SECTION;
  wrap_fundamental(GTK_TYPE_CSS_SECTION, gtk_css_section_get_parent(self), return_value);
}

/**
 * Gtk4\GtkCssSection::get_start_location(): array
 *
 * Where the section starts, as GtkCssLocation's fields (all 0-based counts from the start of the
 * document; `lines` is the line number, `line_chars` the column).
 */
ZEND_METHOD(Gtk4_GtkCssSection, get_start_location) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_SECTION;
  location_to_php(gtk_css_section_get_start_location(self), return_value);
}

/**
 * Gtk4\GtkCssSection::get_end_location(): array
 *
 * Where the section ends; same shape as {@see get_start_location()}.
 */
ZEND_METHOD(Gtk4_GtkCssSection, get_end_location) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_SECTION;
  location_to_php(gtk_css_section_get_end_location(self), return_value);
}
