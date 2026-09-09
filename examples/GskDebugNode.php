<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskDebugNode - A render node that emits a debugging message when drawing its child node.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GskDebugNode doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GskDebugNode
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskDebugNode',
    'A render node that emits a debugging message when drawing its child node.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'get_child',
            'get_message',
        ]);
        $label->set_markup("<b>GskDebugNode</b>\n3 generated methods\n<small>$names</small>");
        return $label;
    },
);
