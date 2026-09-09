<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskRoundedClipNode - A render node applying a rounded rectangle clip to its single child.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GskRoundedClipNode doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GskRoundedClipNode
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskRoundedClipNode',
    'A render node applying a rounded rectangle clip to its single child.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'get_child',
            'get_clip',
        ]);
        $label->set_markup("<b>GskRoundedClipNode</b>\n3 generated methods\n<small>$names</small>");
        return $label;
    },
);
