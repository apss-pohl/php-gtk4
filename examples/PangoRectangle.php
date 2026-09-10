<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\CairoContext;
use Gtk4\GdkRGBA;
use Gtk4\GtkBox;
use Gtk4\GtkDrawingArea;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkOverlay;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\PangoRectangle;

/*
 * Gtk4\PangoRectangle - where text is. A layout measures its ink (the pixels the glyphs cover)
 * and its logical extents (the line box a caret sits in), and index_to_pos() gives the cell of
 * one character; all three come back as rectangles in Pango units, 1024 to the pixel.
 *
 * The boxes below are drawn over a real GtkLabel, measured from that label's own layout
 * (get_layout()) and shifted by get_layout_offsets(), so they land exactly on the glyphs Pango
 * drew: logical in grey, ink in blue, the third character's cell in red.
 *
 *   bin/php-gtk4 examples/demo.php PangoRectangle
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'PangoRectangle',
    'ink, logical extents and one character cell, drawn over the label that owns them',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel('');
        $label->set_markup('<span size="xx-large">Hamburgefonstiv</span>');

        $boxes = Demo::canvas(360, 90, function (
            GtkDrawingArea $area,
            CairoContext $cr,
            int $width,
            int $height,
        ) use ($label): void {
            $layout = $label->get_layout();
            [$offsetX, $offsetY] = $label->get_layout_offsets();

            // The label sits in the overlay's coordinates, the drawing area in its own; both are
            // the same size here, so the layout offsets are all that separate them.
            $draw = static function (PangoRectangle $units, string $css) use ($cr, $offsetX, $offsetY): void {
                $r = $units->to_pixels();
                $cr->set_source_color(new GdkRGBA($css));
                $cr->set_line_width(1.0);
                $cr->rectangle($offsetX + $r->x + 0.5, $offsetY + $r->y + 0.5, $r->width, $r->height);
                $cr->stroke();
            };

            [$ink, $logical] = $layout->get_extents();
            $draw($logical, '#77767b');            // the line box, where a caret lives
            $draw($ink, '#3584e4');                // what the glyphs actually cover
            $draw($layout->index_to_pos(2), '#e01b24');   // one character's cell
        });
        $boxes->can_target = false;   // the boxes are decoration; clicks belong to the label

        $overlay = new GtkOverlay();
        $overlay->set_child($label);
        $overlay->add_overlay($boxes);

        $facts = Demo::label();
        $layout = $label->get_layout();
        [$ink, $logical] = $layout->get_extents();
        [$pixelInk, $pixelLogical] = $layout->get_pixel_extents();
        $facts->set_markup(sprintf(
            "<tt>get_extents()        ink %d x %d units\n"
            . "get_pixel_extents()  ink %d x %d px  (1024 units to the pixel)\n"
            . "logical              %d x %d px\n"
            . 'index_to_pos(2)      x %d units, %d px wide</tt>',
            $ink->width,
            $ink->height,
            $pixelInk->width,
            $pixelInk->height,
            $pixelLogical->width,
            $pixelLogical->height,
            $layout->index_to_pos(2)->x,
            $layout->index_to_pos(2)->to_pixels()->width,
        ));

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($overlay);
        $page->append($facts);
        return $page;
    },
);
