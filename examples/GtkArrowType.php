<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GMenu;
use Gtk4\GtkAlign;
use Gtk4\GtkArrowType;
use Gtk4\GtkBox;
use Gtk4\GtkMenuButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkArrowType - which way a GtkMenuButton's arrow points, and where its
 * popover opens.
 *
 * Bound as a native PHP enum. GtkMenuButton has it as the `direction` property
 * (the get/set_direction methods are not bound - they clash with GtkWidget's
 * text-direction pair), so the page assigns `$button->direction` and shows every
 * case in turn: the arrow icon flips and the popover opens on that side.
 *
 *   bin/php-gtk4 examples/demo.php GtkArrowType
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkArrowType',
    'which way a GtkMenuButton arrow points',
    function (GtkWindow $win): GtkWidget {
        $menu = new GMenu();
        $menu->append('an item', 'app.none');

        $button = new GtkMenuButton();
        $button->set_menu_model($menu);
        $button->set_always_show_arrow(true);
        $button->set_halign(GtkAlign::Center);
        $button->set_valign(GtkAlign::Center);
        $button->set_vexpand(true);

        $cases = GtkArrowType::cases();
        $step = 0;
        $show = function () use ($button, $cases, &$step): void {
            $dir = $cases[$step % count($cases)];
            $button->direction = $dir;              // the property takes the enum
            $button->set_label(sprintf('GtkArrowType::%s (%d)', $dir->name, $dir->value));
            $current = $button->direction;
            Demo::status(sprintf(
                '%s · %s',
                $current instanceof GtkArrowType ? $current->name : '?',
                implode(' · ', array_map(static fn(GtkArrowType $a): string => $a->name, $cases)),
            ));
        };

        $show();
        GLib::timeout_add(1300, function () use ($show, &$step): bool {
            $step++;
            $show();
            return true;
        });

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append(Demo::label(
            '<small>open the button: the popover follows the direction; None hides the arrow</small>',
        ));
        $page->append($button);
        return $page;
    },
    520,
    360,
);
