<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkButton;
use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\PangoWrapMode;

/*
 * Gtk4\PangoWrapMode - where a wrapping label may break a line: between words, anywhere, or words first.
 *
 * With wrap on and a narrow width, Word keeps words intact, Char breaks anywhere and
 * WordChar breaks words only when a single word does not fit the line.
 *
 *   bin/php-gtk4 examples/demo.php PangoWrapMode
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'PangoWrapMode',
    'where a wrapping label may break its lines',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $label->set_text('Supercalifragilisticexpialidocious words wrap differently');
        $label->set_wrap(true);
        $label->set_max_width_chars(12);
        $button = new GtkButton();
        $button->set_child($label);

        $cases = PangoWrapMode::cases();
        $step = 0;
        $show = function () use ($label, $cases, &$step): void {
            $case = $cases[$step % count($cases)];
            $label->set_wrap_mode($case);
            Demo::status(sprintf('set_wrap_mode(PangoWrapMode::%s)', $case->name));
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
