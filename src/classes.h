// MINIT hooks of the classes that are not plain GObject handles: boxed values,
// fundamental handles and classes with their own object layout. Each one gets the
// class entry gen_stub created and installs handlers / registry entries. Called
// from src/gtk4.cpp only (the generator emits gen_minit.inc for GObject classes, interfaces and
// enums; this list of non-GObject handles stays hand-written - the fourth place for hand code, see
// CLAUDE.md).
#pragma once
#include "php_gtk4.h"

namespace phpgtk {
void register_GMainLoop(zend_class_entry *ce);      // own object layout (GLib/GMainLoop.cpp)
void register_GParamSpec(zend_class_entry *ce);     // fundamental (core/paramspec.cpp)
void register_CairoContext(zend_class_entry *ce);   // fundamental (Cairo/CairoContext.cpp)
void register_CairoSurface(zend_class_entry *ce);   // fundamental (Cairo/CairoSurface.cpp)
void register_GdkRGBA(zend_class_entry *ce);        // boxed (Gdk/GdkRGBA.cpp)
void register_GdkRectangle(zend_class_entry *ce);   // boxed (Gdk/GdkRectangle.cpp)
void register_GtkCssSection(zend_class_entry *ce);  // fundamental (Gtk/GtkCssSection.cpp)
void register_GdkEvent(GType type,
                       zend_class_entry *ce);  // fundamental, per event GType (Gdk/GdkEvent.cpp)
void register_GdkEventSequence(
    zend_class_entry *ce);  // fundamental, identity only (Gdk/GdkEventSequence.cpp)
}  // namespace phpgtk
