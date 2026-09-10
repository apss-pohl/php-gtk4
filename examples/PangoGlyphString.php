<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\CairoContext;
use Gtk4\GdkRGBA;
use Gtk4\GskTextNode;
use Gtk4\GtkBox;
use Gtk4\GtkDrawingArea;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkOverlay;
use Gtk4\GtkSnapshot;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\PangoGlyphString;

/*
 * Gtk4\PangoGlyphString - text after shaping: not characters but glyph indices into one font,
 * each with its advance and offset. Characters and glyphs need not match one to one ("fi" can be
 * one glyph, one Hangul syllable three), which is why the string measures itself with a font -
 * extents() for all of it, extents_range() for a slice, get_width() for the advance sum.
 *
 * Shaping is what GtkSnapshot::append_layout() does on the way to a text node, so the glyphs
 * below come from that: the label's layout snapshotted, the GskTextNode taken apart with
 * get_glyphs(). Each glyph's cell is drawn over the label with extents_range(): one box per
 * glyph, which is not the same as one box per character.
 *
 *   bin/php-gtk4 examples/demo.php PangoGlyphString
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'PangoGlyphString',
    'text after shaping: one cell per glyph, measured with its font',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel('');
        $label->set_markup('<span size="xx-large">Affinity office</span>');

        // The one shaper this binding has: a layout through a snapshot is a text node.
        $shape = static function (GtkLabel $label): ?GskTextNode {
            $snapshot = new GtkSnapshot();
            $snapshot->append_layout($label->get_layout(), new GdkRGBA(Demo::INK));
            $node = $snapshot->to_node();
            return $node instanceof GskTextNode ? $node : null;
        };

        $cells = Demo::canvas(360, 90, function (
            GtkDrawingArea $area,
            CairoContext $cr,
            int $width,
            int $height,
        ) use (
            $label,
            $shape,
        ): void {
            $node = $shape($label);
            if ($node === null) {
                return;
            }
            $glyphs = $node->get_glyphs();
            $font = $node->get_font();
            [$offsetX, $offsetY] = $label->get_layout_offsets();
            // The node's offset is the baseline origin in layout coordinates. A range's extents
            // are relative to the range's own start, y from the baseline, so the pen advances
            // by each glyph's logical width - in units, so rounding does not drift.
            $originX = $offsetX + $node->get_offset()->x;
            $originY = $offsetY + $node->get_offset()->y;
            $advance = 0;

            $cr->set_line_width(1.0);
            for ($i = 0; $i < $node->get_num_glyphs(); $i++) {
                [, $logical] = $glyphs->extents_range($i, $i + 1, $font);
                $r = $logical->to_pixels();
                $x = $originX + intdiv($advance, 1024) + $r->x;
                $cr->set_source_color(new GdkRGBA($i % 2 === 0 ? Demo::ACCENT : Demo::WARN));
                $cr->rectangle($x + 0.5, $originY + $r->y + 0.5, $r->width, $r->height);
                $cr->stroke();
                $advance += $logical->width;
            }
        });
        $cells->can_target = false;

        $overlay = new GtkOverlay();
        $overlay->set_child($label);
        $overlay->add_overlay($cells);

        $facts = Demo::label();
        $node = $shape($label);
        if ($node !== null) {
            $glyphs = $node->get_glyphs();
            [$ink, $logical] = $glyphs->extents($node->get_font());
            $facts->set_markup(sprintf(
                "<tt>%d characters, %d glyphs\nget_width()   %d units = %d px\n"
                . 'extents()     ink %d x %d px, logical %d x %d px</tt>',
                mb_strlen($label->get_text()),
                $node->get_num_glyphs(),
                $glyphs->get_width(),
                intdiv($glyphs->get_width(), 1024),
                $ink->to_pixels()->width,
                $ink->to_pixels()->height,
                $logical->to_pixels()->width,
                $logical->to_pixels()->height,
            ));
        }

        $empty = new PangoGlyphString();
        $empty->set_size(3);
        Demo::status(sprintf(
            'new PangoGlyphString() then set_size(3): get_width() %d - three empty glyphs',
            $empty->get_width(),
        ));

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($overlay);
        $page->append($facts);
        return $page;
    },
);
