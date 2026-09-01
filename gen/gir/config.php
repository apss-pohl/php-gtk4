<?php

/**
 * What the generator reads and what it considers in scope.
 *
 * Part of gen/gir.php, the GObject-Introspection generator (docs/PLAN.md milestone 3);
 * gen/README.md describes the flow. Split out of the 3 200-line original on 2026-08-30.
 */

declare(strict_types=1);

namespace PhpGtk4\Gen;

const GIR_DIRS = ['/usr/share/gir-1.0', '/usr/lib/x86_64-linux-gnu/gir-1.0', '/usr/lib64/gir-1.0'];
const GIR_FILES = ['GLib-2.0', 'GObject-2.0', 'Gio-2.0', 'cairo-1.0', 'Pango-1.0', 'Gdk-4.0', 'Gsk-4.0', 'Gtk-4.0'];
const GTK_FLOOR = '4.14';           // API newer than this is skipped in this wave
const PHP_NAMESPACE = 'Gtk4';
const INT_TYPES = '/^g(u?int(8|16|32|64)?|size|ssize|u?long|u?short|unichar|u?char)$/';

/**
 * Boxed records whose values point into a GObject: qname -> the C accessor for that owner.
 * The generated registration hands it to core/boxed, and the handle refs the owner for its
 * lifetime - a GtkTextIter whose buffer the script dropped dangled into freed memory.
 */
const BOXED_OWNERS = [
    'Gtk.TextIter' => 'gtk_text_iter_get_buffer',
];

/**
 * Caveats appended to a generated `vfunc_<name>()` docblock: "Ns.Type.vfunc" -> the sentence.
 * For slots GTK does not always route through the class struct, so that an override which
 * silently never runs is documented rather than discovered.
 */
const VFUNC_NOTES = [
    'Gtk.Widget.size_allocate' => 'gtk_widget_allocate() hands a widget that has one to the '
        . 'layout manager instead of to this slot, so an override on a GtkBox, GtkOverlay or any '
        . 'other subclass whose class_init installs a layout manager never runs. GtkWindow, '
        . 'GtkDrawingArea and a direct GtkWidget subclass have none and do reach it.',
    'Gtk.Widget.measure' => 'A widget that has a layout manager is measured by it and never '
        . 'reaches this slot, exactly as with `vfunc_size_allocate()`.',
];

const NS_GIR = 'http://www.gtk.org/introspection/core/1.0';
const NS_C = 'http://www.gtk.org/introspection/c/1.0';
const NS_GLIB = 'http://www.gtk.org/introspection/glib/1.0';
