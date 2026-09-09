<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitNavigationPolicyDecision - A policy decision for navigation actions.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitNavigationPolicyDecision doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitNavigationPolicyDecision
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitNavigationPolicyDecision',
    'A policy decision for navigation actions.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'get_navigation_action',
        ]);
        $label->set_markup("<b>WebKitNavigationPolicyDecision</b>\n1 generated methods\n<small>$names</small>");
        return $label;
    },
);
