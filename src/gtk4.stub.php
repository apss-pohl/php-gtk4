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
     * Connect a handler to a signal (optionally detailed, e.g. "notify::title").
     *
     * The handler receives the emitting object first, then the signal's own
     * parameters (`notify` passes a {@see GParamSpec}). Capture extra context with a closure (`use ($x)`). Its return
     * value is used for signals that return one (e.g. `close-request` -> bool).
     *
     * What happens when the handler throws depends on {@see Gtk::set_exception_mode()}:
     * `ExceptionMode::Log` (default) reports it to the {@see Gtk::set_exception_handler()}
     * callable (or g_critical() on stderr) and GTK continues; `ExceptionMode::Rethrow`
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
     * @throws \ValueError If the signal does not exist or the argument count is wrong
     */
    public function emit(string $signal, mixed ...$args): mixed {}

    /** Disconnect a handler previously returned by connect(). No-op if already disconnected. */
    public function handler_disconnect(int $handler_id): void {}

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
    public function get_name(): string {}

    public function get_nick(): ?string {}

    public function get_blurb(): ?string {}

    /** GType name of the value, e.g. "gchararray", "gint", "GtkWindow". */
    public function get_value_type(): string {}

    /** GParamFlags bitmask (READABLE = 1, WRITABLE = 2, CONSTRUCT_ONLY = 8, ...). */
    public function get_flags(): int {}

    public function is_readable(): bool {}

    public function is_writable(): bool {}

    /** The property's default, converted like get_property(); null if the type is unsupported. */
    public function get_default_value(): mixed {}
}

/**
 * How a Throwable thrown inside a signal handler or GLib callback is handled.
 */
enum ExceptionMode: int
{
    /** Report via {@see Gtk::set_exception_handler()} (or g_critical()) and keep going. */
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

    /**
     * Install (or with null, remove) the callable that receives exceptions
     * thrown inside signal handlers and other callbacks. Signature:
     * `function (\Throwable $exception, string $origin): void`; $origin is the
     * signal name, or the installing method for non-signal callbacks. Called in
     * both exception modes, before a rethrow.
     */
    public static function set_exception_handler(?callable $handler): void {}

    public static function set_exception_mode(ExceptionMode $mode): void {}

    public static function get_exception_mode(): ExceptionMode {}

#if defined(PHPGTK_TESTING)
    /**
     * Test builds only (`--enable-gtk4-testing`, `FEATURES` has `testing=yes`): iterate the
     * default context $iterations times from C, blocking each time, the way GTK does inside
     * DnD or a portal call. Deliberately *not* a rethrow boundary - a Throwable parked in
     * {@see ExceptionMode::Rethrow} surfaces from the enclosing run(), as it would then.
     */
    public static function testing_iterate_nested(int $iterations): void {}
#endif
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

    /** Remove an idle/timeout source; false if it was already gone. */
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
    public function __construct() {}

    /**
     * Run until {@see quit()}. Reentrant runs are refused.
     *
     * @throws \LogicException If this loop is already running
     */
    public function run(): void {}

    public function quit(): void {}

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
    public function __construct(mixed $value = null) {}

    public function get_value(): mixed {}

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

    public function equal(GdkRGBA $other): bool {}

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
    public function __construct(int $x = 0, int $y = 0, int $width = 0, int $height = 0) {}

    /** The overlap with $other, or null if they do not intersect. */
    public function intersect(GdkRectangle $other): ?GdkRectangle {}

    /** The smallest rectangle containing both. */
    public function union(GdkRectangle $other): GdkRectangle {}

    public function contains_point(int $x, int $y): bool {}

    public function equal(GdkRectangle $other): bool {}
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
    public function set_source_rgb(float $red, float $green, float $blue): void {}

    public function set_source_rgba(float $red, float $green, float $blue, float $alpha): void {}

    public function set_source_color(GdkRGBA $color): void {}

    public function set_line_width(float $width): void {}

    public function move_to(float $x, float $y): void {}

    public function line_to(float $x, float $y): void {}

    public function rectangle(float $x, float $y, float $width, float $height): void {}

    /** Angles in radians. */
    public function arc(float $xc, float $yc, float $radius, float $angle1, float $angle2): void {}

    public function close_path(): void {}

    public function fill(): void {}

    public function fill_preserve(): void {}

    public function stroke(): void {}

    public function stroke_preserve(): void {}

    /** Paint the current source everywhere within the clip. */
    public function paint(): void {}

    public function save(): void {}

    public function restore(): void {}

    public function translate(float $tx, float $ty): void {}

    public function scale(float $sx, float $sy): void {}

    public function rotate(float $angle): void {}

    public function set_font_size(float $size): void {}

    public function show_text(string $text): void {}
}

