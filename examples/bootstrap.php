<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\CairoContext;
use Gtk4\ExceptionMode;
use Gtk4\GApplicationFlags;
use Gtk4\GdkRGBA;
use Gtk4\GListModel;
use Gtk4\GListStore;
use Gtk4\GObject;
use Gtk4\GSimpleAction;
use Gtk4\Gtk;
use Gtk4\GtkAlign;
use Gtk4\GtkApplication;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkDrawingArea;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\PhpValue;

/**
 * Shared harness for the per-class examples next to this file.
 *
 * Every example is one class, one file, one window, and every one of them ends in
 * {@see page()} rather than running anything: run the file and it shows itself,
 * let `demo.php` require it and the page is mounted in the shared window instead.
 * One copy of each demo's source, two ways to run it.
 *
 * This file only declares - GTK is initialised lazily by run()/init(), so requiring
 * it has no side effects.
 *
 * GTK 4 has no container widget bound yet, so a window holds exactly one child. The
 * two surfaces the examples draw on are therefore a markup {@see label()} and a cairo
 * {@see canvas()}; {@see bars()} is the shared rendering of a list model used by the
 * model, filter and sorter examples.
 *
 * @phpstan-type PageBuilder callable(GtkWindow, GtkApplication): ?GtkWidget
 * @phpstan-type DemoPage array{class: string, summary: string, build: PageBuilder, width: int,
 *                             height: int, alone: (callable(): never)|null}
 */
final class Demo
{
    public const string ACCENT = '#3584e4';
    public const string INK = '#241f31';
    public const string MUTED = '#77767b';
    public const string WARN = '#e01b24';
    public const string GOOD = '#2ec27e';
    public const string PAPER = '#fdfdfd';

    /**
     * The sidebar's sections, in order. Every registered class belongs to exactly
     * one (ExampleTest checks); until GtkScrolledWindow is bound this is also what
     * keeps the sidebar short enough to fit on screen.
     *
     * @var array<string, list<string>>
     */
    public const array SECTIONS = [
        'Widgets' => [
            'GtkWidget', 'GtkWindow', 'GtkBox', 'GtkButton', 'GtkLabel',
            'GtkDrawingArea', 'CairoContext',
        ],
        'Application' => [
            'Gtk', 'GtkApplication', 'GSimpleAction', 'GAction', 'GActionMap',
            'GActionGroup', 'GApplicationFlags',
        ],
        'Objects & values' => [
            'GObject', 'GParamSpec', 'PhpValue', 'GdkRGBA', 'GdkRectangle',
            'GdkTexture', 'GError', 'ExceptionMode',
        ],
        'Lists' => [
            'GListModel', 'GListStore', 'GtkFilter', 'GtkCustomFilter',
            'GtkFilterListModel', 'GtkFilterChange', 'GtkSorter', 'GtkCustomSorter',
            'GtkSortListModel', 'GtkSorterChange',
        ],
        'Loop & enums' => ['GLib', 'GMainLoop', 'GtkAlign', 'GtkOrientation'],
    ];

    private static bool $ready = false;
    private static string $prefix = 'php-gtk4';
    private static ?GtkWindow $window = null;

    /**
     * GTK init plus the callback exception policy every example shares.
     *
     * A Throwable must never unwind through GTK's C frames, so one thrown inside a
     * signal handler is reported here instead and GTK carries on (ExceptionMode::Log).
     * ExceptionMode.php shows the alternative.
     */
    public static function init(): void
    {
        if (self::$ready) {
            return;
        }
        if (!Gtk::init()) {
            fwrite(STDERR, "no display - run inside a desktop session or under xvfb-run\n");
            exit(1);
        }
        Gtk::set_exception_mode(ExceptionMode::Log);
        Gtk::set_exception_handler(static function (\Throwable $e, string $origin): void {
            error_log(sprintf('[%s] %s: %s', $origin, $e::class, $e->getMessage()));
        });
        self::$ready = true;
    }

    /**
     * Build a single-window application around $build and run it until the window closes.
     *
     * @param callable(GtkWindow, GtkApplication): ?GtkWidget $build
     *        Returns the window's child, or null if it set one itself.
     */
    public static function run(string $title, callable $build, int $width = 480, int $height = 340): never
    {
        self::init();
        $app = new GtkApplication('org.phpgtk4.examples', GApplicationFlags::NON_UNIQUE);
        $app->connect('activate', static function (GtkApplication $app) use ($title, $build, $width, $height): void {
            $win = new GtkWindow($app);
            $win->set_default_size($width, $height);
            self::$window = $win;
            self::$prefix = 'php-gtk4 · ' . $title;
            self::status();
            $child = $build($win, $app);
            if ($child instanceof GtkWidget) {
                $win->set_child($child);
            }
            $win->present();
        });
        exit($app->run());
    }

    /**
     * Declare what this file demonstrates.
     *
     * Called at the end of every examples/<Class>.php, which does nothing else -
     * the file is a description, not a program. examples/demo.php collects them all
     * into the application; `examples/demo.php GtkButton` runs just this one.
     *
     * @param callable(GtkWindow, GtkApplication): ?GtkWidget $build
     * @param (callable(): never)|null $alone Replaces the window when this page is run on its own
     * @return DemoPage
     */
    public static function page(
        string $class,
        string $summary,
        callable $build,
        int $width = 480,
        int $height = 340,
        ?callable $alone = null,
    ): array {
        return [
            'class' => $class,
            'summary' => $summary,
            'build' => $build,
            'width' => $width,
            'height' => $height,
            'alone' => $alone,
        ];
    }

    /**
     * Show one page in a window of its own - what `demo.php <Class>` does.
     *
     * @param DemoPage $page
     */
    public static function single(array $page): never
    {
        if ($page['alone'] !== null) {
            ($page['alone'])();
        }
        self::run($page['class'], $page['build'], $page['width'], $page['height']);
    }

    /**
     * Every examples/<Class>.php, required and asked for its page.
     *
     * @return list<DemoPage>
     */
    public static function pages(): array
    {
        $pages = [];
        foreach (glob(__DIR__ . '/*.php') ?: [] as $file) {
            if (in_array(basename($file), ['bootstrap.php', 'demo.php'], true)) {
                continue;
            }
            $loaded = require $file;
            if (!is_array($loaded) || !isset($loaded['class'], $loaded['summary'], $loaded['build'])) {
                continue;   // not a page file
            }
            /** @var DemoPage $loaded */
            $pages[] = $loaded;
        }
        usort($pages, static fn(array $a, array $b): int => strcmp($a['class'], $b['class']));
        return $pages;
    }

    /**
     * The demo application: a header row, a sidebar of sections, a content area.
     *
     * This is what GtkBox bought - before it, a window held exactly one child and the
     * only possible shape was a slideshow. The content is still swapped rather than
     * stacked (no GtkStack yet), but navigation now lives next to what it navigates.
     *
     * Pages are built the first time they are shown and then kept, so a page's
     * animations pick up where they left off.
     *
     * @param list<DemoPage> $pages
     */
    public static function showcase(array $pages): never
    {
        self::init();
        if ($pages === []) {
            fwrite(STDERR, 'no pages found in ' . __DIR__ . "\n");
            exit(1);
        }

        /** @var array<string, DemoPage> $byClass */
        $byClass = [];
        foreach ($pages as $page) {
            $byClass[$page['class']] = $page;
        }

        $app = new GtkApplication('org.phpgtk4.examples.demo', GApplicationFlags::NON_UNIQUE);
        $app->connect('activate', static function (GtkApplication $app) use ($pages, $byClass): void {
            $win = new GtkWindow($app);
            $win->set_default_size(940, 660);
            self::$window = $win;

            /** @var array<string, GtkWidget> $built */
            $built = [];
            $section = (string) array_key_first(self::SECTIONS);
            $class = $pages[0]['class'];

            $content = new GtkBox(GtkOrientation::Vertical);
            $content->set_hexpand(true);
            $content->set_vexpand(true);

            $heading = new GtkLabel();
            $heading->set_halign(GtkAlign::Start);
            $heading->set_hexpand(true);

            $list = new GtkBox(GtkOrientation::Vertical, 2);
            $sections = new GtkBox(GtkOrientation::Vertical, 2);

            // Navigation goes through an action, so nothing here has to hold a
            // reference to anything defined below it.
            $select = new GSimpleAction('select', 's');
            $app->add_action($select);
            $jump = static function (string $to) use ($app): void {
                $app->activate_action('select', $to);
            };

            // The sidebar lists the current section only: 36 buttons do not fit on
            // screen and GtkScrolledWindow is not bound yet.
            $fillList = function () use ($list, &$section, &$class, $jump, $byClass): void {
                foreach ($list->get_children() as $old) {
                    $list->remove($old);
                }
                foreach (self::SECTIONS[$section] as $member) {
                    if (!isset($byClass[$member])) {
                        continue;
                    }
                    $button = new GtkButton($member);
                    $button->add_css_class($member === $class ? 'suggested-action' : 'flat');
                    $button->connect('clicked', static function () use ($jump, $member): void {
                        $jump($member);
                    });
                    $list->append($button);
                }
            };

            $show = function (string $wanted) use (
                &$built,
                &$class,
                &$section,
                $fillList,
                $byClass,
                $content,
                $heading,
                $win,
                $app
            ): void {
                if (!isset($byClass[$wanted])) {
                    return;
                }
                $page = $byClass[$wanted];
                $class = $wanted;
                foreach (self::SECTIONS as $name => $members) {
                    if (in_array($wanted, $members, true)) {
                        $section = $name;
                    }
                }
                if (!isset($built[$wanted])) {
                    $widget = ($page['build'])($win, $app);
                    $built[$wanted] = $widget instanceof GtkWidget
                        ? $widget
                        : self::label('(this page builds no widget)');
                    // Natural size, centred - the content box is what expands.
                    $built[$wanted]->set_halign(GtkAlign::Center);
                    $built[$wanted]->set_valign(GtkAlign::Center);
                }
                foreach ($content->get_children() as $old) {
                    $content->remove($old);   // GTK inserts, it never reparents
                }
                $content->append($built[$wanted]);
                $heading->set_markup(sprintf(
                    "<span size=\"large\"><b>%s</b></span>  <span alpha=\"55%%\">%s</span>\n<small>%s</small>",
                    htmlspecialchars($page['class']),
                    htmlspecialchars($section),
                    htmlspecialchars($page['summary']),
                ));
                self::$prefix = 'php-gtk4 · ' . $page['class'];
                self::status($section);
                $fillList();
            };

            $select->connect('activate', static function (GSimpleAction $self, mixed $wanted) use ($show): void {
                if (is_string($wanted)) {
                    $show($wanted);
                }
            });

            foreach (array_keys(self::SECTIONS) as $name) {
                $button = new GtkButton($name);
                $button->add_css_class('flat');
                $button->connect('clicked', static function () use ($jump, $name): void {
                    $jump(self::SECTIONS[$name][0]);
                });
                $sections->append($button);
            }

            // Previous/Next walk the whole list, not just the section.
            $step = static function (int $delta) use ($pages, &$class, $jump): void {
                $names = array_map(static fn(array $page): string => $page['class'], $pages);
                $at = array_search($class, $names, true);
                $at = is_int($at) ? $at : 0;
                $jump($names[($at + $delta + count($names)) % count($names)]);
            };
            $quit = new GSimpleAction('quit');
            $quit->connect('activate', static function () use ($app): void {
                $app->quit();
            });
            $app->add_action($quit);

            foreach (['previous' => -1, 'next' => 1] as $name => $delta) {
                $action = new GSimpleAction($name);
                $action->connect('activate', static function () use ($step, $delta): void {
                    $step($delta);
                });
                $app->add_action($action);
            }
            $nav = static function (string $label, string $action) use ($app): GtkButton {
                $button = new GtkButton($label);
                $button->connect('clicked', static function () use ($app, $action): void {
                    $app->activate_action($action);
                });
                return $button;
            };

            $header = new GtkBox(GtkOrientation::Horizontal, 6);
            $buttons = [$heading, $nav('◀  Previous', 'previous'), $nav('Next  ▶', 'next'), $nav('Quit', 'quit')];
            foreach ($buttons as $item) {
                $header->append($item);
            }

            $sidebar = new GtkBox(GtkOrientation::Vertical, 10);
            $sidebar->set_size_request(200, -1);
            $sidebar->append($sections);
            $sidebar->append($list);

            $body = new GtkBox(GtkOrientation::Horizontal, 16);
            $body->set_vexpand(true);
            $body->append($sidebar);
            $body->append($content);

            $root = new GtkBox(GtkOrientation::Vertical, 10);
            $root->append($header);
            $root->append($body);
            $win->set_child($root);

            // This is the primary window: closing it ends the application, even
            // though a page may have opened toplevels of its own (GtkWindow does).
            $win->connect('close-request', static function () use ($app): bool {
                $app->quit();
                return false;
            });

            $show($pages[0]['class']);
            $win->present();
        });

        exit($app->run());
    }

    /**
     * Put a line of state in the window title, under whatever heading is current.
     *
     * Standalone that reads "php-gtk4 · GtkLabel — selection 0..14"; inside the
     * showcase, "12/35 · GtkLabel — selection 0..14". A page that wants to drive the
     * title itself (GObject, GParamSpec, GtkWindow) just calls set_title() instead.
     */
    public static function status(string $text = ''): void
    {
        self::$window?->set_title($text === '' ? self::$prefix : self::$prefix . ' — ' . $text);
    }

    /** A centred, wrapping Pango-markup label - the text surface most examples write on. */
    public static function label(string $markup = ''): GtkLabel
    {
        $label = new GtkLabel();
        $label->set_markup($markup);
        $label->wrap = true;
        $label->set_halign(GtkAlign::Center);
        $label->set_valign(GtkAlign::Center);
        return $label;
    }

    /** @param callable(GtkDrawingArea, CairoContext, int, int): void $draw */
    public static function canvas(int $width, int $height, callable $draw): GtkDrawingArea
    {
        $area = new GtkDrawingArea();
        $area->set_content_width($width);
        $area->set_content_height($height);
        $area->set_draw_func($draw);
        return $area;
    }

    /** Fill the whole area with PAPER and return a pen colour - the start of every canvas. */
    public static function sheet(CairoContext $cr, string $css = self::INK): GdkRGBA
    {
        $cr->set_source_color(new GdkRGBA(self::PAPER));
        $cr->paint();
        return new GdkRGBA($css);
    }

    /** Draw $text at ($x, $y) in $css at $size pixels. */
    public static function text(
        CairoContext $cr,
        float $x,
        float $y,
        string $text,
        string $css = self::INK,
        float $size = 13.0,
    ): void {
        $cr->set_source_color(new GdkRGBA($css));
        $cr->set_font_size($size);
        $cr->move_to($x, $y);
        $cr->show_text($text);
    }

    /** @return list<array{name: string, born: int}> The dataset the list examples share. */
    public static function people(): array
    {
        return [
            ['name' => 'Ada Lovelace', 'born' => 1815],
            ['name' => 'Grace Hopper', 'born' => 1906],
            ['name' => 'Alan Turing', 'born' => 1912],
            ['name' => 'Katherine Johnson', 'born' => 1918],
            ['name' => 'Margaret Hamilton', 'born' => 1936],
        ];
    }

    /** The same people as a GListStore of PhpValue items. */
    public static function store(): GListStore
    {
        $store = new GListStore(PhpValue::class);
        foreach (self::people() as $row) {
            $store->append(new PhpValue($row));
        }
        return $store;
    }

    /** Name of a PhpValue row, '?' for anything else. */
    public static function name(?GObject $item): string
    {
        $row = $item instanceof PhpValue ? $item->get_value() : null;
        $name = is_array($row) ? ($row['name'] ?? null) : null;
        return is_string($name) ? $name : '?';
    }

    /** Birth year of a PhpValue row, 0 for anything else. */
    public static function born(?GObject $item): int
    {
        $row = $item instanceof PhpValue ? $item->get_value() : null;
        $born = is_array($row) ? ($row['born'] ?? null) : null;
        return is_int($born) ? $born : 0;
    }

    /**
     * One labelled bar per item of $model - the shared picture of a list model.
     *
     * The model is read on every draw, so any change only needs queue_draw(); the
     * caption is a callable for the same reason.
     *
     * @param callable(): string $caption
     */
    public static function bars(GListModel $model, callable $caption, string $css = self::ACCENT): GtkDrawingArea
    {
        return self::canvas(440, 240, static function (
            GtkDrawingArea $area,
            CairoContext $cr,
            int $width,
            int $height,
        ) use (
            $model,
            $caption,
            $css
        ): void {
            self::sheet($cr);
            $count = $model->get_n_items();
            self::text($cr, 14, 24, sprintf('%s — %d item(s)', $caption(), $count), self::INK, 14);

            for ($i = 0; $i < $count; $i++) {
                $y = 44 + $i * 26;
                if ($y + 20 > $height) {
                    self::text($cr, 14, $height - 8, '…', self::MUTED, 12);
                    break;
                }
                $item = $model->get_item($i);
                // 1800..1950 mapped onto the space left of the caption column.
                $bar = max(8, (int) round((self::born($item) - 1800) / 150 * ($width - 210)));
                $cr->set_source_color(new GdkRGBA($css));
                $cr->rectangle(14, $y, $bar, 16);
                $cr->fill();
                $caption = sprintf('%s · %d', self::name($item), self::born($item));
                self::text($cr, 14 + $bar + 8, $y + 13, $caption, self::INK, 12);
            }
        });
    }
}
