<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\WebKitJavascriptError;

/*
 * Gtk4\WebKitJavascriptError - Enum values used to denote errors happening when executing JavaScript
 *
 * GENERATED skeleton (gen/gir.php): lists every one of the enum's cases. Replace it with a
 * page that shows the enum in action and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitJavascriptError
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitJavascriptError',
    'Enum values used to denote errors happening when executing JavaScript',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $rows = array_map(
            static fn(\BackedEnum $c): string => sprintf('%s = %d', $c->name, $c->value),
            WebKitJavascriptError::cases(),
        );
        $label->set_markup("<b>WebKitJavascriptError</b>\n" . implode("\n", $rows));
        return $label;
    },
);
