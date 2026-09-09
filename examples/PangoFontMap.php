<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\PangoFontMap;

/*
 * Gtk4\PangoFontMap - the fonts this machine has, as a list model.
 *
 * The map is what resolves a family name to something drawable, and it is itself a GListModel
 * of font families - so its size is the number of families installed. PHP never builds one: the
 * backend does, and PangoContext::get_font_map() hands it over.
 *
 *   bin/php-gtk4 examples/demo.php PangoFontMap
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'PangoFontMap',
    'the fonts this machine has, as a list model',
    function (GtkWindow $win): GtkWidget {
        $map = $win->create_pango_context()->get_font_map();
        if (!$map instanceof PangoFontMap) {
            return Demo::label('<b>this context has no font map</b>');
        }

        Demo::status(sprintf(
            'serial %d · a GListModel of %s',
            $map->get_serial(),
            $map->get_item_type(),
        ));
        return Demo::label(sprintf(
            "<span size=\"xx-large\"><b>%d</b></span>\n<small>font families on this machine</small>\n\n"
            . '<small>the map is a GListModel, so get_item() walks them</small>',
            $map->get_n_items(),
        ));
    },
    460,
    200,
);
