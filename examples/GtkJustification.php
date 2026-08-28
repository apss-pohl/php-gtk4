<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkButton;
use Gtk4\GtkJustification;
use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkJustification - how the lines of a multi-line label are aligned to each other.
 *
 * A GEnum bound as a PHP enum. The label has three lines of different length, so
 * every case is visible: Left, Right, Center, Fill. A timer steps through them.
 *
 *   bin/php-gtk4 examples/demo.php GtkJustification
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkJustification',
    'how the lines of a multi-line label align',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $label->set_markup("short\na somewhat longer line\nmedium line");
        $label->set_size_request(300, 90);
        $button = new GtkButton();
        $button->set_child($label);

        $cases = GtkJustification::cases();
        $step = 0;
        $show = function () use ($label, $cases, &$step): void {
            $case = $cases[$step % count($cases)];
            $label->set_justify($case);
            Demo::status(sprintf('set_justify(GtkJustification::%s)', $case->name));
        };
        $show();
        GLib::timeout_add(1500, function () use ($show, &$step): bool {
            $step++;
            $show();
            return true;
        });
        return $button;
    },
    480,
    340,
);
