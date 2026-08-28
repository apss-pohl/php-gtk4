<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkButton;
use Gtk4\GtkLabel;
use Gtk4\GtkNaturalWrapMode;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkNaturalWrapMode - how a wrapping label computes its *natural* width.
 *
 * Inherit follows the wrap mode, None asks for the whole text on one line, Word
 * asks for the width of the longest word. Watch the button change size.
 *
 *   bin/php-gtk4 examples/demo.php GtkNaturalWrapMode
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkNaturalWrapMode',
    'how a wrapping label measures its natural width',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $label->set_text('natural wrap mode decides how wide this label wants to be');
        $label->set_wrap(true);
        $button = new GtkButton();
        $button->set_child($label);
        $button->set_halign(GtkAlign::Center);

        $cases = GtkNaturalWrapMode::cases();
        $step = 0;
        $show = function () use ($label, $cases, &$step): void {
            $case = $cases[$step % count($cases)];
            $label->set_natural_wrap_mode($case);
            Demo::status(sprintf('set_natural_wrap_mode(GtkNaturalWrapMode::%s)', $case->name));
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
