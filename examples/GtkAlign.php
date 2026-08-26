<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkButton;
use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkAlign - how a widget sits inside the space it was given.
 *
 * A GEnum bound as a native PHP enum (the case values are checked against GTK's
 * GEnumClass when the extension loads). The button walks through every case on
 * both axes, so you can watch what each one actually does.
 *
 *   bin/php-gtk4 examples/demo.php GtkAlign
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkAlign',
    'how a widget sits inside the space it was given',
    function (GtkWindow $win): GtkWidget {
        $face = new GtkLabel();
        $button = new GtkButton();
        $button->set_child($face);
        $button->set_size_request(180, 70);

        // cases() is the ordinary PHP enum API - these are real enum instances.
        $cases = GtkAlign::cases();
        $step = 0;

        $show = function () use ($button, $face, $cases, &$step): void {
            $halign = $cases[intdiv($step, count($cases)) % count($cases)];
            $valign = $cases[$step % count($cases)];
            $button->set_halign($halign);       // typed setter takes the enum, never an int
            $button->valign = $valign;          // property access takes it too
            $face->set_markup(sprintf(
                "halign <b>%s</b> <small>(%d)</small>\nvalign <b>%s</b> <small>(%d)</small>\n\n<small>%s</small>",
                $halign->name,
                $halign->value,
                $valign->name,
                $valign->value,
                implode(' · ', array_map(static fn(GtkAlign $a): string => $a->name, $cases)),
            ));
        };

        $show();
        GLib::timeout_add(1100, function () use ($show, &$step): bool {
            $step++;
            $show();
            return true;
        });

        // Fill stretches, Center never does - the button changes size as well as place.
        Demo::status(GtkAlign::from(3)->name . ' = GtkAlign::from(3)');
        return $button;
    },
    520,
    400,
);
