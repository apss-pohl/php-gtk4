<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitFormSubmissionRequest - Represents a form submission request.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitFormSubmissionRequest doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitFormSubmissionRequest
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitFormSubmissionRequest',
    'Represents a form submission request.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'list_text_fields',
            'submit',
        ]);
        $label->set_markup("<b>WebKitFormSubmissionRequest</b>\n2 generated methods\n<small>$names</small>");
        return $label;
    },
);
