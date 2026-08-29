<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkOrientation;
use Gtk4\GtkSpinButton;
use Gtk4\GtkSpinType;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkSpinType - the argument of GtkSpinButton::spin().
 *
 * Step and page forward/backward use the adjustment's own increments, Home and
 * End jump to the bounds, and UserDefined moves by the amount you pass. The page
 * walks every case on one spin button, so the value visibly jumps around.
 *
 *   bin/php-gtk4 examples/demo.php GtkSpinType
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkSpinType',
    'the directions spin() can move a spin button in',
    function (GtkWindow $win): GtkWidget {
        $spin = GtkSpinButton::new_with_range(0.0, 100.0, 1.0);
        $spin->set_increments(3.0, 25.0);
        $spin->set_value(50.0);
        $spin->set_halign(GtkAlign::Center);

        $status = Demo::label();
        $cases = GtkSpinType::cases();
        $step = 0;

        $show = function () use ($spin, $status, $cases, &$step): void {
            $type = $cases[$step % count($cases)];
            $before = $spin->get_value();
            $spin->spin($type, 7.0);    // the increment only matters for UserDefined
            $status->set_markup(sprintf(
                "spin(<b>%s</b>, 7.0) <small>(%d)</small>\n<tt>%g -> %g</tt>\n\n<small>%s</small>",
                $type->name,
                $type->value,
                $before,
                $spin->get_value(),
                implode(' · ', array_map(static fn(GtkSpinType $t): string => $t->name, $cases)),
            ));
        };

        $show();
        GLib::timeout_add(1000, function () use ($show, &$step): bool {
            $step++;
            $show();
            return true;
        });

        Demo::status(GtkSpinType::from(4)->name . ' = GtkSpinType::from(4)');

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($spin);
        $page->append($status);
        return $page;
    },
    480,
    260,
);
