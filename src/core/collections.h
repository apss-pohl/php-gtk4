// GLib collections -> PHP arrays with GIR transfer semantics.
//
//   Transfer::None       the callee still owns list and elements: copy, free nothing
//   Transfer::Container  we own the list, not the elements: free the list
//   Transfer::Full       we own both: unref/free the elements, free the list
//
// Elements are converted by their GType: GObject-derived -> wrap() (handles,
// identity preserved), G_TYPE_STRING -> string, registered boxed -> wrap_boxed().
#pragma once
#include "php_gtk4.h"

namespace phpgtk {

enum class Transfer { None, Container, Full };

// GList -> list, elements converted by `element_type`, the list released per `transfer`.
void glist_to_php(GList *list, GType element_type, Transfer transfer, zval *rv);
// GSList -> list, likewise.
void gslist_to_php(GSList *list, GType element_type, Transfer transfer, zval *rv);
// GPtrArray -> list, likewise.
void gptrarray_to_php(GPtrArray *array, GType element_type, Transfer transfer, zval *rv);
// NULL-terminated char**; Full frees it with g_strfreev().
void strv_to_php(char **strv, Transfer transfer, zval *rv);
// A borrowed `const char * const *` (GTK getters): never freed.
void strv_to_php(const char *const *strv, zval *rv);

// PHP list of strings -> NULL-terminated char** (g_strfreev() it). Throws
// TypeError and returns nullptr if the value is not an array of scalars.
char **strv_from_php(zval *value);

}  // namespace phpgtk
