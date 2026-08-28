<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkButton;
use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\PangoEllipsizeMode;

/*
 * Gtk4\PangoEllipsizeMode - where a label that is too narrow for its text puts the "…".
 *
 * A Pango enum, generated alongside the GTK ones because GtkLabel::set_ellipsize()
 * takes it. The label is limited to 14 characters; None, Start, Middle and End
 * each cut the sentence differently.
 *
 *   bin/php-gtk4 examples/demo.php PangoEllipsizeMode
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'PangoEllipsizeMode',
    'where a too-narrow label puts the ellipsis',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $label->set_text('The quick brown fox jumps over the lazy dog');
        $label->set_max_width_chars(14);
        $button = new GtkButton();
        $button->set_child($label);

        $cases = PangoEllipsizeMode::cases();
        $step = 0;
        $show = function () use ($label, $cases, &$step): void {
            $case = $cases[$step % count($cases)];
            $label->set_ellipsize($case);
            Demo::status(sprintf('set_ellipsize(PangoEllipsizeMode::%s)', $case->name));
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
