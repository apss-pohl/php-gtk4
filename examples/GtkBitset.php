<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\CairoContext;
use Gtk4\GdkRGBA;
use Gtk4\GtkBitset;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkDrawingArea;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkBitset - a set of positions, as a value.
 *
 * What every selection model answers with: the set of selected positions,
 * stored as ranges rather than as one flag per row, so "all 10 million rows are
 * selected" is cheap. A boxed record with no constructor - new_empty() and
 * new_range() build one - and a real value: `clone` copies it (the refcount
 * would only alias) and `==` compares the contents.
 *
 *   bin/php-gtk4 examples/demo.php GtkBitset
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkBitset',
    'a set of positions, as a value',
    function (GtkWindow $win): GtkWidget {
        $cells = 32;
        $left = GtkBitset::new_range(2, 12);      // 2 … 13
        $right = GtkBitset::new_range(8, 12);     // 8 … 19
        $result = clone $left;
        $note = 'left = new_range(2, 12), right = new_range(8, 12)';

        $row = function (CairoContext $cr, GtkBitset $set, float $y, string $css) use ($cells): void {
            $cr->set_source_color(new GdkRGBA($css));
            for ($i = 0; $i < $cells; $i++) {
                if (!$set->contains($i)) {
                    continue;
                }
                $cr->rectangle(10.0 + $i * 13.0, $y, 11.0, 18.0);
                $cr->fill();
            }
        };

        $canvas = Demo::canvas(440, 150, function (
            GtkDrawingArea $area,
            CairoContext $cr,
            int $width,
            int $height,
        ) use (
            $row,
            $left,
            $right,
            &$result,
            $cells
        ): void {
            $ink = Demo::sheet($cr);
            $cr->set_source_color(new GdkRGBA(Demo::MUTED));
            for ($i = 0; $i < $cells; $i++) {   // the empty grid, one cell per position
                $cr->rectangle(10.0 + $i * 13.0, 20.0, 11.0, 18.0);
                $cr->rectangle(10.0 + $i * 13.0, 55.0, 11.0, 18.0);
                $cr->rectangle(10.0 + $i * 13.0, 100.0, 11.0, 18.0);
            }
            $cr->set_line_width(0.5);
            $cr->stroke();
            $row($cr, $left, 20.0, Demo::ACCENT);
            $row($cr, $right, 55.0, Demo::WARN);
            $row($cr, $result, 100.0, Demo::GOOD);
            $cr->set_source_color($ink);
            $cr->move_to(10.0, 92.0);
            $cr->show_text('result');
        });

        $face = Demo::label();
        $describe = function () use ($face, &$result, &$note, $left): void {
            $positions = [];
            for ($i = 0; $i < $result->get_size(); $i++) {
                $positions[] = $result->get_nth($i);
            }
            $face->set_markup(sprintf(
                "<b>%s</b>\n<tt>size %d  min %s  max %s  equals(left) %s</tt>\n<small>{%s}</small>",
                htmlspecialchars($note),
                $result->get_size(),
                $result->is_empty() ? '-' : (string) $result->get_minimum(),
                $result->is_empty() ? '-' : (string) $result->get_maximum(),
                $result->equals($left) ? 'true' : 'false',
                implode(', ', $positions),
            ));
        };

        /** @var list<callable(): string> $steps */
        $steps = [
            function () use ($left, $right, &$result): string {
                $result = clone $left;
                $result->union($right);
                return 'result = left ∪ right   (union)';
            },
            function () use ($left, $right, &$result): string {
                $result = clone $left;
                $result->intersect($right);
                return 'result = left ∩ right   (intersect)';
            },
            function () use ($left, $right, &$result): string {
                $result = clone $left;
                $result->subtract($right);
                return 'result = left \\ right   (subtract)';
            },
            function () use ($left, $right, &$result): string {
                $result = clone $left;
                $result->difference($right);
                return 'result = left △ right   (symmetric difference)';
            },
            function () use ($left, &$result): string {
                $result = clone $left;
                $result->shift_right(6);
                return 'result = left shifted right by 6';
            },
            function () use ($left, &$result): string {
                $result = clone $left;
                return 'result = clone left   (the operands never changed)';
            },
        ];
        $step = 0;
        $button = GtkButton::new_with_label('next set operation');
        $button->connect('clicked', function () use ($steps, &$step, &$note, $describe, $canvas): void {
            $note = $steps[$step++ % count($steps)]();
            $describe();
            $canvas->queue_draw();
        });

        $describe();
        $page = new GtkBox(GtkOrientation::Vertical, 8);
        $page->append($canvas);
        $page->append($face);
        $page->append($button);
        return $page;
    },
    480,
    400,
);
