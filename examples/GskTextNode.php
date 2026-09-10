<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkRGBA;
use Gtk4\GraphenePoint;
use Gtk4\GrapheneRect;
use Gtk4\GskTextNode;
use Gtk4\GtkBox;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkSnapshot;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskTextNode - a run of glyphs in one font and one colour at one offset: the render node
 * every piece of text on screen ends up as. GtkSnapshot::append_layout() makes them from a
 * layout; the constructor makes one from the parts, which is what the widget below does - it
 * takes a node apart with get_font(), get_glyphs() and get_offset(), then builds three new ones
 * from the same glyphs in other colours at other offsets and appends those instead.
 *
 *   bin/php-gtk4 examples/demo.php GskTextNode
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskTextNode',
    'one run of glyphs, taken apart and rebuilt in three colours',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel('');
        $label->set_markup('<span size="xx-large">Render node</span>');

        $shadowed = new class extends GtkWidget {
            public ?GtkLabel $source = null;

            /** @return array{int, int, int, int} */
            public function vfunc_measure(GtkOrientation $orientation, int $for_size): array
            {
                return $orientation === GtkOrientation::Horizontal ? [300, 300, -1, -1] : [90, 90, -1, -1];
            }

            public function vfunc_snapshot(GtkSnapshot $snapshot): void
            {
                if ($this->source === null) {
                    return;
                }
                $w = (float) $this->get_width();
                $h = (float) $this->get_height();
                $snapshot->append_color(new GdkRGBA(Demo::PAPER), GrapheneRect::alloc()->init(0.0, 0.0, $w, $h));

                // GTK's own shaping, then the node it made taken apart.
                $shaper = new GtkSnapshot();
                $shaper->append_layout($this->source->get_layout(), new GdkRGBA(Demo::INK));
                $original = $shaper->to_node();
                if (!$original instanceof GskTextNode) {
                    return;
                }
                $font = $original->get_font();
                $glyphs = $original->get_glyphs();
                $baseline = $original->get_offset();
                [$width, $height] = $this->source->get_layout()->get_pixel_size();
                $x = ($w - $width) / 2;
                $y = ($h - $height) / 2;

                // The same glyphs three times: two offset "shadows", then the text itself.
                $passes = [[6.0, 6.0, Demo::WARN], [3.0, 3.0, Demo::ACCENT], [0.0, 0.0, Demo::INK]];
                foreach ($passes as [$dx, $dy, $css]) {
                    $snapshot->append_node(new GskTextNode(
                        $font,
                        $glyphs,
                        new GdkRGBA($css),
                        new GraphenePoint($x + $baseline->x + $dx, $y + $baseline->y + $dy),
                    ));
                }
                Demo::status(sprintf(
                    '%d glyphs in %s · has_color_glyphs() %s',
                    $original->get_num_glyphs(),
                    $font->describe()->to_string(),
                    $original->has_color_glyphs() ? 'true' : 'false',
                ));
            }
        };
        $shadowed->source = $label;
        $shadowed->set_hexpand(true);

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($shadowed);
        $page->append(Demo::label(
            '<small>the label the glyphs come from is never shown; only its layout is used</small>',
        ));
        return $page;
    },
);
