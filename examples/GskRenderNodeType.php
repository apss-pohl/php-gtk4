<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GskRenderNodeType;
use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskRenderNodeType - The type of a node determines what the node is rendering.
 *
 * GENERATED skeleton (gen/gir.php): lists every one of the enum's cases. Replace it with a
 * page that shows the enum in action and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GskRenderNodeType
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskRenderNodeType',
    'The type of a node determines what the node is rendering.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $rows = array_map(
            static fn(\BackedEnum $c): string => sprintf('%s = %d', $c->name, $c->value),
            GskRenderNodeType::cases(),
        );
        $label->set_markup("<b>GskRenderNodeType</b>\n" . implode("\n", $rows));
        return $label;
    },
);
