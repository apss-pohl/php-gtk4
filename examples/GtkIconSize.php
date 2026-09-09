<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkIconSize;
use Gtk4\GtkImage;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkIconSize - the built-in icon sizes.
 *
 * A GEnum bound as a native PHP enum. Normal is 16px, Large 32px, and Inherit
 * takes whatever the CSS -gtk-icon-size of the parent says. Three images show
 * every case side by side while a fourth walks through them.
 *
 *   bin/php-gtk4 examples/demo.php GtkIconSize
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkIconSize',
    'built-in icon sizes - Inherit, Normal, Large',
    function (GtkWindow $win): GtkWidget {
        $row = new GtkBox(GtkOrientation::Horizontal, 24);
        $row->set_halign(GtkAlign::Center);
        foreach (GtkIconSize::cases() as $case) {
            $image = GtkImage::new_from_icon_name('weather-clear-symbolic');
            $image->set_icon_size($case);       // typed setter takes the enum, never an int
            $column = new GtkBox(GtkOrientation::Vertical, 6);
            $column->append($image);
            $column->append(Demo::label(sprintf('<small>%s (%d)</small>', $case->name, $case->value)));
            $row->append($column);
        }

        // One image cycles through the cases so the change itself is visible.
        $cycling = GtkImage::new_from_icon_name('weather-clear-symbolic');
        $cycling->set_vexpand(true);
        $cycling->set_valign(GtkAlign::Center);
        $cases = GtkIconSize::cases();
        $step = 0;
        $show = function () use ($cycling, $cases, &$step): void {
            $case = $cases[$step % count($cases)];
            $cycling->icon_size = $case;        // property access takes it too
            Demo::status(sprintf(
                'set_icon_size(GtkIconSize::%s) - get_icon_size() = %s',
                $case->name,
                $cycling->get_icon_size()->name,
            ));
        };
        $show();
        GLib::timeout_add(1000, function () use ($show, &$step): bool {
            $step++;
            $show();
            return true;
        });

        $page = new GtkBox(GtkOrientation::Vertical, 18);
        $page->append($row);
        $page->append($cycling);
        return $page;
    },
    440,
    320,
);
