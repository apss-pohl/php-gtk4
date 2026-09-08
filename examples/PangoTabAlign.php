<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\PangoTabAlign;

/*
 * Gtk4\PangoTabAlign
 * PangoTabAlign specifies where the text appears relative to the tab stop…
 *
 * GENERATED skeleton (gen/gir.php): lists every one of the enum's cases. Replace it
 * with a page that shows the enum in action and move the class out of the
 * 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php PangoTabAlign
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'PangoTabAlign',
    'PangoTabAlign specifies where the text appears relative to the tab stop…',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $rows = array_map(
            static fn(\BackedEnum $c): string => sprintf('%s = %d', $c->name, $c->value),
            PangoTabAlign::cases(),
        );
        $label->set_markup("<b>PangoTabAlign</b>\n" . implode("\n", $rows));
        return $label;
    },
);
