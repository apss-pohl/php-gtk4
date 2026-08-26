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
use Gtk4\Gtk;
use Gtk4\GtkAlign;
use Gtk4\GtkApplication;
use Gtk4\GtkDrawingArea;
use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\PhpValue;

/**
 * Shared harness for the per-class examples next to this file.
 *
 * Every example is one class, one file, one window: `bin/php-gtk4 examples/GtkButton.php`.
 * This file only declares - GTK is initialised lazily by run()/init(), so requiring it
 * has no side effects.
 *
 * GTK 4 has no container widget bound yet, so a window holds exactly one child. The
 * two surfaces the examples draw on are therefore a markup {@see label()} and a cairo
 * {@see canvas()}; {@see bars()} is the shared rendering of a list model used by the
 * model, filter and sorter examples.
 */
final class Demo
{
    public const string ACCENT = '#3584e4';
    public const string INK = '#241f31';
    public const string MUTED = '#77767b';
    public const string WARN = '#e01b24';
    public const string GOOD = '#2ec27e';
    public const string PAPER = '#fdfdfd';

    private static bool $ready = false;

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
            $win->set_title('php-gtk4 · ' . $title);
            $win->set_default_size($width, $height);
            $child = $build($win, $app);
            if ($child instanceof GtkWidget) {
                $win->set_child($child);
            }
            $win->present();
        });
        exit($app->run());
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
    public static function text(CairoContext $cr, float $x, float $y, string $text, string $css = self::INK, float $size = 13.0): void
    {
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
        ) use ($model, $caption, $css): void {
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
                self::text($cr, 14 + $bar + 8, $y + 13, sprintf('%s · %d', self::name($item), self::born($item)), self::INK, 12);
            }
        });
    }
}
