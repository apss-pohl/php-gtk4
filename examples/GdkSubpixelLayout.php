<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkSubpixelLayout;
use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkSubpixelLayout - This enumeration describes how the red, green and blue components of physical…
 *
 * GENERATED skeleton (gen/gir.php): lists every one of the enum's cases. Replace it with a
 * page that shows the enum in action and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GdkSubpixelLayout
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkSubpixelLayout',
    'This enumeration describes how the red, green and blue components of physical…',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $rows = array_map(
            static fn(\BackedEnum $c): string => sprintf('%s = %d', $c->name, $c->value),
            GdkSubpixelLayout::cases(),
        );
        $label->set_markup("<b>GdkSubpixelLayout</b>\n" . implode("\n", $rows));
        return $label;
    },
);
