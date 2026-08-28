<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkButton;
use Gtk4\GtkLabel;
use Gtk4\GtkTextDirection;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkTextDirection - left-to-right or right-to-left layout for one widget.
 *
 * set_direction() flips the meaning of "start": a label aligned to GtkAlign::Start
 * sits on the left in Ltr and on the right in Rtl. None means "inherit the default".
 *
 *   bin/php-gtk4 examples/demo.php GtkTextDirection
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkTextDirection',
    'per-widget left-to-right / right-to-left layout',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $label->set_text('◀ start');
        $label->set_halign(GtkAlign::Start);
        $label->set_hexpand(true);
        $button = new GtkButton();
        $button->set_child($label);
        $button->set_size_request(320, 60);

        $cases = GtkTextDirection::cases();
        $step = 0;
        $show = function () use ($label, $cases, &$step): void {
            $case = $cases[$step % count($cases)];
            $label->set_direction($case);
            Demo::status(sprintf(
                'set_direction(GtkTextDirection::%s); get_direction() = %s; default = %s',
                $case->name,
                $label->get_direction()->name,
                GtkWidget::get_default_direction()->name,
            ));
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
