<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\PangoContext;

/*
 * Gtk4\PangoContext - the font settings a layout is measured against.
 *
 * Every widget has one (create_pango_context()), carrying the font description, the text
 * direction and the font map to resolve family names against. A layout built from a widget's
 * context therefore measures in exactly the font the widget will draw with.
 *
 *   bin/php-gtk4 examples/demo.php PangoContext
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'PangoContext',
    'the font settings a layout is measured against',
    function (GtkWindow $win): GtkWidget {
        $context = $win->create_pango_context();
        $description = $context->get_font_description();
        $map = $context->get_font_map();

        return Demo::label(sprintf(
            "<span size=\"x-large\"><b>%s</b></span>\n\n"
            . "<small>base direction <b>%s</b> · gravity <b>%s</b></small>\n"
            . "<small>the font map has <b>%d</b> families</small>\n"
            . '<small>serial %d - it changes when the settings do</small>',
            htmlspecialchars($description?->to_string() ?? 'no font description'),
            $context->get_base_dir()->name,
            $context->get_gravity()->name,
            $map?->get_n_items() ?? 0,
            $context->get_serial(),
        ));
    },
    460,
    220,
);
