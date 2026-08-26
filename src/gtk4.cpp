// Module entry for the gtk4 extension.
#include "php_gtk4.h"
#include "core/boxed.h"
#include "core/cairo.h"
#include "core/classes.h"
#include "core/enums.h"
#include "core/fundamental.h"
#include "core/gerror.h"
#include "core/error.h"
#include "core/object.h"
#include "core/paramspec.h"
#include "core/phpvalue.h"
#include "core/teardown.h"
#include <Zend/zend_modules.h>

#include <string>

namespace phpgtk {
zend_class_entry *ce_ExceptionMode = nullptr;
zend_class_entry *ce_GMainLoop = nullptr;
zend_class_entry *ce_GParamSpec = nullptr;
zend_class_entry *ce_GListModel = nullptr;
void register_GdkRGBA(zend_class_entry *ce);
void register_GdkRectangle(zend_class_entry *ce);
void register_GMainLoop_handlers(zend_class_entry *ce);
}  // namespace phpgtk

// Generated from src/gtk4.stub.php by gen/gen_stub.php. Included exactly
// once with the method tables; other TUs only need the ZEND_METHOD prototypes
// (the header is guarded for that).
#include "gtk4_arginfo.h"

PHP_INI_BEGIN()
PHP_INI_ENTRY("gtk4.build_info", PHPGTK_BUILD_INFO, PHP_INI_SYSTEM, nullptr)
PHP_INI_ENTRY("gtk4.features", PHPGTK_BUILD_FEATURES, PHP_INI_SYSTEM, nullptr)
PHP_INI_END()

// Module init: ini entries, constants, handlers, class registration (parents first).
static PHP_MINIT_FUNCTION(gtk4) {
  REGISTER_INI_ENTRIES();
  register_gtk4_symbols(module_number);  // Gtk4\VERSION, BUILD_INFO, FEATURES
  phpgtk::object_handlers_init();
  phpgtk::boxed_handlers_init();
  phpgtk::fundamental_handlers_init();

  // Class registration, parents first (the generator will emit this block).
  // register_class() installs create_object before subclasses inherit it and
  // records the GType name -> class mapping used by wrap().
  zend_class_entry *ce_GObject = register_class_Gtk4_GObject();
  phpgtk::register_class("GObject", ce_GObject, G_TYPE_OBJECT);
  phpgtk::ce_ExceptionMode = register_class_Gtk4_ExceptionMode();
  // Enums/flags: registered before any class that mentions them in arginfo.
  phpgtk::register_enum(GTK_TYPE_ALIGN, register_class_Gtk4_GtkAlign());
  phpgtk::register_enum(GTK_TYPE_ORIENTATION, register_class_Gtk4_GtkOrientation());
  phpgtk::register_flags(G_TYPE_APPLICATION_FLAGS, register_class_Gtk4_GApplicationFlags());
  phpgtk::register_enum(GTK_TYPE_FILTER_CHANGE, register_class_Gtk4_GtkFilterChange());
  phpgtk::register_enum(GTK_TYPE_SORTER_CHANGE, register_class_Gtk4_GtkSorterChange());
  register_class_Gtk4_Gtk();
  register_class_Gtk4_GLib();
  phpgtk::ce_GMainLoop = register_class_Gtk4_GMainLoop();
  phpgtk::register_GMainLoop_handlers(phpgtk::ce_GMainLoop);
  phpgtk::register_GdkRGBA(register_class_Gtk4_GdkRGBA());
  phpgtk::register_GdkRectangle(register_class_Gtk4_GdkRectangle());
  phpgtk::ce_GParamSpec = register_class_Gtk4_GParamSpec();
  phpgtk::register_GParamSpec(phpgtk::ce_GParamSpec);
  phpgtk::ce_GError = register_class_Gtk4_GError(spl_ce_RuntimeException);
  phpgtk::register_class("GdkTexture", register_class_Gtk4_GdkTexture(ce_GObject),
                         GDK_TYPE_TEXTURE);
  phpgtk::register_class("PhpValue", register_class_Gtk4_PhpValue(ce_GObject), PHP_TYPE_VALUE);
  phpgtk::register_CairoContext(register_class_Gtk4_CairoContext());
  zend_class_entry *ce_GListModel = register_class_Gtk4_GListModel();
  phpgtk::ce_GListModel = ce_GListModel;
  phpgtk::register_class("GListStore", register_class_Gtk4_GListStore(ce_GObject, ce_GListModel),
                         G_TYPE_LIST_STORE);
  zend_class_entry *ce_GAction = register_class_Gtk4_GAction();
  zend_class_entry *ce_GActionMap = register_class_Gtk4_GActionMap();
  zend_class_entry *ce_GActionGroup = register_class_Gtk4_GActionGroup();
  phpgtk::register_class("GSimpleAction", register_class_Gtk4_GSimpleAction(ce_GObject, ce_GAction),
                         G_TYPE_SIMPLE_ACTION);
  phpgtk::register_class(
      "GtkApplication",
      register_class_Gtk4_GtkApplication(ce_GObject, ce_GActionMap, ce_GActionGroup),
      GTK_TYPE_APPLICATION);
  zend_class_entry *ce_GtkWidget = register_class_Gtk4_GtkWidget(ce_GObject);
  phpgtk::register_class("GtkWidget", ce_GtkWidget, GTK_TYPE_WIDGET);
  phpgtk::register_class("GtkButton", register_class_Gtk4_GtkButton(ce_GtkWidget), GTK_TYPE_BUTTON);
  phpgtk::register_class("GtkBox", register_class_Gtk4_GtkBox(ce_GtkWidget), GTK_TYPE_BOX);
  phpgtk::register_class("GtkLabel", register_class_Gtk4_GtkLabel(ce_GtkWidget), GTK_TYPE_LABEL);
  phpgtk::register_class("GtkWindow", register_class_Gtk4_GtkWindow(ce_GtkWidget), GTK_TYPE_WINDOW);
  phpgtk::register_class("GtkDrawingArea", register_class_Gtk4_GtkDrawingArea(ce_GtkWidget),
                         GTK_TYPE_DRAWING_AREA);
  zend_class_entry *ce_GtkFilter = register_class_Gtk4_GtkFilter(ce_GObject);
  phpgtk::register_class("GtkFilter", ce_GtkFilter, GTK_TYPE_FILTER);
  phpgtk::register_class("GtkCustomFilter", register_class_Gtk4_GtkCustomFilter(ce_GtkFilter),
                         GTK_TYPE_CUSTOM_FILTER);
  phpgtk::register_class("GtkFilterListModel",
                         register_class_Gtk4_GtkFilterListModel(ce_GObject, ce_GListModel),
                         GTK_TYPE_FILTER_LIST_MODEL);
  zend_class_entry *ce_GtkSorter = register_class_Gtk4_GtkSorter(ce_GObject);
  phpgtk::register_class("GtkSorter", ce_GtkSorter, GTK_TYPE_SORTER);
  phpgtk::register_class("GtkCustomSorter", register_class_Gtk4_GtkCustomSorter(ce_GtkSorter),
                         GTK_TYPE_CUSTOM_SORTER);
  phpgtk::register_class("GtkSortListModel",
                         register_class_Gtk4_GtkSortListModel(ce_GObject, ce_GListModel),
                         GTK_TYPE_SORT_LIST_MODEL);

  // libgtk-3 and libgtk-4 export the same C symbols; whichever loaded first
  // wins symbol resolution, so with php-gtk3 present every gtk4 call silently
  // lands in GTK 3. PHP names are namespaced and do not clash - warn anyway.
  if (zend_hash_str_exists(&module_registry, "gtk3", sizeof("gtk3") - 1)) {
    php_error_docref(nullptr, E_WARNING,
                     "extension 'gtk3' is also loaded. libgtk-3 and libgtk-4 cannot share a "
                     "process (identical C symbols); gtk4 calls will misbehave. Load only one "
                     "of them (e.g. bin/php-gtk4, or php -n -dextension=gtk4).");
  }
  return SUCCESS;
}

// Module shutdown.
static PHP_MSHUTDOWN_FUNCTION(gtk4) {
  UNREGISTER_INI_ENTRIES();
  return SUCCESS;
}

// Request init: one-time verification of the PHP enums against the C enums.
static PHP_RINIT_FUNCTION(gtk4) {
  phpgtk::enums_verify();
  return SUCCESS;
}

// Request shutdown: tear down callables before Zend goes away, reset the exception state.
static PHP_RSHUTDOWN_FUNCTION(gtk4) {
  phpgtk::teardown_request();  // before anything that holds callables could finalize later
  phpgtk::phpvalue_request_shutdown();
  phpgtk::exception_state_shutdown();
  return SUCCESS;
}

// phpinfo() section.
static PHP_MINFO_FUNCTION(gtk4) {
  php_info_print_table_start();
  php_info_print_table_row(2, "gtk4 support", "enabled");
  php_info_print_table_row(2, "version", PHP_GTK4_VERSION);
  php_info_print_table_row(2, "build", PHPGTK_BUILD_INFO);
  php_info_print_table_row(2, "features", PHPGTK_BUILD_FEATURES);
  php_info_print_table_row(2, "gtk (compiled)",
                           G_STRINGIFY(GTK_MAJOR_VERSION) "." G_STRINGIFY(
                               GTK_MINOR_VERSION) "." G_STRINGIFY(GTK_MICRO_VERSION));
  const std::string runtime = std::to_string(gtk_get_major_version()) + "." +
                              std::to_string(gtk_get_minor_version()) + "." +
                              std::to_string(gtk_get_micro_version());
  php_info_print_table_row(2, "gtk (runtime)", runtime.c_str());
  php_info_print_table_end();
  DISPLAY_INI_ENTRIES();
}

zend_module_entry gtk4_module_entry = {
    STANDARD_MODULE_HEADER,
    "gtk4",
    nullptr,  // no global functions - everything lives in Gtk4\ classes
    PHP_MINIT(gtk4),
    PHP_MSHUTDOWN(gtk4),
    PHP_RINIT(gtk4),
    PHP_RSHUTDOWN(gtk4),
    PHP_MINFO(gtk4),
    PHP_GTK4_VERSION,
    STANDARD_MODULE_PROPERTIES,
};

ZEND_GET_MODULE(gtk4)
