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
 * Horizontal / vertical alignment of a widget within its allocation.
 * (Case values are verified against GTK's GEnumClass when the extension loads.)
 *
 * @link https://docs.gtk.org/gtk4/enum.Align.html
 */
enum GtkAlign: int
{
    case Fill = 0;
    case Start = 1;
    case End = 2;
    case Center = 3;
    case BaselineFill = 4;
    case BaselineCenter = 5;
}

/**
 * @link https://docs.gtk.org/gtk4/enum.Orientation.html
 */
enum GtkOrientation: int
{
    case Horizontal = 0;
    case Vertical = 1;
}

/**
 * GApplication flags - a bitmask, combine with `|`. Flags types are constant
 * classes (PHP enums cannot be OR-ed); the values are verified against GLib's
 * GFlagsClass when the extension loads.
 *
 * @link https://docs.gtk.org/gio/flags.ApplicationFlags.html
 */
final class GApplicationFlags
{
    public const int DEFAULT_FLAGS = 0;
    public const int IS_SERVICE = 1;
    public const int IS_LAUNCHER = 2;
    public const int HANDLES_OPEN = 4;
    public const int HANDLES_COMMAND_LINE = 8;
    public const int SEND_ENVIRONMENT = 16;
    public const int NON_UNIQUE = 32;
    public const int CAN_OVERRIDE_APP_ID = 64;
    public const int ALLOW_REPLACEMENT = 128;
    public const int REPLACE = 256;
}

/** @link https://docs.gtk.org/gtk4/enum.FilterChange.html */
enum GtkFilterChange: int
{
    case Different = 0;
    case LessStrict = 1;
    case MoreStrict = 2;
}

/** @link https://docs.gtk.org/gtk4/enum.SorterChange.html */
enum GtkSorterChange: int
{
    case Different = 0;
    case Inverted = 1;
    case LessStrict = 2;
    case MoreStrict = 3;
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
    public static function timeout_add(int $intervalMs, callable $callback): int {}

    /** Remove an idle/timeout source; false if it was already gone. */
    public static function source_remove(int $sourceId): bool {}
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

    /** Error domain (quark name), e.g. "g-io-error-quark", "gdk-texture-error-quark". */
    public function getDomain(): string {}
}

/**
 * Pixel data usable by widgets and paintables. Byte buffers (`GBytes`) are
 * plain PHP strings on this side.
 *
 * @property int $width
 * @property int $height
 *
 * @link https://docs.gtk.org/gdk4/class.Texture.html
 * @not-serializable
 */
class GdkTexture extends GObject
{
    /** @throws GError If the file cannot be read or decoded */
    public static function new_from_filename(string $path): GdkTexture {}

    /**
     * @param string $bytes Encoded image data (PNG, JPEG, ...)
     * @throws GError If the data cannot be decoded
     */
    public static function new_from_bytes(string $bytes): GdkTexture {}

    public function get_width(): int {}

    public function get_height(): int {}

    /** The texture encoded as PNG. */
    public function save_to_png_bytes(): string {}

    /** @throws GError If the file cannot be written */
    public function save_to_png(string $path): void {}
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
 * A list of GObjects with change notification (`items-changed`).
 *
 * @link https://docs.gtk.org/gio/iface.ListModel.html
 */
interface GListModel
{
    /** GType name of the items, e.g. "PhpValue" or "GObject". */
    public function get_item_type(): string;

    public function get_n_items(): int;

    /** The item at $position, or null past the end. */
    public function get_item(int $position): ?GObject;
}

/**
 * A GListModel backed by an array; items must be instances of the item type.
 *
 * @property int $n_items
 *
 * @link https://docs.gtk.org/gio/class.ListStore.html
 * @not-serializable
 */
class GListStore extends GObject implements GListModel
{
    /**
     * @param string $itemType PHP class (e.g. PhpValue::class) or GType name of the items
     * @throws \ValueError If the type is unknown or not a GObject type
     */
    public function __construct(string $itemType = GObject::class) {}

    public function get_item_type(): string {}

    public function get_n_items(): int {}

    public function get_item(int $position): ?GObject {}

    /** @throws \TypeError If $item is not of the item type */
    public function append(GObject $item): void {}

    /** @throws \TypeError If $item is not of the item type */
    public function insert(int $position, GObject $item): void {}

    public function remove(int $position): void {}

    public function remove_all(): void {}

    /** Position of $item, or null if it is not in the store. */
    public function find(GObject $item): ?int {}
}

/**
 * An action: a named, optionally parameterised and stateful operation.
 * GVariant parameters and states are mapped to plain PHP values (bool, int,
 * float, string, list, associative array, null for "maybe" types).
 *
 * @link https://docs.gtk.org/gio/iface.Action.html
 */
interface GAction
{
    public function get_name(): string;

    public function get_enabled(): bool;

    /** GVariant type string of the activation parameter (e.g. "s", "i"), or null. */
    public function get_parameter_type(): ?string;

    /** Current state as a PHP value, or null for a stateless action. */
    public function get_state(): mixed;
}

/**
 * A container of actions, keyed by name.
 *
 * @link https://docs.gtk.org/gio/iface.ActionMap.html
 */
interface GActionMap
{
    public function add_action(GAction $action): void;

    public function remove_action(string $name): void;

    public function lookup_action(string $name): ?GAction;
}

/**
 * Something that can activate its actions by name.
 *
 * @link https://docs.gtk.org/gio/iface.ActionGroup.html
 */
interface GActionGroup
{
    public function has_action(string $name): bool;

    /** @return list<string> */
    public function list_actions(): array;

    /** Activate by name; $parameter is converted to the action's parameter type. */
    public function activate_action(string $name, mixed $parameter = null): void;
}

/**
 * The plain GAction implementation: emits `activate` (with the parameter as a
 * PHP value) and, if stateful, `change-state`.
 *
 * ```php
 * $quit = new GSimpleAction('quit');
 * $quit->connect('activate', fn() => $app->quit());
 * $app->add_action($quit);            // reachable as "app.quit"
 * ```
 *
 * @property string $name
 * @property bool $enabled
 * @property ?string $parameter_type
 * @property mixed $state
 *
 * @link https://docs.gtk.org/gio/class.SimpleAction.html
 * @not-serializable
 */
class GSimpleAction extends GObject implements GAction
{
    /**
     * @param string $name Action name (letters, digits, `-` and `.`)
     * @param string|null $parameterType GVariant type string the `activate` parameter must have, or null for none
     * @param mixed $state Initial state for a stateful action (its GVariant type is inferred), or null for stateless
     */
    public function __construct(string $name, ?string $parameterType = null, mixed $state = null) {}

    public function get_name(): string {}

    public function get_enabled(): bool {}

    public function set_enabled(bool $enabled): void {}

    public function get_parameter_type(): ?string {}

    public function get_state(): mixed {}

    /** Set the state directly (emits `notify::state`, not `change-state`). */
    public function set_state(mixed $state): void {}

    /** Activate as if through an action group; emits `activate`. */
    public function activate(mixed $parameter = null): void {}
}

/**
 * The application object: owns the main loop and the windows.
 *
 * ```php
 * $app = new GtkApplication('org.example.Hello');
 * $app->connect('activate', function (GtkApplication $app): void {
 *     $win = new GtkWindow($app);
 *     $win->present();
 * });
 * exit($app->run($argv));
 * ```
 *
 * @property ?string $application_id
 * @property ?GtkWindow $active_window
 *
 * @link https://docs.gtk.org/gtk4/class.Application.html
 * @not-serializable
 */
class GtkApplication extends GObject implements GActionMap, GActionGroup
{
    /**
     * @param string|null $applicationId Reverse-DNS id, or null for a non-unique app
     * @param int $flags Bitmask of {@see GApplicationFlags} constants
     */
    public function __construct(?string $applicationId = null, int $flags = 0) {}

    /**
     * Run the application (emits `startup`, `activate`, ...) until the last
     * window closes or {@see quit()} is called.
     *
     * @param array $argv Command line as passed to the script (list of strings)
     * @return int Exit status
     */
    public function run(array $argv = []): int {}

    public function quit(): void {}

    public function add_window(GtkWindow $window): void {}

    public function get_active_window(): ?GtkWindow {}

    public function get_application_id(): ?string {}

    /**
     * The application's windows, most recently focused first.
     *
     * @return list<GtkWindow>
     */
    public function get_windows(): array {}

    public function add_action(GAction $action): void {}

    public function remove_action(string $name): void {}

    public function lookup_action(string $name): ?GAction {}

    /**
     * GActionGroup methods (has_action, list_actions, activate_action) work once the
     * application is registered, i.e. from `startup` on; add/remove/lookup_action work any time.
     */
    public function has_action(string $name): bool {}

    public function list_actions(): array {}

    public function activate_action(string $name, mixed $parameter = null): void {}
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
 * Base class of all widgets. Not instantiable from PHP.
 *
 * GObject properties are also available as PHP properties (dashes become
 * underscores).
 *
 * @property bool $visible
 * @property bool $sensitive
 * @property bool $can_focus
 * @property bool $has_focus
 * @property ?string $tooltip_text
 * @property ?string $name
 * @property GtkAlign $halign
 * @property GtkAlign $valign
 * @property bool $hexpand
 * @property bool $vexpand
 * @property int $margin_start
 * @property int $margin_end
 * @property int $margin_top
 * @property int $margin_bottom
 * @property float $opacity
 * @property array $css_classes
 * @property int $width_request
 * @property int $height_request
 * @property ?GtkWidget $parent
 *
 * @link https://docs.gtk.org/gtk4/class.Widget.html
 * @not-serializable
 */
abstract class GtkWidget extends GObject
{
    public function show(): void {}

    public function hide(): void {}

    public function set_visible(bool $visible): void {}

    public function get_visible(): bool {}

    /** Whether the widget and all its ancestors are visible. */
    public function is_visible(): bool {}

    public function set_sensitive(bool $sensitive): void {}

    public function get_sensitive(): bool {}

    /** Minimum size in pixels; -1 = natural size. */
    public function set_size_request(int $width, int $height): void {}

    /**
     * The size request as [width, height] (out parameters become a list).
     *
     * @return array{int, int}
     */
    public function get_size_request(): array {}

    /**
     * Widgets whose mnemonic activates this widget.
     *
     * @return list<GtkWidget>
     */
    public function list_mnemonic_labels(): array {}

    public function get_parent(): ?GtkWidget {}

    /** The toplevel GtkWindow (or other root) this widget is in, if any. */
    public function get_root(): ?GtkWidget {}

    public function grab_focus(): bool {}

    /** Activate the widget (a button emits `clicked`); false if it is not activatable. */
    public function activate(): bool {}

    public function set_tooltip_text(?string $text): void {}

    public function get_tooltip_text(): ?string {}

    public function set_name(?string $name): void {}

    public function get_name(): ?string {}

    public function add_css_class(string $cssClass): void {}

    public function remove_css_class(string $cssClass): void {}

    public function has_css_class(string $cssClass): bool {}

    /** @return list<string> */
    public function get_css_classes(): array {}

    /** @param array $classes list of class names (replaces all current ones) */
    public function set_css_classes(array $classes): void {}

    /**
     * Activate a named action ("app.quit", "win.close") found on this widget's ancestry.
     * $parameter is converted to the action's parameter type. False if no such action.
     */
    public function activate_action(string $name, mixed $parameter = null): bool {}

    public function set_halign(GtkAlign $align): void {}

    public function get_halign(): GtkAlign {}

    public function set_valign(GtkAlign $align): void {}

    public function get_valign(): GtkAlign {}

    /** Whether the widget takes the horizontal space its parent has spare. */
    public function set_hexpand(bool $expand): void {}

    public function get_hexpand(): bool {}

    public function set_vexpand(bool $expand): void {}

    public function get_vexpand(): bool {}

    /** Queue a redraw of the widget. */
    public function queue_draw(): void {}
}

/**
 * A widget that emits `clicked` when activated.
 *
 * @property ?string $label
 * @property ?GtkWidget $child
 *
 * @link https://docs.gtk.org/gtk4/class.Button.html
 * @not-serializable
 */
class GtkButton extends GtkWidget
{
    /** @param string|null $label Text label; null for an empty button */
    public function __construct(?string $label = null) {}

    public function set_label(?string $label): void {}

    public function get_label(): ?string {}

    public function set_child(?GtkWidget $child): void {}

    public function get_child(): ?GtkWidget {}
}

/**
 * The layout container: children in a row or a column.
 *
 * GTK 4 has no GtkContainer - a window or a button holds exactly one child, and
 * this is what holds several. `pack_start`/`pack_end` are gone; children are
 * appended, prepended or inserted after a sibling, and where the spare space goes
 * is the child's own {@see GtkWidget::set_hexpand()} / `set_vexpand()`.
 *
 * ```php
 * $row = new GtkBox(GtkOrientation::Horizontal, 6);
 * $row->append(new GtkLabel('left'));
 * $row->append(new GtkButton('right'));
 * $window->set_child($row);
 * ```
 *
 * @property int $spacing
 * @property bool $homogeneous
 * @property GtkOrientation $orientation
 *
 * @link https://docs.gtk.org/gtk4/class.Box.html
 * @not-serializable
 */
class GtkBox extends GtkWidget
{
    public function __construct(
        GtkOrientation $orientation = GtkOrientation::Horizontal,
        int $spacing = 0,
    ) {}

    /**
     * Add $child at the end.
     *
     * @throws \ValueError If $child already has a parent - GTK inserts, it never reparents
     */
    public function append(GtkWidget $child): void {}

    /**
     * Add $child at the start.
     *
     * @throws \ValueError If $child already has a parent
     */
    public function prepend(GtkWidget $child): void {}

    /**
     * Insert $child directly after $sibling, or at the start when $sibling is null.
     *
     * To move a child that is already in this box, {@see remove()} it first.
     *
     * @throws \ValueError If $child already has a parent, or $sibling is not a child of this box
     */
    public function insert_child_after(GtkWidget $child, ?GtkWidget $sibling): void {}

    /**
     * Remove a child.
     *
     * @throws \ValueError If $child is not a child of this box
     */
    public function remove(GtkWidget $child): void {}

    /** Pixels between children. */
    public function set_spacing(int $spacing): void {}

    public function get_spacing(): int {}

    /** Whether every child gets the same amount of space. */
    public function set_homogeneous(bool $homogeneous): void {}

    public function get_homogeneous(): bool {}

    public function set_orientation(GtkOrientation $orientation): void {}

    public function get_orientation(): GtkOrientation {}

    /**
     * This box's children, in order.
     *
     * @return list<GtkWidget>
     */
    public function get_children(): array {}
}

/**
 * A widget that displays a small amount of text.
 *
 * @property string $label
 * @property bool $use_markup
 * @property bool $selectable
 * @property bool $wrap
 *
 * @link https://docs.gtk.org/gtk4/class.Label.html
 * @not-serializable
 */
class GtkLabel extends GtkWidget
{
    public function __construct(?string $text = null) {}

    public function set_text(string $text): void {}

    public function get_text(): string {}

    /** Set Pango markup, e.g. `<b>bold</b>`. */
    public function set_markup(string $markup): void {}

    public function set_selectable(bool $selectable): void {}

    public function get_selectable(): bool {}

    /**
     * Selected character range as [start, end], or null when nothing is selected
     * (a boolean-returning C function with out parameters returns the outs or null).
     *
     * @return array{int, int}|null
     */
    public function get_selection_bounds(): ?array {}

    public function select_region(int $start, int $end): void {}
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

/**
 * A widget that paints with cairo through a PHP callback.
 *
 * ```php
 * $area->set_draw_func(function (GtkDrawingArea $a, CairoContext $cr, int $w, int $h): void {
 *     $cr->set_source_rgb(0.2, 0.4, 0.8);
 *     $cr->rectangle(0, 0, $w, $h);
 *     $cr->fill();
 * });
 * ```
 *
 * @property int $content_width
 * @property int $content_height
 *
 * @link https://docs.gtk.org/gtk4/class.DrawingArea.html
 * @not-serializable
 */
class GtkDrawingArea extends GtkWidget
{
    public function __construct() {}

    /**
     * Install (or with null, remove) the draw function: `function (GtkDrawingArea $area,
     * CairoContext $cr, int $width, int $height): void`. Kept until replaced or the widget dies.
     */
    public function set_draw_func(?callable $drawFunc): void {}

    public function set_content_width(int $width): void {}

    public function get_content_width(): int {}

    public function set_content_height(int $height): void {}

    public function get_content_height(): int {}
}

/**
 * Decides which items of a list model are visible.
 *
 * @link https://docs.gtk.org/gtk4/class.Filter.html
 * @not-serializable
 */
abstract class GtkFilter extends GObject
{
    /** Tell users of the filter that its decisions changed. */
    public function changed(GtkFilterChange $change = GtkFilterChange::Different): void {}
}

/**
 * A GtkFilter driven by a PHP callback: `function (GObject $item): bool`.
 *
 * @link https://docs.gtk.org/gtk4/class.CustomFilter.html
 * @not-serializable
 */
class GtkCustomFilter extends GtkFilter
{
    public function __construct(?callable $matchFunc = null) {}

    /** Replace the callback (null = everything matches) and notify users. */
    public function set_filter_func(?callable $matchFunc): void {}
}

/**
 * A GListModel showing only the items of another model that pass a filter.
 *
 * @property ?GtkFilter $filter
 * @property ?GListModel $model
 *
 * @link https://docs.gtk.org/gtk4/class.FilterListModel.html
 * @not-serializable
 */
class GtkFilterListModel extends GObject implements GListModel
{
    public function __construct(?GListModel $model = null, ?GtkFilter $filter = null) {}

    public function get_item_type(): string {}

    public function get_n_items(): int {}

    public function get_item(int $position): ?GObject {}

    public function set_filter(?GtkFilter $filter): void {}

    public function get_filter(): ?GtkFilter {}

    public function set_model(?GListModel $model): void {}

    public function get_model(): ?GListModel {}
}

/**
 * Orders the items of a list model.
 *
 * @link https://docs.gtk.org/gtk4/class.Sorter.html
 * @not-serializable
 */
abstract class GtkSorter extends GObject
{
    public function changed(GtkSorterChange $change = GtkSorterChange::Different): void {}
}

/**
 * A GtkSorter driven by a PHP callback: `function (GObject $a, GObject $b): int` (negative,
 * zero, positive - like `<=>`).
 *
 * @link https://docs.gtk.org/gtk4/class.CustomSorter.html
 * @not-serializable
 */
class GtkCustomSorter extends GtkSorter
{
    public function __construct(?callable $compare = null) {}

    /** Replace the callback (null = keep original order) and notify users. */
    public function set_sort_func(?callable $compare): void {}
}

/**
 * A GListModel presenting another model's items in sorted order.
 *
 * @property ?GtkSorter $sorter
 * @property ?GListModel $model
 *
 * @link https://docs.gtk.org/gtk4/class.SortListModel.html
 * @not-serializable
 */
class GtkSortListModel extends GObject implements GListModel
{
    public function __construct(?GListModel $model = null, ?GtkSorter $sorter = null) {}

    public function get_item_type(): string {}

    public function get_n_items(): int {}

    public function get_item(int $position): ?GObject {}

    public function set_sorter(?GtkSorter $sorter): void {}

    public function get_sorter(): ?GtkSorter {}

    public function set_model(?GListModel $model): void {}

    public function get_model(): ?GListModel {}
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
 * @property ?GtkWidget $child
 * @property ?GtkApplication $application
 *
 * @link https://docs.gtk.org/gtk4/class.Window.html
 * @not-serializable
 */
class GtkWindow extends GtkWidget
{
    /** @param GtkApplication|null $application Owning application (keeps its main loop alive) */
    public function __construct(?GtkApplication $application = null) {}

    public function set_application(?GtkApplication $application): void {}

    public function get_application(): ?GtkApplication {}

    public function set_title(?string $title): void {}

    public function get_title(): ?string {}

    /** Default size in pixels; -1 to unset one dimension. */
    public function set_default_size(int $width, int $height): void {}

    /** @return array{int, int} [width, height]; -1 where unset */
    public function get_default_size(): array {}

    /** Set (or with null, remove) the single child widget. */
    public function set_child(?GtkWidget $child): void {}

    public function get_child(): ?GtkWidget {}

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
