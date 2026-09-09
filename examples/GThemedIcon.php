<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GThemedIcon;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkIconSize;
use Gtk4\GtkIconTheme;
use Gtk4\GtkImage;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GThemedIcon - an icon named rather than drawn.
 *
 * A themed icon is a list of names, tried in order until the icon theme has one: the constructor
 * takes the name you want, GLib adds its -symbolic variant, and new_with_default_fallbacks()
 * derives the shorter names as well ("folder-music-symbolic" also asks for "folder"). Nothing is
 * loaded until a widget shows it, so the same icon works at any size. Click to walk through
 * some names and watch which ones this machine's theme actually has.
 *
 *   bin/php-gtk4 examples/demo.php GThemedIcon
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GThemedIcon',
    'an icon named rather than drawn, at whatever size a widget wants',
    function (GtkWindow $win): GtkWidget {
        $names = [
            'folder', 'document-open', 'edit-find', 'system-run',
            'dialog-information', 'weather-clear', 'a-name-no-theme-has',
        ];

        $row = new GtkBox(GtkOrientation::Horizontal, 18);
        $row->set_halign(\Gtk4\GtkAlign::Center);
        /** @var list<GtkImage> $images */
        $images = [];
        foreach ([GtkIconSize::Normal, GtkIconSize::Large] as $size) {
            $image = new GtkImage();
            $image->set_icon_size($size);
            $row->append($image);
            $images[] = $image;
        }
        $big = new GtkImage();
        $big->set_icon_size(GtkIconSize::Large);
        $big->set_pixel_size(64);
        $row->append($big);
        $images[] = $big;

        $column = new GtkBox(GtkOrientation::Vertical, 12);
        $column->append($row);
        $label = Demo::label();
        $column->append($label);

        $theme = GtkIconTheme::get_for_display($win->get_display());
        $at = 0;
        $show = function () use (&$at, $names, $images, $label, $theme): void {
            $name = $names[$at % count($names)];
            $icon = GThemedIcon::new_with_default_fallbacks($name);
            foreach ($images as $image) {
                $image->set_from_gicon($icon);
            }
            $label->set_markup(sprintf(
                "<span size=\"x-large\"><b>%s</b></span>\n<small>tries, in order: %s</small>",
                htmlspecialchars($name),
                htmlspecialchars(implode(', ', $icon->get_names())),
            ));
            Demo::status(sprintf(
                'the theme %s this icon · to_string(): %s',
                $theme->has_gicon($icon) ? 'has' : 'does NOT have',
                $icon->to_string() ?? 'null',
            ));
        };

        $button = new GtkButton();
        $button->set_child($column);
        $button->connect('clicked', function () use (&$at, $show): void {
            $at++;
            $show();
        });
        $show();
        return $button;
    },
    480,
    280,
);
