<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkBox;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\PangoFontFace;

/*
 * Gtk4\PangoFontFace - one weight-and-style of a family: "Book", "Bold", "Oblique". A family is a
 * GListModel of its faces, and describe() on a face is the description that loads exactly it -
 * each line below is a face of Sans set in itself, with is_synthesized() saying whether the face
 * is a real file or one Pango slants or emboldens on the fly.
 *
 *   bin/php-gtk4 examples/demo.php PangoFontFace
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'PangoFontFace',
    'every face of a family, each set in itself',
    function (GtkWindow $win): GtkWidget {
        $map = $win->create_pango_context()->get_font_map();
        if ($map === null) {
            return Demo::label('<b>this context has no font map</b>');
        }
        $family = $map->get_family('Sans');

        $page = new GtkBox(GtkOrientation::Vertical, 6);
        $real = 0;
        for ($i = 0; $i < $family->get_n_items(); $i++) {
            $face = $family->get_item($i);
            if (!$face instanceof PangoFontFace) {
                continue;
            }
            $real += $face->is_synthesized() ? 0 : 1;
            $line = new GtkLabel('');
            $line->set_markup(sprintf(
                '<span font_desc="%s" size="large">%s</span>  <small>%s%s</small>',
                htmlspecialchars($face->describe()->to_string()),
                htmlspecialchars($face->get_face_name()),
                htmlspecialchars($face->describe()->to_string()),
                $face->is_synthesized() ? ', synthesized' : '',
            ));
            $line->set_xalign(0.0);
            $page->append($line);
        }

        $named = $family->get_face('Bold');
        Demo::status(sprintf(
            "%d faces of %s, %d real · get_face('Bold') → %s",
            $family->get_n_items(),
            $family->get_name(),
            $real,
            $named === null ? 'none' : $named->get_family()->get_name() . ' ' . $named->get_face_name(),
        ));
        return $page;
    },
);
