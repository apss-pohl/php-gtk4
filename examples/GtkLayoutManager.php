<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkLabel;
use Gtk4\GtkLayoutManager;
use Gtk4\GtkOrientation;
use Gtk4\GtkSizeRequestMode;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkLayoutManager - a PHP class that places a widget's children itself.
 *
 * GTK 4 delegates allocation to a layout manager, and a widget that has one never reaches
 * WidgetClass.size_allocate - so overriding vfunc_size_allocate() on a GtkBox subclass does
 * nothing. This is the way in: a GtkLayoutManager subclass whose vfunc_measure() reports the
 * size it wants and whose vfunc_allocate() gives every child a size and a position through
 * GtkWidget::allocate(). Click the button to switch the ring between clockwise and
 * counter-clockwise; only queue_resize() runs, the layout does the rest.
 *
 *   bin/php-gtk4 examples/demo.php GtkLayoutManager
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkLayoutManager',
    'a PHP class that places a widget\'s children itself',
    function (GtkWindow $win): GtkWidget {
        $page = new GtkBox(GtkOrientation::Vertical, 12);

        // A plain GtkBox: set_layout_manager() replaces the GtkBoxLayout it was born with.
        $ring = new GtkBox(GtkOrientation::Horizontal, 0);
        $ring->set_hexpand(true);
        $ring->set_vexpand(true);

        // Places every child on a circle instead of in a row or a column.
        $layout = new class extends GtkLayoutManager {
            public int $direction = 1;

            /** @return array{int, int, int, int} minimum, natural, and the two baselines */
            public function vfunc_measure(GtkWidget $widget, GtkOrientation $orientation, int $for_size): array
            {
                $largest = 0;
                foreach (self::children($widget) as $child) {
                    [, $natural] = $child->measure($orientation, -1);
                    $largest = max($largest, $natural);
                }
                // The ring's diameter plus a child on each side of it.
                $size = 2 * $largest + 160;

                return [$size, $size, -1, -1];
            }

            public function vfunc_allocate(GtkWidget $widget, int $width, int $height, int $baseline): void
            {
                $children = self::children($widget);
                $count = count($children);
                $radius = min($width, $height) / 2 - 60;
                foreach ($children as $i => $child) {
                    [, $w] = $child->measure(GtkOrientation::Horizontal, -1);
                    [, $h] = $child->measure(GtkOrientation::Vertical, -1);
                    $angle = $this->direction * $i / max($count, 1) * 2 * M_PI - M_PI / 2;
                    $child->allocate(
                        $w,
                        $h,
                        -1,
                        (int) round($width / 2 + cos($angle) * $radius - $w / 2),
                        (int) round($height / 2 + sin($angle) * $radius - $h / 2),
                    );
                }
            }

            public function vfunc_get_request_mode(GtkWidget $widget): GtkSizeRequestMode
            {
                return GtkSizeRequestMode::ConstantSize;
            }

            /** @return list<GtkWidget> */
            private static function children(GtkWidget $widget): array
            {
                $children = [];
                for ($c = $widget->get_first_child(); $c !== null; $c = $c->get_next_sibling()) {
                    $children[] = $c;
                }

                return $children;
            }
        };
        $ring->set_layout_manager($layout);

        foreach (['north', 'east', 'south', 'west', 'here'] as $name) {
            $label = new GtkLabel($name);
            $ring->append($label);
        }

        $flip = GtkButton::new_with_label('Reverse the ring');
        $flip->connect('clicked', function () use ($layout, $ring): void {
            $layout->direction = -$layout->direction;
            $ring->queue_resize();          // the layout manager is asked again
            Demo::status($layout->direction === 1 ? 'clockwise' : 'counter-clockwise');
        });

        $page->append($flip);
        $page->append($ring);
        Demo::status('clockwise - the box is laid out by RingLayout, not by GtkBoxLayout');

        return $page;
    },
);
