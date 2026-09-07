<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GskTransformCategory;
use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskTransformCategory - The categories of matrices relevant for GSK and GTK.
 *
 * GENERATED skeleton (gen/gir.php): lists every one of the enum's cases. Replace it with a
 * page that shows the enum in action and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GskTransformCategory
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskTransformCategory',
    'The categories of matrices relevant for GSK and GTK.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $rows = array_map(
            static fn(\BackedEnum $c): string => sprintf('%s = %d', $c->name, $c->value),
            GskTransformCategory::cases(),
        );
        $label->set_markup("<b>GskTransformCategory</b>\n" . implode("\n", $rows));
        return $label;
    },
);
