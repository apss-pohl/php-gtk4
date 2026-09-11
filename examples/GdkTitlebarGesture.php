<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkTitlebarGesture;
use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkTitlebarGesture
 * the GdkTitlebarGesture enum
 *
 * GENERATED skeleton (gen/gir.php): lists every one of the enum's cases. Replace it
 * with a page that shows the enum in action and move the class out of the
 * 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GdkTitlebarGesture
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkTitlebarGesture',
    'the GdkTitlebarGesture enum',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $rows = array_map(
            static fn(\BackedEnum $c): string => sprintf('%s = %d', $c->name, $c->value),
            GdkTitlebarGesture::cases(),
        );
        $label->set_markup("<b>GdkTitlebarGesture</b>\n" . implode("\n", $rows));
        return $label;
    },
);
