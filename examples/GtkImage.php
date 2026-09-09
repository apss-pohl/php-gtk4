<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkIconSize;
use Gtk4\GtkImage;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkImage - the widget that shows an icon.
 *
 * A GtkImage holds one of nothing, an icon name, a GIcon or a paintable, and
 * get_storage_type() tells which. This page shows a named icon from the theme
 * and cycles it through the GtkIconSize cases and a few explicit pixel sizes;
 * the button below swaps the icon name, clear() empties it again.
 *
 *   bin/php-gtk4 examples/demo.php GtkImage
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkImage',
    'the widget that shows an icon - by name, size or pixel size',
    function (GtkWindow $win): GtkWidget {
        $image = GtkImage::new_from_icon_name('emblem-ok-symbolic');
        $image->set_vexpand(true);
        $image->set_valign(GtkAlign::Center);

        $status = Demo::label();
        $describe = function () use ($image, $status): void {
            $status->set_markup(sprintf(
                "<tt>storage_type  %s\nicon_name     %s\nicon_size     %s\npixel_size    %d</tt>",
                $image->get_storage_type()->name,
                htmlspecialchars($image->get_icon_name() ?? 'null'),
                $image->get_icon_size()->name,
                $image->get_pixel_size(),
            ));
        };

        // Cycle through the size vocabulary: enum sizes first, then pixel sizes (-1 = unset).
        $sizes = [GtkIconSize::Inherit, GtkIconSize::Normal, GtkIconSize::Large, 48, 96, -1];
        $step = 0;
        GLib::timeout_add(1000, function () use ($image, $sizes, $describe, &$step): bool {
            $size = $sizes[$step++ % count($sizes)];
            if ($size instanceof GtkIconSize) {
                $image->set_pixel_size(-1);
                $image->set_icon_size($size);
            } else {
                $image->set_pixel_size($size);
            }
            $describe();
            return true;
        });

        // set_from_icon_name() replaces the content; clear() leaves storage type Empty.
        $names = ['emblem-ok-symbolic', 'dialog-warning-symbolic', 'folder-symbolic', 'starred-symbolic'];
        $next = 0;
        $swap = GtkButton::new_with_label('set_from_icon_name()');
        $swap->connect('clicked', function () use ($image, $names, $describe, &$next): void {
            $image->set_from_icon_name($names[++$next % count($names)]);
            $describe();
        });
        $clear = GtkButton::new_with_label('clear()');
        $clear->connect('clicked', function () use ($image, $describe): void {
            $image->clear();
            $describe();
            Demo::status('cleared - click set_from_icon_name() to bring the icon back');
        });

        $describe();

        $buttons = new GtkBox(GtkOrientation::Horizontal, 6);
        $buttons->set_halign(GtkAlign::Center);
        $buttons->append($swap);
        $buttons->append($clear);

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $status->set_halign(GtkAlign::Start);
        $page->append($status);
        $page->append($image);
        $page->append($buttons);
        return $page;
    },
    480,
    380,
);
