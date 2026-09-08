<?php


/**
 * php-gtk4 API declaration - the single source of truth.
 *
 * gen/gen_stub.php turns this file into gtk4_arginfo.h next to it (class entries,
 * method tables, typed arginfo); gen/ide-stub.php turns it into stubs/gtk4.php for
 * IDEs (same declarations with dummy bodies). Never edit those two by hand;
 * regenerate with `./ci.sh --only=stubs --fix`.
 *
 * PHP class names equal GType names; method names equal the GTK C function
 * names with the type prefix stripped (gtk_window_set_title -> set_title).
 *
 * @generate-class-entries
 * @generate-legacy-arginfo 80400
 */

namespace Gtk4;

/**
 * Extension version, version_compare()-friendly.
 * @var string
 */
const VERSION = '0.1.0-dev';

/**
 * "built <date>, git <hash>" of the loaded binary.
 * @var string
 * @cvalue PHPGTK_BUILD_INFO
 */
const BUILD_INFO = UNKNOWN;

/**
 * Compiled-in optional features, e.g. "webkit=no".
 * @var string
 * @cvalue PHPGTK_BUILD_FEATURES
 */
const FEATURES = UNKNOWN;

/**
 * Root handle for any GObject.
 *
 * A PHP object is a handle that owns one reference on the C object. Wrapping
 * the same C object twice yields the same PHP object (`===` holds). Handles
 * cannot be cloned or serialized. GObject properties are also readable and
 * writable as PHP properties (`$window->title`).
 *
 * @link https://docs.gtk.org/gobject/class.Object.html
 * @not-serializable
 */
class GObject
{
    /**
     * A plain GObject - or, on a PHP subclass, an instance of that class' own GType (the way to
     * implement a GTK interface such as {@see GListModel} in PHP: `class M extends GObject
     * implements GListModel`).
     */
    public function __construct() {}

    /**
     * Connect a handler to a signal (optionally detailed, e.g. "notify::title").
     *
     * The handler receives the emitting object first, then the signal's own
     * parameters (`notify` passes a {@see GParamSpec}). Capture extra context with a closure (`use ($x)`). Its return
     * value is used for signals that return one (e.g. `close-request` -> bool).
     *
     * What happens when the handler throws depends on {@see Gtk::set_exception_mode()}:
     * `ExceptionMode::Log` (default) reports it to the {@see Gtk::set_exception_handler()}
     * callable (or an `E_WARNING`) and GTK continues; `ExceptionMode::Rethrow`
     * stops any running main loop and rethrows it to the PHP code that triggered the
     * emission (or from {@see GtkApplication::run()} / {@see GMainLoop::run()}).
     *
     * @return int Handler id for {@see handler_disconnect()}
     * @throws \ValueError If the signal does not exist on this object
     */
    public function connect(string $signal, callable $handler): int {}

    /** Same as {@see connect()} but the handler runs after the default class handler. */
    public function connect_after(string $signal, callable $handler): int {}

    /**
     * Emit a signal on this object with the given arguments (converted to the
     * signal's parameter types) and return the signal's return value, if any.
     *
     * The class handler runs too, so this is GTK's own code being driven with PHP's values:
     * an `int` that follows a `string` parameter (`insert-text`'s length) is bounded by that
     * string, but a signal whose handler needs the object in a state PHP cannot check
     * (`realize`, `map`) is not guarded beyond what GTK asserts.
     *
     * @throws \ValueError If the signal does not exist, the argument count is wrong, or a
     *                     length exceeds the string before it
     */
    public function emit(string $signal, mixed ...$args): mixed {}

    /** Disconnect a handler previously returned by connect(). No-op if already disconnected. */
    public function handler_disconnect(int $handler_id): void {}

    /**
     * Every signal this object can emit - its class', its ancestors' and its interfaces' -
     * keyed by name: the parameter GTypes in order (`gchararray`, `gint`, `GtkWidget`, ...),
     * the return GType or null, whether it is an action signal (meant to be emitted by the
     * application, `activate`, `move-cursor`) and whether it takes a detail (`notify::title`).
     *
     * @return array<string, array{params: list<string>, return: ?string, action: bool, detailed: bool}>
     */
    public function list_signals(): array {}

    /**
     * Read a GObject property by name, converted to the matching PHP type.
     *
     * @throws \ValueError If the property does not exist
     */
    public function get_property(string $name): mixed {}

    /**
     * Write a GObject property by name; the value is converted to the property's GType.
     *
     * @throws \ValueError If the property does not exist
     * @throws \TypeError If the value cannot be converted
     */
    public function set_property(string $name, mixed $value): void {}
}

/**
 * Metadata of a GObject property, as passed to `notify` handlers.
 *
 * @link https://docs.gtk.org/gobject/class.ParamSpec.html
 * @not-serializable
 */
final class GParamSpec
{
    /** GTK's property name with dashes, e.g. `default-width`. */
    public function get_name(): string {}

    /** Short human-readable name, or null. */
    public function get_nick(): ?string {}

    /** Longer description, or null. */
    public function get_blurb(): ?string {}

    /** GType name of the value, e.g. "gchararray", "gint", "GtkWindow". */
    public function get_value_type(): string {}

    /** GParamFlags bitmask (READABLE = 1, WRITABLE = 2, CONSTRUCT_ONLY = 8, ...). */
    public function get_flags(): int {}

    /** Whether `get_property()` / `$obj->prop` may read it (G_PARAM_READABLE). */
    public function is_readable(): bool {}

    /** Whether `set_property()` / `$obj->prop = ...` may write it (G_PARAM_WRITABLE). */
    public function is_writable(): bool {}

    /** The property's default, converted like get_property(); null if the type is unsupported. */
    public function get_default_value(): mixed {}
}

/**
 * How a Throwable thrown inside a signal handler or GLib callback is handled.
 */
enum ExceptionMode: int
{
    /** Report via {@see Gtk::set_exception_handler()} (or an `E_WARNING`) and keep going. */
    case Log = 0;
    /**
     * Stop any running {@see GtkApplication::run()} / {@see GMainLoop::run()} and rethrow the
     * Throwable to the PHP code that triggered the callback (the emitting method call, or
     * the run() call). Remaining PHP callbacks of the same emission are skipped.
     */
    case Rethrow = 1;
}

/**
 * Static entry points: initialisation and the callback exception policy.
 * The main loop lives in {@see GtkApplication} (preferred) and {@see GMainLoop}.
 */
final class Gtk
{
    /** Initialise GTK (gtk_init_check). Returns false if no display is available. */
    public static function init(): bool {}

    /** The major version of the GTK library in use - 4 here (gtk_get_major_version). */
    public static function get_major_version(): int {}

    /** The minor version of the GTK library in use (gtk_get_minor_version). */
    public static function get_minor_version(): int {}

    /** The micro version of the GTK library in use (gtk_get_micro_version). */
    public static function get_micro_version(): int {}

    /**
     * Null when the GTK in use is compatible with the given version, otherwise a string saying
     * how it is not (gtk_check_version): older than what was asked for, or a different major
     * version, which is not binary compatible either way.
     *
     * This is the runtime library's answer, not what the extension was built against, so it is
     * what a script should ask before using something a later GTK added.
     */
    public static function check_version(int $required_major, int $required_minor, int $required_micro): ?string {}

    /**
     * Install (or with null, remove) the callable that receives exceptions
     * thrown inside signal handlers and other callbacks. Signature:
     * `function (\Throwable $exception, string $origin): void`; $origin is the
     * signal name, or the installing method for non-signal callbacks. Called in
     * both exception modes, before a rethrow.
     */
    public static function set_exception_handler(?callable $handler): void {}

    /** What a Throwable escaping a handler does: `Log` (report, GTK continues) or `Rethrow` (stop loops, propagate). */
    public static function set_exception_mode(ExceptionMode $mode): void {}

    /** The current mode; `Log` by default. */
    public static function get_exception_mode(): ExceptionMode {}

    /**
     * Add a style provider (a {@see GtkCssProvider}) to every widget on $display
     * (gtk_style_context_add_provider_for_display - the function outlived the
     * GtkStyleContext class it is named after).
     *
     * The highest priority wins per property; use {@see GtkStyleProviderPriority}
     * for $priority. Providers stay attached until removed, and reloading the
     * provider's CSS restyles everything immediately.
     */
    public static function add_provider_for_display(
        GdkDisplay $display,
        GtkStyleProvider $provider,
        int $priority = GtkStyleProviderPriority::APPLICATION,
    ): void {}

    /** Detach a provider added with {@see add_provider_for_display()}; unknown providers are ignored. */
    public static function remove_provider_for_display(GdkDisplay $display, GtkStyleProvider $provider): void {}

    /**
     * A test hook, not part of the supported surface: iterate the default context $iterations
     * times from C, blocking each time, the way GTK does inside DnD or a portal call.
     * Deliberately *not* a rethrow boundary - a Throwable parked in
     * {@see ExceptionMode::Rethrow} surfaces from the enclosing run(), as it would then.
     */
    public static function testing_iterate_nested(int $iterations): void {}

    /**
     * A test hook, not part of the supported surface: run `g_object_run_dispose()` on $object
     * from C while PHP still holds it, the way GTK guts a widget that a C owner destroys. The
     * handle turns *disposed*: method calls and passing it as an argument throw an `Error` from
     * then on.
     */
    public static function testing_run_dispose(GtkWidget $object): void {}
}

/**
 * GLib main-context helpers (idle and timeout sources on the default context).
 * Callbacks return bool: true keeps the source, false removes it (missing/null = false).
 */
final class GLib
{
    /** @return int Source id for {@see source_remove()} */
    public static function idle_add(callable $callback): int {}

    /** @return int Source id for {@see source_remove()} */
    public static function timeout_add(int $interval_ms, callable $callback): int {}

    /**
     * Watch a socket stream on the default main context: `$callback($stream, int $condition)`
     * runs whenever one of the {@see GIOCondition} bits in $condition is met, and keeps the
     * watch by returning true. This is how a socket joins the GTK main loop instead of being
     * polled between manual iterations. $stream is a stream resource over a socket
     * (`stream_socket_client()`, `stream_socket_pair()`, or an ext-sockets `\Socket` through
     * `socket_export_stream()`); the descriptor stays PHP's - closing the stream ends the watch
     * with a `HUP`/`NVAL` condition.
     *
     * @param resource $stream
     * @return int Source id for {@see source_remove()}
     * @throws \TypeError When $stream is not an open stream resource
     * @throws \ValueError When the stream has no socket descriptor or $condition has no valid bit
     */
    public static function io_add_watch(mixed $stream, int $condition, callable $callback): int {}

    /** Remove an idle/timeout/I/O source; false if it was already gone. */
    public static function source_remove(int $source_id): bool {}

    /**
     * Run one iteration of the default main context (g_main_context_iteration):
     * dispatch what is ready, optionally blocking until something is. Returns
     * true if any source was dispatched. Lets a script pump events without
     * handing control to run(); in {@see ExceptionMode::Rethrow} a Throwable
     * raised by a dispatched callback propagates from this call.
     */
    public static function main_context_iteration(bool $may_block = false): bool {}
}

/**
 * A bare GLib main loop on the default context. Prefer {@see GtkApplication}
 * for applications; use this for scripts and tests that just need to pump
 * events.
 *
 * @link https://docs.gtk.org/glib/struct.MainLoop.html
 * @not-serializable
 */
final class GMainLoop
{
    /** A loop on the default main context (`GLib::idle_add()` / `timeout_add()` sources run in it). */
    public function __construct() {}

    /**
     * Run until {@see quit()}. Reentrant runs are refused.
     *
     * @throws \LogicException If this loop is already running
     */
    public function run(): void {}

    /** Make a running `run()` return; a no-op when not running. */
    public function quit(): void {}

    /** True between `run()` and the `quit()` that ends it. */
    public function is_running(): bool {}
}

/**
 * A GLib error (`GError`) surfaced as an exception. Every method whose C
 * counterpart takes a `GError **` throws this instead of returning false/null.
 * `getCode()` is GLib's error code within {@see getDomain()}.
 */
class GError extends \RuntimeException
{
    protected string $domain = '';

    /**
     * Error domain (quark name), e.g. "g-io-error-quark", "gdk-texture-error-quark".
     * camelCase on purpose: sits next to the inherited getCode()/getMessage().
     */
    public function getDomain(): string {}
}

/**
 * A GObject that carries an arbitrary PHP value, so PHP data can live where
 * GTK expects GObjects - above all in a {@see GListStore} feeding list views.
 * The value is held by reference (objects and arrays keep their identity);
 * it is released when the last handle and every list holding the item are gone.
 *
 * ```php
 * $store = new GListStore(PhpValue::class);
 * $store->append(new PhpValue(['name' => 'Ada']));
 * $store->get_item(0)->get_value()['name'];   // "Ada"
 * ```
 *
 * @not-serializable
 */
final class PhpValue extends GObject
{
    /** A GObject carrying any PHP value, so PHP data can live in a `GListStore`. */
    public function __construct(mixed $value = null) {}

    /** The carried value. */
    public function get_value(): mixed {}

    /** Replace the carried value. */
    public function set_value(mixed $value): void {}
}

/**
 * An RGBA colour (a boxed value type: cloneable, compared by value).
 *
 * @property float $red   0.0 .. 1.0
 * @property float $green 0.0 .. 1.0
 * @property float $blue  0.0 .. 1.0
 * @property float $alpha 0.0 .. 1.0
 *
 * @link https://docs.gtk.org/gdk4/struct.RGBA.html
 * @not-serializable
 */
final class GdkRGBA
{
    /**
     * @param string|null $css A CSS colour (`#rrggbb`, `rgba(...)`, a name); null = transparent black
     * @throws \ValueError If the string cannot be parsed
     */
    public function __construct(?string $css = null) {}

    /** Parse a CSS colour into this value; false if it is not valid. */
    public function parse(string $css): bool {}

    /** CSS representation, e.g. `rgb(255,0,0)` or `rgba(255,0,0,0.5)`. */
    public function to_string(): string {}

    /** Component-wise equality (`==` compares handles by value too). */
    public function equal(GdkRGBA $other): bool {}

    /** True when alpha is 1. */
    public function is_opaque(): bool {}
}

/**
 * An integer rectangle (a boxed value type: cloneable, compared by value).
 *
 * @property int $x
 * @property int $y
 * @property int $width
 * @property int $height
 *
 * @link https://docs.gtk.org/gdk4/struct.Rectangle.html
 * @not-serializable
 */
final class GdkRectangle
{
    /** Origin and size in pixels. */
    public function __construct(int $x = 0, int $y = 0, int $width = 0, int $height = 0) {}

    /** The overlap with $other, or null if they do not intersect. */
    public function intersect(GdkRectangle $other): ?GdkRectangle {}

    /** The smallest rectangle containing both. */
    public function union(GdkRectangle $other): GdkRectangle {}

    /** Whether the point lies inside the rectangle. */
    public function contains_point(int $x, int $y): bool {}

    /** Position and size equal. */
    public function equal(GdkRectangle $other): bool {}
}

/**
 * A rectangle with a size per corner (a boxed value type: cloneable, compared by value) - what
 * {@see GskRoundedClipNode}, {@see GskBorderNode}, the shadow nodes and
 * {@see GtkSnapshot::push_rounded_clip()} take. GSK gives the struct no GType; the binding
 * registers one. The GSK calls that rewrite the rectangle in place (normalize, offset, shrink)
 * answer with a copy here, like graphene's do: a value handle never changes under you.
 *
 * @link https://docs.gtk.org/gsk4/struct.RoundedRect.html
 * @not-serializable
 */
final class GskRoundedRect
{
    /** A rectangle with a (circular) radius per corner; a radius of 0.0 keeps that corner square. */
    public function __construct(
        GrapheneRect $bounds,
        float $top_left = 0.0,
        float $top_right = 0.0,
        float $bottom_right = 0.0,
        float $bottom_left = 0.0,
    ) {}

    /** The rectangle without its corners. */
    public function get_bounds(): GrapheneRect {}

    /** The horizontal and vertical radius of one corner. */
    public function get_corner(GskCorner $corner): GrapheneSize {}

    /** Whether every corner is square (all radii 0.0). */
    public function is_rectilinear(): bool {}

    /** Whether the point lies inside the rounded rectangle. */
    public function contains_point(GraphenePoint $point): bool {}

    /** Whether the whole rectangle lies inside the rounded rectangle. */
    public function contains_rect(GrapheneRect $rect): bool {}

    /** Whether the rectangle overlaps the rounded rectangle anywhere. */
    public function intersects_rect(GrapheneRect $rect): bool {}

    /** A copy with a non-negative size and corners no larger than the sides allow. */
    public function normalize(): GskRoundedRect {}

    /** A copy moved by ($dx, $dy). */
    public function offset(float $dx, float $dy): GskRoundedRect {}

    /**
     * A copy inset by the given amounts on each side, the corners shrinking with it (negative
     * values grow it).
     */
    public function shrink(float $top, float $right, float $bottom, float $left): GskRoundedRect {}

    /** Same bounds and the same four corners (what `==` compares too). */
    public function equal(GskRoundedRect $other): bool {}
}

/**
 * An input event, as GTK 4 delivers it to event controllers: an opaque, refcounted handle with
 * typed getters (there are no fields to copy and no `new`). The class tells the kind — a
 * {@see GdkKeyEvent}, {@see GdkButtonEvent}, ... — and {@see get_event_type()} the exact type.
 * Comes from {@see GtkEventController::get_current_event()}, {@see GtkGesture::get_last_event()}
 * and the `event` signal of {@see GtkEventControllerLegacy}.
 *
 * @link https://docs.gtk.org/gdk4/class.Event.html
 * @not-serializable
 */
class GdkEvent
{
    /** What kind of event this is (also visible in the handle's class). */
    public function get_event_type(): GdkEventType {}

    /** The event's timestamp in milliseconds (server time, 0 when the event carries none). */
    public function get_time(): int {}

    /**
     * The keyboard modifiers and mouse buttons held down when the event happened
     * ({@see GdkModifierType} flags).
     */
    public function get_modifier_state(): int {}

    /**
     * The pointer position [x, y] in surface coordinates, or null for events that have none
     * (key and focus events).
     *
     * @return array{float, float}|null
     */
    public function get_position(): ?array {}

    /** True for a pointer event that was synthesised from a touch sequence. */
    public function get_pointer_emulated(): bool {}

    /**
     * True when this event is the platform's "open a context menu" gesture (the secondary
     * button, Control+click on macOS).
     */
    public function triggers_context_menu(): bool {}

    /** The display the event came from. */
    public function get_display(): ?GdkDisplay {}
}

/**
 * A key press or release.
 *
 * @link https://docs.gtk.org/gdk4/class.KeyEvent.html
 * @not-serializable
 */
final class GdkKeyEvent extends GdkEvent
{
    /** The key symbol (a `GDK_KEY_*` value, e.g. 65307 for Escape). */
    public function get_keyval(): int {}

    /** The hardware key code. */
    public function get_keycode(): int {}

    /**
     * The modifiers that were used up producing the keyval ({@see GdkModifierType} flags) -
     * Shift for "A", for instance - and so must not count as part of an accelerator.
     */
    public function get_consumed_modifiers(): int {}

    /** The keyboard layout (group) the key was pressed in. */
    public function get_layout(): int {}

    /** The shift level of the key. */
    public function get_level(): int {}

    /** True when the key is itself a modifier (Shift, Control, ...). */
    public function is_modifier(): bool {}

    /**
     * Whether the event matches an accelerator of $keyval + $modifiers ({@see GdkModifierType}
     * flags): exactly, partially (layout-independent) or not at all.
     */
    public function matches(int $keyval, int $modifiers): GdkKeyMatch {}

    /**
     * The [keyval, modifiers] an accelerator would have to match this event, or null when the
     * event cannot be matched (a modifier key on its own).
     *
     * @return array{int, int}|null
     */
    public function get_match(): ?array {}
}

/**
 * A mouse button press or release.
 *
 * @link https://docs.gtk.org/gdk4/class.ButtonEvent.html
 * @not-serializable
 */
final class GdkButtonEvent extends GdkEvent
{
    /** The mouse button (1 = primary, 2 = middle, 3 = secondary). */
    public function get_button(): int {}
}

/**
 * A scroll wheel or smooth-scroll event.
 *
 * @link https://docs.gtk.org/gdk4/class.ScrollEvent.html
 * @not-serializable
 */
final class GdkScrollEvent extends GdkEvent
{
    /** The scroll direction; `Smooth` means {@see get_deltas()} carries the amount. */
    public function get_direction(): GdkScrollDirection {}

    /**
     * The scroll amount [dx, dy] of a smooth scroll event.
     *
     * @return array{float, float}
     */
    public function get_deltas(): array {}

    /** What the deltas are measured in (wheel clicks or surface pixels). */
    public function get_unit(): GdkScrollUnit {}

    /** True for the event that ends a smooth scroll sequence (fingers lifted). */
    public function is_stop(): bool {}
}

/**
 * The pointer entering or leaving a surface.
 *
 * @link https://docs.gtk.org/gdk4/class.CrossingEvent.html
 * @not-serializable
 */
final class GdkCrossingEvent extends GdkEvent
{
    /** Why the pointer crossed (normal motion, a grab, ...). */
    public function get_mode(): GdkCrossingMode {}

    /** The surface hierarchy relation of the crossing. */
    public function get_detail(): GdkNotifyType {}

    /** True when the surface has (or had) keyboard focus. */
    public function get_focus(): bool {}
}

/**
 * Keyboard focus entering or leaving a surface.
 *
 * @link https://docs.gtk.org/gdk4/class.FocusEvent.html
 * @not-serializable
 */
final class GdkFocusEvent extends GdkEvent
{
    /** True when focus came in, false when it left. */
    public function get_in(): bool {}
}

/**
 * A touch-screen contact.
 *
 * @link https://docs.gtk.org/gdk4/class.TouchEvent.html
 * @not-serializable
 */
final class GdkTouchEvent extends GdkEvent
{
    /** True when this touch sequence also drives the pointer. */
    public function get_emulating_pointer(): bool {}
}

/**
 * A touchpad gesture (pinch, swipe, hold).
 *
 * @link https://docs.gtk.org/gdk4/class.TouchpadEvent.html
 * @not-serializable
 */
final class GdkTouchpadEvent extends GdkEvent
{
    /** Where in the gesture this event sits (begin, update, end, cancel). */
    public function get_gesture_phase(): GdkTouchpadGesturePhase {}

    /** How many fingers the gesture uses. */
    public function get_n_fingers(): int {}

    /**
     * The movement [dx, dy] since the previous event of the gesture.
     *
     * @return array{float, float}
     */
    public function get_deltas(): array {}

    /** The rotation since the previous pinch event, in radians. */
    public function get_pinch_angle_delta(): float {}

    /** The scale of a pinch gesture relative to its start. */
    public function get_pinch_scale(): float {}
}

/**
 * A drawing-tablet pad event (buttons, rings and strips).
 *
 * @link https://docs.gtk.org/gdk4/class.PadEvent.html
 * @not-serializable
 */
final class GdkPadEvent extends GdkEvent
{
    /**
     * The [index, value] of the pad axis that moved (ring or strip events).
     *
     * @return array{int, float}
     */
    public function get_axis_value(): array {}

    /** The pad button that was pressed or released. */
    public function get_button(): int {}

    /**
     * The [group, mode] of the pad event.
     *
     * @return array{int, int}
     */
    public function get_group_mode(): array {}
}

/**
 * A pointer or keyboard grab that was broken by another grab.
 *
 * @link https://docs.gtk.org/gdk4/class.GrabBrokenEvent.html
 * @not-serializable
 */
final class GdkGrabBrokenEvent extends GdkEvent
{
    /** True when the broken grab was implicit (a button press), false for an explicit one. */
    public function get_implicit(): bool {}
}

/**
 * The identity of one touch point (or the pointer: null) for the duration of a gesture -
 * what {@see GtkGesture::get_point()}, {@see GtkGesture::get_last_event()} and the gesture
 * signals take and hand out. Opaque and compared by identity: the same sequence is the same
 * handle (`===`) while PHP holds it. Never constructed.
 *
 * @link https://docs.gtk.org/gdk4/struct.EventSequence.html
 * @not-serializable
 */
final class GdkEventSequence
{
}

/**
 * What an I/O watch ({@see GLib::io_add_watch()}) waits for, and what its callback is told
 * happened: readable, writable, urgent data, error, hang-up, invalid descriptor. OR-able.
 *
 * @link https://docs.gtk.org/glib/flags.IOCondition.html
 */
final class GIOCondition
{
    /** There is data to read. */
    public const int IN = 1;
    /** There is urgent data to read. */
    public const int PRI = 2;
    /** Data can be written without blocking. */
    public const int OUT = 4;
    /** An error condition. */
    public const int ERR = 8;
    /** Hung up (the connection was closed). */
    public const int HUP = 16;
    /** Invalid request: the descriptor is not open. */
    public const int NVAL = 32;
}

/**
 * A cairo drawing context, as handed to {@see GtkDrawingArea::set_draw_func()}
 * callbacks. Only valid during the callback. Minimal surface for now; grows
 * with the generator.
 *
 * @link https://www.cairographics.org/manual/cairo-cairo-t.html
 * @not-serializable
 */
final class CairoContext
{
    /** Opaque source colour, components 0..1. */
    public function set_source_rgb(float $red, float $green, float $blue): void {}

    /** Translucent source colour, components 0..1. */
    public function set_source_rgba(float $red, float $green, float $blue, float $alpha): void {}

    /** Source colour from a `GdkRGBA`. */
    public function set_source_color(GdkRGBA $color): void {}

    /** Width for `stroke()` in user units. */
    public function set_line_width(float $width): void {}

    /** Begin a new sub-path at the point. */
    public function move_to(float $x, float $y): void {}

    /** Straight line from the current point. */
    public function line_to(float $x, float $y): void {}

    /** Add a closed rectangular sub-path. */
    public function rectangle(float $x, float $y, float $width, float $height): void {}

    /** Angles in radians. */
    public function arc(float $xc, float $yc, float $radius, float $angle1, float $angle2): void {}

    /** Line back to the start of the current sub-path. */
    public function close_path(): void {}

    /** Fill the current path and clear it. */
    public function fill(): void {}

    /** Fill the current path, keeping it for a following `stroke()`. */
    public function fill_preserve(): void {}

    /** Stroke the current path with the line width and clear it. */
    public function stroke(): void {}

    /** Stroke the current path, keeping it. */
    public function stroke_preserve(): void {}

    /** Paint the current source everywhere within the clip. */
    public function paint(): void {}

    /** Push the drawing state (source, line width, transformation, clip). */
    public function save(): void {}

    /** Pop the state pushed by `save()`. */
    public function restore(): void {}

    /** Move the user-space origin. */
    public function translate(float $tx, float $ty): void {}

    /** Scale user space. */
    public function scale(float $sx, float $sy): void {}

    /** Rotate user space by the angle in radians. */
    public function rotate(float $angle): void {}

    /** Font size in user units for `show_text()`. */
    public function set_font_size(float $size): void {}

    /** Draw the text at the current point with the toy font API. */
    public function show_text(string $text): void {}

    /**
     * Use $surface as the source pattern, its origin at ($x, $y).
     *
     * The cairo counterpart of GTK 3's `gdk_cairo_set_source_pixbuf()`: paint an image into a
     * draw func by downloading a {@see GdkTexture} and setting it as the source.
     */
    public function set_source_surface(CairoSurface $surface, float $x = 0.0, float $y = 0.0): void {}
}

/**
 * A cairo image surface: pixels a {@see CairoContext} can paint from, and what
 * {@see GdkTexture::download()} answers with. Pass one to
 * {@see CairoContext::set_source_surface()} to draw an image inside a
 * {@see GtkDrawingArea} draw func.
 *
 * @link https://www.cairographics.org/manual/cairo-cairo-surface-t.html
 * @not-serializable
 */
final class CairoSurface
{
    /** The width in pixels. Only an image surface has one. */
    public function get_width(): int {}

    /** The height in pixels. Only an image surface has one. */
    public function get_height(): int {}

    /** Write the surface out as a PNG file. */
    public function write_to_png(string $filename): void {}
}

/**
 * Priorities for {@see Gtk::add_provider_for_display()}, in the order GTK applies
 * them: a property set by a higher-priority provider wins.
 *
 * @link https://docs.gtk.org/gtk4/index.html#constants
 */
final class GtkStyleProviderPriority
{
    /** Below the theme: defaults an application ships that the theme may override. */
    public const int FALLBACK = 1;
    /** The current theme. */
    public const int THEME = 200;
    /** GtkSettings, e.g. gtk-key-theme-name. */
    public const int SETTINGS = 400;
    /** Where an application's own stylesheet belongs (the default). */
    public const int APPLICATION = 600;
    /** ~/.config/gtk-4.0/gtk.css; above everything an application loads. */
    public const int USER = 800;
}

/**
 * The part of a stylesheet a `parsing-error` refers to, as passed to handlers of
 * {@see GtkCssProvider}'s `parsing-error` signal together with a {@see GError}.
 *
 * @link https://docs.gtk.org/gtk4/struct.CssSection.html
 * @not-serializable
 */
final class GtkCssSection
{
    /** "<file>:<start line>:<start column>-<end line>:<end column>", 1-based, as GTK prints it. */
    public function to_string(): string {}

    /**
     * The section this one is nested in, or null when there is none - which is what
     * GTK 4.14 reports for every parsing error, nested or not.
     */
    public function get_parent(): ?GtkCssSection {}

    /**
     * Where the section starts, as GtkCssLocation's fields (all 0-based counts from the
     * start of the document; `lines` is the line number, `line_chars` the column).
     *
     * @return array{bytes: int, chars: int, lines: int, line_bytes: int, line_chars: int}
     */
    public function get_start_location(): array {}

    /**
     * Where the section ends; same shape as {@see get_start_location()}.
     *
     * @return array{bytes: int, chars: int, lines: int, line_bytes: int, line_chars: int}
     */
    public function get_end_location(): array {}
}
