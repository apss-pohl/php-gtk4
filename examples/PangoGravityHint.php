<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\PangoGravityHint;

/*
 * Gtk4\PangoGravityHint - PangoGravityHint defines how horizontal scripts should behave in a vertical…
 *
 * GENERATED skeleton (gen/gir.php): lists every one of the enum's cases. Replace it with a
 * page that shows the enum in action and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php PangoGravityHint
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'PangoGravityHint',
    'PangoGravityHint defines how horizontal scripts should behave in a vertical…',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $rows = array_map(
            static fn(\BackedEnum $c): string => sprintf('%s = %d', $c->name, $c->value),
            PangoGravityHint::cases(),
        );
        $label->set_markup("<b>PangoGravityHint</b>\n" . implode("\n", $rows));
        return $label;
    },
);
