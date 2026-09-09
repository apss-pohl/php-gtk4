<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\WebKitAuthenticationScheme;

/*
 * Gtk4\WebKitAuthenticationScheme - Enum values representing the authentication scheme.
 *
 * GENERATED skeleton (gen/gir.php): lists every one of the enum's cases. Replace it with a
 * page that shows the enum in action and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitAuthenticationScheme
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitAuthenticationScheme',
    'Enum values representing the authentication scheme.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $rows = array_map(
            static fn(\BackedEnum $c): string => sprintf('%s = %d', $c->name, $c->value),
            WebKitAuthenticationScheme::cases(),
        );
        $label->set_markup("<b>WebKitAuthenticationScheme</b>\n" . implode("\n", $rows));
        return $label;
    },
);
