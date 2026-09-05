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
const GIR_FILES = ['GLib-2.0', 'GObject-2.0', 'Gio-2.0', 'cairo-1.0', 'Pango-1.0', 'Graphene-1.0', 'Gdk-4.0',
    'Gsk-4.0', 'Gtk-4.0'];
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

/** GMenuModel refuses `new` (gen/skip.txt); its slots are overridden from a subclass of GMenu. */
const MENU_MODEL_SLOT_NOTE = 'GMenuModel itself cannot be subclassed (its constructor is private); '
    . 'override this from a PHP subclass of `GMenu`, which fills the slot natively.';

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
    'Gio.MenuModel.get_n_items' => MENU_MODEL_SLOT_NOTE,
    'Gio.MenuModel.is_mutable' => MENU_MODEL_SLOT_NOTE,
    'Gio.MenuModel.get_item_link' => MENU_MODEL_SLOT_NOTE,
    'Gio.MenuModel.get_item_attribute_value' => MENU_MODEL_SLOT_NOTE,
];


/**
 * Thunks returning a GVariant convert the PHP value against the type the object declares for
 * it, when the interface has a slot that names one: "Ns.Type.vfunc" -> the C expression of that
 * `const GVariantType *` (the thunk's arguments are in scope). A slot not listed here infers
 * the type from the value (core/variant).
 */
const VFUNC_VARIANT_TYPE = [
    'Gio.Action.get_state' => 'g_action_get_state_type(G_ACTION(self))',
    'Gio.ActionGroup.get_action_state' => 'g_action_group_get_action_state_type(G_ACTION_GROUP(self), action_name)',
];

/**
 * Returns GIR does not mark nullable but that really are: C identifier -> why. The declared
 * return type is a promise the engine never verifies (CLAUDE.md, "The declared type is a
 * promise the engine does not keep"), so a getter that answers NULL under a non-nullable
 * declaration is a lie PHPStan believes. Found by `TypeDeclarationTest`, which calls every
 * arg-less getter and compares.
 */
const NULLABLE_RETURNS = [
    // priv->widget is NULL until gtk_event_controller_set_widget(); a controller that has not
    // been handed to gtk_widget_add_controller() yet answers NULL, and PHP can hold one.
    'gtk_event_controller_get_widget' => 'NULL until the controller is added to a widget',
    // both answer NULL, without a CRITICAL, for a widget that is not a child of the container
    'gtk_stack_get_page' => 'NULL for a widget that is not a child of the stack',
    'gtk_notebook_get_page' => 'NULL for a widget that is not a page of the notebook',
];

/**
 * Parameters GIR marks nullable that the C function refuses with a `g_return_if_fail()`:
 * "<c identifier>.<param>" -> why. Same problem as {@see NULLABLE_RETURNS} from the other
 * side - a `?callable` in the stub tells the script null is a legal "fire and forget", and
 * these four answer with a CRITICAL and no read. Dropping the `?` makes it an ordinary
 * TypeError at the boundary. Found by `RobustnessTest`, which passes null to every parameter
 * that admits it.
 */
const NON_NULLABLE_PARAMS = [
    'gdk_clipboard_read_async.callback' => 'gdk_clipboard_read_async: assertion callback != NULL',
    'gdk_clipboard_read_text_async.callback' => 'gdk_clipboard_read_text_async: assertion callback != NULL',
    'gdk_clipboard_read_texture_async.callback' => 'gdk_clipboard_read_texture_async: assertion callback != NULL',
    'gdk_clipboard_store_async.callback' => 'gdk_clipboard_store_async: assertion callback != NULL',
    // gdk_drop_read_async() has the same signature and very likely the same precondition, but a
    // GdkDrop needs a real drag from another client and no test can reach one (DragDropTest,
    // testTheseAreGdksToCreateNotPhps), so there is no evidence for a line here. Every entry in
    // this table is a precondition that was *observed* firing; add it when a drag test can show
    // one, not by symmetry.
];

/**
 * Members whose returned object must not outlive the object it came from: C identifier -> why.
 * The generated method calls `object_hold_owner(return_value, ZEND_THIS)`, so the returned
 * handle keeps `$this`'s handle alive - the object counterpart of {@see BOXED_OWNERS}, and the
 * reason it is held between the handles rather than the GObjects is in src/core/object.h.
 *
 * Each line is a use-after-free the ASan build reproduces without it: the returned object keeps
 * a bare pointer into its owner, so `new GtkStack()->get_pages()` (owner dropped on the same
 * line) reads freed memory - `get_n_items()` answered 497 - and a composite widget's internal
 * child measures through its parent's private struct.
 *
 * Only `$this` can be named as the owner, so the sibling walk cannot be expressed here:
 * `get_next_sibling()`'s result belongs to the shared parent, not to the receiver. That gap was
 * measured rather than assumed - reaching a sibling through the eight composite widgets that
 * have one, dropping everything that holds the parent and then calling every arg-less getter on
 * the orphan (and on its own next sibling) is clean under ASan+UBSan, so nothing needs the
 * owner-expression form BOXED_OWNERS has. Re-measure before adding one.
 */
const RETURNS_HOLD_SELF = [
    'gtk_stack_get_pages' => 'GtkStackPages keeps a bare GtkStack pointer (the stack holds only a weak one back)',
    'gtk_widget_get_first_child' => 'a composite widget hands out private children that measure through its own struct',
    'gtk_widget_get_last_child' => 'a composite widget hands out private children that measure through its own struct',
];

/**
 * Widget parameters GTK requires to be in a particular place, as `<C identifier>.<param>` ->
 * the relation: `parent` (a child of `$this`), `page` (a page of this notebook), or
 * `child-of:<param>` (a child of the widget in another parameter). GTK checks each with a
 * g_return_if_fail() - a CRITICAL and the call silently not happening - so the generator emits
 * the check first and raises a LogicException naming the argument (the handle is in the wrong
 * state for the call, CLAUDE.md's vocabulary). Every line retired an entry from
 * tests/robustness-criticals.txt; the message GTK printed is what decided the relation.
 */
const CHILD_PARAMS = [
    'gtk_box_reorder_child_after.child' => 'parent',
    'gtk_box_reorder_child_after.sibling' => 'parent',
    'gtk_fixed_move.widget' => 'parent',
    'gtk_overlay_set_clip_overlay.widget' => 'parent',
    'gtk_overlay_set_measure_overlay.widget' => 'parent',
    'gtk_grid_attach_next_to.sibling' => 'parent',
    'gtk_notebook_reorder_child.child' => 'page',
    'gtk_notebook_set_tab_label.child' => 'page',
    'gtk_notebook_set_tab_label_text.child' => 'page',
    'gtk_notebook_set_menu_label.child' => 'page',
    'gtk_notebook_set_menu_label_text.child' => 'page',
    'gtk_notebook_set_tab_reorderable.child' => 'page',
    'gtk_notebook_set_tab_detachable.child' => 'page',
    'gtk_widget_insert_after.previous_sibling' => 'child-of:parent',
    'gtk_widget_insert_before.next_sibling' => 'child-of:parent',
];

/**
 * String parameters GLib validates with a predicate of its own, as `<C identifier>.<param>` ->
 * [the C predicate over `%s` (the `const char *`), the ValueError's wording]. GLib asserts them
 * with g_return_if_fail() - a CRITICAL and nothing done - so the generator emits the predicate
 * after check_utf8(). Every line retired an entry from tests/robustness-criticals.txt.
 */
const PARAM_VALIDATORS = [
    'g_application_set_application_id.application_id' => ['g_application_id_is_valid(%s)',
        'must be a valid application id (reverse-DNS, org.example.App)'],
    'g_application_set_resource_base_path.resource_path' => ["%s[0] == '/'",  // not g_str_has_prefix(): a macro here
        'must be an absolute resource path'],
];

/**
 * Methods whose object must be in a state GLib asserts with g_return_if_fail(): `<C identifier>`
 * -> [the C predicate over `self` that has to hold, the LogicException's wording]. The handle is
 * in the wrong state for the call (CLAUDE.md's vocabulary).
 */
const SELF_PRECONDITIONS = [
    'g_application_withdraw_notification' => ['g_application_get_is_registered(self) == TRUE',
        'the application is not registered yet - notifications exist from `startup` on'],
];

/**
 * Methods that reparent `$this`: it must have no parent yet, or the one in the named parameter -
 * GTK otherwise warns "Can't set new parent" and does nothing. `<C identifier>` -> the parameter.
 */
const SELF_UNPARENTED_OR = [
    'gtk_widget_insert_after' => 'parent',
    'gtk_widget_insert_before' => 'parent',
];

/**
 * Parameters whose C function accepts a narrower domain than their type, as
 * `<C identifier>.<param>` -> `[min, max]` (`null` for an open end; a float bound makes it a
 * float check). GTK states these as `g_return_if_fail()`, so out of domain means a CRITICAL and
 * the call silently not happening - `$calendar->set_month(12)` left the month where it was and
 * told PHP nothing. The generator emits `check_domain()` (src/php_gtk4.h) after the type's own
 * `check_range<T>()`, which turns each into a `ValueError` naming the argument.
 *
 * Every bound here is copied from the assertion GTK itself printed under `RobustnessTest`; each
 * line retired one or more entries from tests/robustness-criticals.txt. Do not add a bound from
 * documentation alone - the pin file is what proves GTK enforces it.
 */
const PARAM_DOMAINS = [
    'gtk_calendar_set_day.day' => [1, 31],
    'gtk_calendar_set_month.month' => [0, 11],
    'gtk_calendar_set_year.year' => [1, 9999],
    'gtk_drawing_area_set_content_width.width' => [0, null],
    'gtk_drawing_area_set_content_height.height' => [0, null],
    'gtk_editable_get_chars.start_pos' => [0, null],
    'gtk_editable_delete_text.start_pos' => [0, null],
    'gtk_grid_attach.width' => [1, null],
    'gtk_grid_attach.height' => [1, null],
    'gtk_gesture_long_press_set_delay_factor.delay_factor' => [0.5, 2.0],
    'gtk_icon_theme_lookup_icon.scale' => [1, null],
    'pango_font_description_set_size.size' => [0, null],
];

const NS_GIR = 'http://www.gtk.org/introspection/core/1.0';
const NS_C = 'http://www.gtk.org/introspection/c/1.0';
const NS_GLIB = 'http://www.gtk.org/introspection/glib/1.0';
