<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GObject;
use Gtk4\GtkFlowBox;
use Gtk4\GtkFlowBoxChild;
use Gtk4\GtkLabel;
use Gtk4\GtkScrolledWindow;
use Gtk4\GtkSelectionMode;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkFlowBox - children that wrap into as many lines as they need.
 *
 * A list box puts every child on its own line; a flow box fills the width and wraps, between
 * min_children_per_line and max_children_per_line. It takes the same callbacks otherwise -
 * bind_model() builds a child per model item, set_sort_func() and set_filter_func() decide the
 * order and what is shown. Click a swatch to walk through the arrangements.
 *
 *   bin/php-gtk4 examples/demo.php GtkFlowBox
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkFlowBox',
    'children that wrap into as many lines as they need',
    function (GtkWindow $win): GtkWidget {
        $store = Demo::store();
        $box = new GtkFlowBox();
        $box->set_selection_mode(GtkSelectionMode::Single);
        $box->set_min_children_per_line(2);
        $box->set_max_children_per_line(5);
        $box->set_row_spacing(8);
        $box->set_column_spacing(8);

        $box->bind_model($store, static function (GObject $item): GtkWidget {
            $label = Demo::label(sprintf(
                "<b>%s</b>\n<span foreground=\"%s\">%d</span>",
                htmlspecialchars(Demo::name($item)),
                Demo::MUTED,
                Demo::born($item),
            ));
            $label->set_size_request(120, 60);
            return $label;
        });

        $textOf = static function (GtkFlowBoxChild $child): string {
            $inner = $child->get_child();
            return $inner instanceof GtkLabel ? $inner->get_text() : '';
        };

        /** @var list<array{string, int, int, ?callable, ?callable}> $steps */
        $steps = [
            ['2 to 5 per line, as the model has them', 2, 5, null, null],
            ['sorted by name', 2, 5,
                static fn(GtkFlowBoxChild $a, GtkFlowBoxChild $b): int
                    => strcmp($textOf($a), $textOf($b)), null],
            ['one per line - a list, in other words', 1, 1, null, null],
            ['only the A names', 2, 5, null,
                static fn(GtkFlowBoxChild $c): bool => str_starts_with($textOf($c), 'A')],
        ];

        $at = 0;
        $apply = function () use (&$at, $steps, $box): void {
            [$name, $min, $max, $sort, $filter] = $steps[$at % count($steps)];
            $box->set_min_children_per_line($min);
            $box->set_max_children_per_line($max);
            $box->set_sort_func($sort);
            $box->set_filter_func($filter);
            Demo::status($name . ' - click a child for the next arrangement');
        };
        $box->connect('child-activated', function () use (&$at, $apply): void {
            $at++;
            $apply();
        });
        $apply();

        $scroller = new GtkScrolledWindow();
        $scroller->set_child($box);
        $scroller->set_vexpand(true);
        return $scroller;
    },
    520,
    340,
);
