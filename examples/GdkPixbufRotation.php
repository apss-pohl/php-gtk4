<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkPixbufRotation;
use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkPixbufRotation - The possible rotations which can be passed to gdk_pixbuf_rotate_simple().
 *
 * GENERATED skeleton (gen/gir.php): lists every one of the enum's cases. Replace it with a
 * page that shows the enum in action and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GdkPixbufRotation
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkPixbufRotation',
    'The possible rotations which can be passed to gdk_pixbuf_rotate_simple().',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $rows = array_map(
            static fn(\BackedEnum $c): string => sprintf('%s = %d', $c->name, $c->value),
            GdkPixbufRotation::cases(),
        );
        $label->set_markup("<b>GdkPixbufRotation</b>\n" . implode("\n", $rows));
        return $label;
    },
);
