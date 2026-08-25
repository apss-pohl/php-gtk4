<?php


/**
 * php-gtk4 API declaration - the single source of truth.
 *
 * gen_stub.php turns this file into src/gtk4_arginfo.h (class entries, method
 * tables, typed arginfo); gen/ide-stub.php turns it into stubs/gtk4.php for
 * IDEs (same declarations with dummy bodies). Never edit those two by hand.
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
const VERSION = '0.1.0';

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
     * parameters, then any extra `$userData` passed here. Its return value is
     * used for signals that return one (e.g. `close-request` -> bool).
     *
     * A Throwable thrown inside the handler is NOT propagated to the caller
     * of the emitting GTK function; it is reported to the callable installed
     * with {@see Gtk::set_exception_handler()} (or g_critical() on stderr).
     *
     * @return int Handler id for {@see handler_disconnect()}
     * @throws \ValueError If the signal does not exist on this object
     */
    public function connect(string $signal, callable $handler, mixed ...$userData): int {}

    /** Same as {@see connect()} but the handler runs after the default class handler. */
    public function connect_after(string $signal, callable $handler, mixed ...$userData): int {}

    /** Disconnect a handler previously returned by connect(). No-op if already disconnected. */
    public function handler_disconnect(int $handlerId): void {}

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
 * Static entry points: initialisation, the main loop and the callback
 * exception handler.
 */
final class Gtk
{
    /** Initialise GTK (gtk_init_check). Returns false if no display is available. */
    public static function init(): bool {}

    /**
     * Run the default GLib main loop until {@see main_quit()} is called.
     * If main_quit() was already called before, returns immediately.
     *
     * @throws \LogicException If the loop is already running
     */
    public static function main(): void {}

    /** Stop the loop started by {@see main()}; may be called before main(). */
    public static function main_quit(): void {}

    /**
     * Install (or with null, remove) the callable that receives exceptions
     * thrown inside signal handlers and other callbacks.
     *
     * The handler signature is `function (\Throwable $exception, string $origin): void`;
     * $origin is the signal name, or the installing method for non-signal callbacks.
     */
    public static function set_exception_handler(?callable $handler): void {}
}

/**
 * A toplevel window.
 *
 * GObject properties are also available as PHP properties (dashes become
 * underscores). The generator will list every property here from the GIR;
 * until then only the ones the tests and the example use are declared.
 *
 * @property ?string $title
 * @property int $default_width
 * @property int $default_height
 * @property bool $resizable
 * @property bool $modal
 * @property bool $visible
 * @property ?GtkWindow $transient_for
 * @property ?GObject $child
 *
 * @link https://docs.gtk.org/gtk4/class.Window.html
 * @not-serializable
 */
class GtkWindow extends GObject
{
    public function __construct() {}

    public function set_title(?string $title): void {}

    public function get_title(): ?string {}

    /** Default size in pixels; -1 to unset one dimension. */
    public function set_default_size(int $width, int $height): void {}

    /**
     * Set (or with null, remove) the single child widget.
     *
     * @param GObject|null $child A GtkWidget
     */
    public function set_child(?GObject $child): void {}

    /** Show the window and bring it to the front. */
    public function present(): void {}

    /** Request the window to close (emits close-request; a handler returning true cancels). */
    public function close(): void {}

    /**
     * Drop GTK's reference to the toplevel and unrealize it. The PHP handle
     * stays valid; `destroy` is emitted when the last handle is released.
     */
    public function destroy(): void {}
}
