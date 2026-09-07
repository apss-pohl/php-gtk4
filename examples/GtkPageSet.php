<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkPageSet;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkPageSet - See also gtk_print_job_set_page_set().
 *
 * GENERATED skeleton (gen/gir.php): lists every one of the enum's cases. Replace it with a
 * page that shows the enum in action and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GtkPageSet
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkPageSet',
    'See also gtk_print_job_set_page_set().',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $rows = array_map(
            static fn(\BackedEnum $c): string => sprintf('%s = %d', $c->name, $c->value),
            GtkPageSet::cases(),
        );
        $label->set_markup("<b>GtkPageSet</b>\n" . implode("\n", $rows));
        return $label;
    },
);
