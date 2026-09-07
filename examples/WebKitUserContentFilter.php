<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitUserContentFilter - A compiled set of rules which applied to resource loads.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitUserContentFilter doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitUserContentFilter
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitUserContentFilter',
    'A compiled set of rules which applied to resource loads.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'get_identifier',
            'ref',
            'unref',
        ]);
        $label->set_markup("<b>WebKitUserContentFilter</b>\n3 generated methods\n<small>$names</small>");
        return $label;
    },
);
