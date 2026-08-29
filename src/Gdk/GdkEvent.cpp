// Gtk4\GdkEvent and its typed subclasses: handles on the fundamental registry.
// GdkEvent is GTK 4's own refcounted fundamental type (neither GObject nor boxed,
// gdk_event_ref/unref); the subtypes (GdkKeyEvent, GdkButtonEvent, ...) are GTypes
// below it, so the registry's parent walk hands each one its own PHP class and
// everything without a class of its own (GdkMotionEvent, GdkDeleteEvent, ...) the base.
// Events reach PHP from GtkEventController::get_current_event(), the
// GtkEventControllerLegacy `event` signal and GtkGesture::get_last_event(); there is
// no `new` (docs/PLAN.md §4: opaque with typed getters, never a field copy).
#include "php_gtk4.h"

#include "classes.h"
#include "core/enums.h"
#include "core/fundamental.h"
#include "core/object.h"

using namespace phpgtk;

namespace {

// gdk_event_ref() as a RefFn.
gpointer event_ref(gpointer p) {
  return gdk_event_ref(static_cast<GdkEvent *>(p));
}

// gdk_event_unref() as an UnrefFn.
void event_unref(gpointer p) {
  gdk_event_unref(static_cast<GdkEvent *>(p));
}

// Two doubles as the [x, y] / [dx, dy] list the position-like getters return.
void pair_to_php(double a, double b, zval *rv) {
  array_init_size(rv, 2);
  add_next_index_double(rv, a);
  add_next_index_double(rv, b);
}

}  // namespace

namespace phpgtk {

// MINIT: one registry entry per event GType that has a PHP class of its own; `ce` is the
// class gen_stub created for it (GdkEvent itself and its subclasses alike).
void register_GdkEvent(GType type, zend_class_entry *ce) {
  register_fundamental(
      FundamentalClass{.type = type, .ce = ce, .ref = event_ref, .unref = event_unref});
}

}  // namespace phpgtk

// The GdkEvent behind $this (the class guarantees the kind: a GdkKeyEvent method runs on a key
// event, because only wrap_fundamental() creates these and it picks the class from the GType).
#define SELF_EVENT GdkEvent *self = PHPGTK_FUNDAMENTAL_SELF(GdkEvent)

// ---------------------------------------------------------------- GdkEvent

/**
 * Gtk4\GdkEvent::get_event_type(): GdkEventType
 *
 * What kind of event this is (also visible in the handle's class).
 */
ZEND_METHOD(Gtk4_GdkEvent, get_event_type) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_EVENT;
  enum_to_php(GDK_TYPE_EVENT_TYPE, static_cast<gint>(gdk_event_get_event_type(self)), return_value);
}

/**
 * Gtk4\GdkEvent::get_time(): int
 *
 * The event's timestamp in milliseconds (server time, 0 when the event carries none).
 */
ZEND_METHOD(Gtk4_GdkEvent, get_time) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_EVENT;
  RETURN_LONG(static_cast<zend_long>(gdk_event_get_time(self)));
}

/**
 * Gtk4\GdkEvent::get_modifier_state(): int
 *
 * The keyboard modifiers and mouse buttons held down when the event happened ({@see
 * GdkModifierType} flags).
 */
ZEND_METHOD(Gtk4_GdkEvent, get_modifier_state) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_EVENT;
  RETURN_LONG(static_cast<zend_long>(gdk_event_get_modifier_state(self)));
}

/**
 * Gtk4\GdkEvent::get_position(): ?array
 *
 * The pointer position [x, y] in surface coordinates, or null for events that have none (key and
 * focus events).
 */
ZEND_METHOD(Gtk4_GdkEvent, get_position) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_EVENT;
  double x = 0;
  double y = 0;
  if (!gdk_event_get_position(self, &x, &y)) RETURN_NULL();
  pair_to_php(x, y, return_value);
}

/**
 * Gtk4\GdkEvent::get_pointer_emulated(): bool
 *
 * True for a pointer event that was synthesised from a touch sequence.
 */
ZEND_METHOD(Gtk4_GdkEvent, get_pointer_emulated) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_EVENT;
  RETURN_BOOL(gdk_event_get_pointer_emulated(self));
}

/**
 * Gtk4\GdkEvent::triggers_context_menu(): bool
 *
 * True when this event is the platform's "open a context menu" gesture (the secondary button,
 * Control+click on macOS).
 */
ZEND_METHOD(Gtk4_GdkEvent, triggers_context_menu) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_EVENT;
  RETURN_BOOL(gdk_event_triggers_context_menu(self));
}

/**
 * Gtk4\GdkEvent::get_display(): ?GdkDisplay
 *
 * The display the event came from.
 */
ZEND_METHOD(Gtk4_GdkEvent, get_display) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_EVENT;
  GdkDisplay *display = gdk_event_get_display(self);
  if (display == nullptr) RETURN_NULL();
  wrap(G_OBJECT(display), return_value);
}

// ---------------------------------------------------------------- GdkKeyEvent

/**
 * Gtk4\GdkKeyEvent::get_keyval(): int
 *
 * The key symbol (a `GDK_KEY_*` value, e.g. 65307 for Escape).
 */
ZEND_METHOD(Gtk4_GdkKeyEvent, get_keyval) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_EVENT;
  RETURN_LONG(static_cast<zend_long>(gdk_key_event_get_keyval(self)));
}

/**
 * Gtk4\GdkKeyEvent::get_keycode(): int
 *
 * The hardware key code.
 */
ZEND_METHOD(Gtk4_GdkKeyEvent, get_keycode) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_EVENT;
  RETURN_LONG(static_cast<zend_long>(gdk_key_event_get_keycode(self)));
}

/**
 * Gtk4\GdkKeyEvent::get_consumed_modifiers(): int
 *
 * The modifiers that were used up producing the keyval ({@see GdkModifierType} flags) - Shift for
 * "A", for instance - and so must not count as part of an accelerator.
 */
ZEND_METHOD(Gtk4_GdkKeyEvent, get_consumed_modifiers) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_EVENT;
  RETURN_LONG(static_cast<zend_long>(gdk_key_event_get_consumed_modifiers(self)));
}

/**
 * Gtk4\GdkKeyEvent::get_layout(): int
 *
 * The keyboard layout (group) the key was pressed in.
 */
ZEND_METHOD(Gtk4_GdkKeyEvent, get_layout) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_EVENT;
  RETURN_LONG(static_cast<zend_long>(gdk_key_event_get_layout(self)));
}

/**
 * Gtk4\GdkKeyEvent::get_level(): int
 *
 * The shift level of the key.
 */
ZEND_METHOD(Gtk4_GdkKeyEvent, get_level) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_EVENT;
  RETURN_LONG(static_cast<zend_long>(gdk_key_event_get_level(self)));
}

/**
 * Gtk4\GdkKeyEvent::is_modifier(): bool
 *
 * True when the key is itself a modifier (Shift, Control, ...).
 */
ZEND_METHOD(Gtk4_GdkKeyEvent, is_modifier) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_EVENT;
  RETURN_BOOL(gdk_key_event_is_modifier(self));
}

/**
 * Gtk4\GdkKeyEvent::matches(int $keyval, int $modifiers): GdkKeyMatch
 *
 * Whether the event matches an accelerator of $keyval + $modifiers ({@see GdkModifierType} flags):
 * exactly, partially (layout-independent) or not at all.
 */
ZEND_METHOD(Gtk4_GdkKeyEvent, matches) {
  zend_long keyval;
  zend_long modifiers;
  ZEND_PARSE_PARAMETERS_START(2, 2)
  Z_PARAM_LONG(keyval)
  Z_PARAM_LONG(modifiers)
  ZEND_PARSE_PARAMETERS_END();
  SELF_EVENT;
  const GdkKeyMatch match = gdk_key_event_matches(self, static_cast<guint>(keyval),
                                                  static_cast<GdkModifierType>(modifiers));
  enum_to_php(GDK_TYPE_KEY_MATCH, static_cast<gint>(match), return_value);
}

/**
 * Gtk4\GdkKeyEvent::get_match(): ?array
 *
 * The [keyval, modifiers] an accelerator would have to match this event, or null when the event
 * cannot be matched (a modifier key on its own).
 */
ZEND_METHOD(Gtk4_GdkKeyEvent, get_match) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_EVENT;
  guint keyval = 0;
  GdkModifierType modifiers = static_cast<GdkModifierType>(0);
  if (!gdk_key_event_get_match(self, &keyval, &modifiers)) RETURN_NULL();
  array_init_size(return_value, 2);
  add_next_index_long(return_value, static_cast<zend_long>(keyval));
  add_next_index_long(return_value, static_cast<zend_long>(modifiers));
}

// ---------------------------------------------------------------- GdkButtonEvent

/**
 * Gtk4\GdkButtonEvent::get_button(): int
 *
 * The mouse button (1 = primary, 2 = middle, 3 = secondary).
 */
ZEND_METHOD(Gtk4_GdkButtonEvent, get_button) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_EVENT;
  RETURN_LONG(static_cast<zend_long>(gdk_button_event_get_button(self)));
}

// ---------------------------------------------------------------- GdkScrollEvent

/**
 * Gtk4\GdkScrollEvent::get_direction(): GdkScrollDirection
 *
 * The scroll direction; `Smooth` means {@see get_deltas()} carries the amount.
 */
ZEND_METHOD(Gtk4_GdkScrollEvent, get_direction) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_EVENT;
  enum_to_php(GDK_TYPE_SCROLL_DIRECTION, static_cast<gint>(gdk_scroll_event_get_direction(self)),
              return_value);
}

/**
 * Gtk4\GdkScrollEvent::get_deltas(): array
 *
 * The scroll amount [dx, dy] of a smooth scroll event.
 */
ZEND_METHOD(Gtk4_GdkScrollEvent, get_deltas) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_EVENT;
  double dx = 0;
  double dy = 0;
  gdk_scroll_event_get_deltas(self, &dx, &dy);
  pair_to_php(dx, dy, return_value);
}

/**
 * Gtk4\GdkScrollEvent::get_unit(): GdkScrollUnit
 *
 * What the deltas are measured in (wheel clicks or surface pixels).
 */
ZEND_METHOD(Gtk4_GdkScrollEvent, get_unit) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_EVENT;
  enum_to_php(GDK_TYPE_SCROLL_UNIT, static_cast<gint>(gdk_scroll_event_get_unit(self)),
              return_value);
}

/**
 * Gtk4\GdkScrollEvent::is_stop(): bool
 *
 * True for the event that ends a smooth scroll sequence (fingers lifted).
 */
ZEND_METHOD(Gtk4_GdkScrollEvent, is_stop) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_EVENT;
  RETURN_BOOL(gdk_scroll_event_is_stop(self));
}

// ---------------------------------------------------------------- GdkCrossingEvent

/**
 * Gtk4\GdkCrossingEvent::get_mode(): GdkCrossingMode
 *
 * Why the pointer crossed (normal motion, a grab, ...).
 */
ZEND_METHOD(Gtk4_GdkCrossingEvent, get_mode) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_EVENT;
  enum_to_php(GDK_TYPE_CROSSING_MODE, static_cast<gint>(gdk_crossing_event_get_mode(self)),
              return_value);
}

/**
 * Gtk4\GdkCrossingEvent::get_detail(): GdkNotifyType
 *
 * The surface hierarchy relation of the crossing.
 */
ZEND_METHOD(Gtk4_GdkCrossingEvent, get_detail) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_EVENT;
  enum_to_php(GDK_TYPE_NOTIFY_TYPE, static_cast<gint>(gdk_crossing_event_get_detail(self)),
              return_value);
}

/**
 * Gtk4\GdkCrossingEvent::get_focus(): bool
 *
 * True when the surface has (or had) keyboard focus.
 */
ZEND_METHOD(Gtk4_GdkCrossingEvent, get_focus) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_EVENT;
  RETURN_BOOL(gdk_crossing_event_get_focus(self));
}

// ---------------------------------------------------------------- GdkFocusEvent

/**
 * Gtk4\GdkFocusEvent::get_in(): bool
 *
 * True when focus came in, false when it left.
 */
ZEND_METHOD(Gtk4_GdkFocusEvent, get_in) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_EVENT;
  RETURN_BOOL(gdk_focus_event_get_in(self));
}

// ---------------------------------------------------------------- GdkTouchEvent

/**
 * Gtk4\GdkTouchEvent::get_emulating_pointer(): bool
 *
 * True when this touch sequence also drives the pointer.
 */
ZEND_METHOD(Gtk4_GdkTouchEvent, get_emulating_pointer) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_EVENT;
  RETURN_BOOL(gdk_touch_event_get_emulating_pointer(self));
}

// ---------------------------------------------------------------- GdkTouchpadEvent

/**
 * Gtk4\GdkTouchpadEvent::get_gesture_phase(): GdkTouchpadGesturePhase
 *
 * Where in the gesture this event sits (begin, update, end, cancel).
 */
ZEND_METHOD(Gtk4_GdkTouchpadEvent, get_gesture_phase) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_EVENT;
  enum_to_php(GDK_TYPE_TOUCHPAD_GESTURE_PHASE,
              static_cast<gint>(gdk_touchpad_event_get_gesture_phase(self)), return_value);
}

/**
 * Gtk4\GdkTouchpadEvent::get_n_fingers(): int
 *
 * How many fingers the gesture uses.
 */
ZEND_METHOD(Gtk4_GdkTouchpadEvent, get_n_fingers) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_EVENT;
  RETURN_LONG(static_cast<zend_long>(gdk_touchpad_event_get_n_fingers(self)));
}

/**
 * Gtk4\GdkTouchpadEvent::get_deltas(): array
 *
 * The movement [dx, dy] since the previous event of the gesture.
 */
ZEND_METHOD(Gtk4_GdkTouchpadEvent, get_deltas) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_EVENT;
  double dx = 0;
  double dy = 0;
  gdk_touchpad_event_get_deltas(self, &dx, &dy);
  pair_to_php(dx, dy, return_value);
}

/**
 * Gtk4\GdkTouchpadEvent::get_pinch_angle_delta(): float
 *
 * The rotation since the previous pinch event, in radians.
 */
ZEND_METHOD(Gtk4_GdkTouchpadEvent, get_pinch_angle_delta) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_EVENT;
  RETURN_DOUBLE(gdk_touchpad_event_get_pinch_angle_delta(self));
}

/**
 * Gtk4\GdkTouchpadEvent::get_pinch_scale(): float
 *
 * The scale of a pinch gesture relative to its start.
 */
ZEND_METHOD(Gtk4_GdkTouchpadEvent, get_pinch_scale) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_EVENT;
  RETURN_DOUBLE(gdk_touchpad_event_get_pinch_scale(self));
}

// ---------------------------------------------------------------- GdkPadEvent

/**
 * Gtk4\GdkPadEvent::get_axis_value(): array
 *
 * The [index, value] of the pad axis that moved (ring or strip events).
 */
ZEND_METHOD(Gtk4_GdkPadEvent, get_axis_value) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_EVENT;
  guint index = 0;
  double value = 0;
  gdk_pad_event_get_axis_value(self, &index, &value);
  array_init_size(return_value, 2);
  add_next_index_long(return_value, static_cast<zend_long>(index));
  add_next_index_double(return_value, value);
}

/**
 * Gtk4\GdkPadEvent::get_button(): int
 *
 * The pad button that was pressed or released.
 */
ZEND_METHOD(Gtk4_GdkPadEvent, get_button) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_EVENT;
  RETURN_LONG(static_cast<zend_long>(gdk_pad_event_get_button(self)));
}

/**
 * Gtk4\GdkPadEvent::get_group_mode(): array
 *
 * The [group, mode] of the pad event.
 */
ZEND_METHOD(Gtk4_GdkPadEvent, get_group_mode) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_EVENT;
  guint group = 0;
  guint mode = 0;
  gdk_pad_event_get_group_mode(self, &group, &mode);
  array_init_size(return_value, 2);
  add_next_index_long(return_value, static_cast<zend_long>(group));
  add_next_index_long(return_value, static_cast<zend_long>(mode));
}

// ---------------------------------------------------------------- GdkGrabBrokenEvent

/**
 * Gtk4\GdkGrabBrokenEvent::get_implicit(): bool
 *
 * True when the broken grab was implicit (a button press), false for an explicit one.
 */
ZEND_METHOD(Gtk4_GdkGrabBrokenEvent, get_implicit) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_EVENT;
  RETURN_BOOL(gdk_grab_broken_event_get_implicit(self));
}
