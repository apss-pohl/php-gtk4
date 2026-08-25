<?php

declare(strict_types=1);

/**
 * php-gtk4 IDE stubs.
 *
 * Declarations only - this file is never executed. Bodies contain a dummy
 * `unset()` of each parameter and a placeholder `return` so IDE analysers do
 * not report unused parameters or missing return paths. It exists so that editors
 * (VS Code + Intelephense, PhpStorm, ...) know the classes, methods, parameter
 * and return types the `gtk4` extension provides. Keep it in sync with the
 * registration in src/ (tests/StubsTest.php checks classes and method names
 * against the loaded extension); it will be generated once gen/ exists.
 *
 * Everything lives in the `Gtk4` namespace (`use Gtk4\GtkWindow;`) so the
 * extension can be loaded next to php-gtk3. Class names equal GType names. Method names equal the GTK C function
 * names with the type prefix stripped (gtk_window_set_title -> set_title).
 *
 * @see PLAN.md
 */

namespace Gtk4;

/** Extension version, version_compare()-friendly. */
const PHPGTK_VERSION = '0.1.0';

/** "built <date>, git <hash>" of the loaded binary. */
const PHPGTK_BUILD_INFO = '';

/** Compiled-in optional features, e.g. "webkit=no". */
const PHPGTK_FEATURES = '';

/**
 * Root handle for any GObject.
 *
 * A PHP object is a handle that owns one reference on the C object. Wrapping
 * the same C object twice yields the same PHP object (`===` holds). Handles
 * cannot be cloned.
 *
 * @link https://docs.gtk.org/gobject/class.Object.html
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
     * @param string   $signal   Signal name, optionally with "::detail"
     * @param callable $handler  function (static $object, mixed ...$signalParams, mixed ...$userData): mixed
     * @param mixed    ...$userData Extra values appended to the handler arguments
     * @return int Handler id for {@see handler_disconnect()}
     * @throws \Exception If the signal does not exist on this object or $handler is not callable
     */
    public function connect(string $signal, callable $handler, mixed ...$userData): int
    {
        unset($signal);
        unset($handler);
        unset($userData);
        return 0;
    }

    /**
     * Same as {@see connect()} but the handler runs after the default class handler.
     *
     * @param mixed ...$userData
     */
    public function connect_after(string $signal, callable $handler, mixed ...$userData): int
    {
        unset($signal);
        unset($handler);
        unset($userData);
        return 0;
    }

    /** Disconnect a handler previously returned by connect(). No-op if already disconnected. */
    public function handler_disconnect(int $handlerId): void
    {
        unset($handlerId);
    }

    /**
     * Read a GObject property by name, converted to the matching PHP type
     * (int|float|bool|string|GObject|null).
     *
     * @throws \Exception If the property does not exist
     */
    public function get_property(string $name): mixed
    {
        unset($name);
        return null;
    }

    /**
     * Write a GObject property by name; the value is converted to the
     * property's GType.
     *
     * @throws \Exception If the property does not exist or the value cannot be converted
     */
    public function set_property(string $name, mixed $value): void
    {
        unset($name);
        unset($value);
    }
}

/**
 * Static entry points: initialisation, the main loop and the callback
 * exception handler.
 */
final class Gtk
{
    /**
     * Initialise GTK (gtk_init_check). Returns false if no display is available.
     */
    public static function init(): bool
    {
        return false;
    }

    /**
     * Run the default GLib main loop until {@see main_quit()} is called.
     * If main_quit() was already called before, returns immediately.
     *
     * @throws \Exception If the loop is already running
     */
    public static function main(): void {}

    /** Stop the loop started by {@see main()}; may be called before main(). */
    public static function main_quit(): void {}

    /**
     * Install (or with null, remove) the callable that receives exceptions
     * thrown inside signal handlers and other callbacks.
     *
     * @param null|callable(string $message, string $origin, int $code): void $handler
     *        $origin is the signal name, or the installing method for non-signal callbacks
     * @throws \Exception If $handler is neither null nor callable
     */
    public static function set_exception_handler(?callable $handler): void
    {
        unset($handler);
    }
}

/**
 * A toplevel window.
 *
 * @link https://docs.gtk.org/gtk4/class.Window.html
 */
class GtkWindow extends GObject
{
    public function __construct() {}

    public function set_title(string $title): void
    {
        unset($title);
    }

    public function get_title(): ?string
    {
        return null;
    }

    /** Default size in pixels; -1 to unset one dimension. */
    public function set_default_size(int $width, int $height): void
    {
        unset($width);
        unset($height);
    }

    /**
     * Set (or with null, remove) the single child widget.
     *
     * @param GObject|null $child A GtkWidget
     */
    public function set_child(?GObject $child): void
    {
        unset($child);
    }

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
