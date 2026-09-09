<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkOrientation;
use Gtk4\GtkSpinButton;
use Gtk4\GtkSpinButtonUpdatePolicy;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkSpinButtonUpdatePolicy - what a spin button does with text it cannot parse.
 *
 * Two cases. With Always the value is updated whenever the text changes, even
 * if it is out of range or not a number; with IfValid the entry keeps the last
 * valid value until what was typed is one. The spin button on this page flips
 * between them; type "abc" or "999" and press Enter to see the difference.
 *
 *   bin/php-gtk4 examples/demo.php GtkSpinButtonUpdatePolicy
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkSpinButtonUpdatePolicy',
    'Always parses anything typed, IfValid keeps the last valid value',
    function (GtkWindow $win): GtkWidget {
        $spin = GtkSpinButton::new_with_range(0.0, 100.0, 5.0);
        $spin->set_value(50.0);
        $spin->set_numeric(false);          // let letters in, so IfValid has something to refuse
        $spin->set_halign(GtkAlign::Center);

        $status = Demo::label();
        $cases = GtkSpinButtonUpdatePolicy::cases();
        $step = 0;

        $show = function () use ($spin, $status, $cases, &$step): void {
            $policy = $cases[$step % count($cases)];
            $spin->set_update_policy($policy);      // typed setter takes the enum
            $status->set_markup(sprintf(
                "policy <b>%s</b> <small>(%d)</small>\nvalue <tt>%g</tt>  text <tt>\"%s\"</tt>\n\n<small>%s</small>",
                $spin->get_update_policy()->name,
                $policy->value,
                $spin->get_value(),
                htmlspecialchars($spin->get_text()),
                implode(' · ', array_map(static fn(GtkSpinButtonUpdatePolicy $p): string => $p->name, $cases)),
            ));
        };

        $spin->connect('value-changed', function () use ($show): void {
            $show();
        });

        $show();
        GLib::timeout_add(2500, function () use ($show, &$step): bool {
            $step++;
            $show();
            return true;
        });

        Demo::status(GtkSpinButtonUpdatePolicy::from(1)->name . ' = GtkSpinButtonUpdatePolicy::from(1)');

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($spin);
        $page->append($status);
        return $page;
    },
    480,
    260,
);
