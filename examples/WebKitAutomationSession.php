<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitAutomationSession - Automation Session.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitAutomationSession doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitAutomationSession
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitAutomationSession',
    'Automation Session.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'get_application_info',
            'get_id',
            'set_application_info',
        ]);
        $label->set_markup("<b>WebKitAutomationSession</b>\n3 generated methods\n<small>$names</small>");
        return $label;
    },
);
