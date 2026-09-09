<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GIcon;
use Gtk4\GThemedIcon;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkEntry;
use Gtk4\GtkEntryIconPosition;
use Gtk4\GtkImage;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GIcon - the interface everything that takes an icon is written against.
 *
 * A GtkImage, either side of a GtkEntry, a GMenuItem and the icon theme all take a GIcon rather
 * than a name or a texture, so one icon object serves all of them - and comparing two of them is
 * equal()/hash(), not string comparison. The page hands the same icon to an image and to an
 * entry, and reports what the interface itself can say about it.
 *
 *   bin/php-gtk4 examples/demo.php GIcon
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GIcon',
    'the interface everything that takes an icon is written against',
    function (GtkWindow $win): GtkWidget {
        $column = new GtkBox(GtkOrientation::Vertical, 14);

        $image = new GtkImage();
        $image->set_pixel_size(48);
        $entry = new GtkEntry();
        $entry->set_placeholder_text('the same icon, in an entry');
        $column->append($image);
        $column->append($entry);

        $icons = ['edit-find', 'edit-clear', 'document-save', 'mail-send'];
        $at = 0;
        $apply = function () use (&$at, $icons, $image, $entry): void {
            $icon = new GThemedIcon($icons[$at % count($icons)]);
            // One object, two widgets: both are declared against GIcon.
            $image->set_from_gicon($icon);
            $entry->set_icon_from_gicon(GtkEntryIconPosition::Secondary, $icon);

            $back = $image->get_gicon();
            Demo::status(sprintf(
                'to_string(): %s · hash(): %d · the image gives back an icon that %s',
                $icon->to_string() ?? 'null',
                $icon->hash(),
                $back instanceof GIcon && $back->equal($icon) ? 'equals it' : 'does not equal it',
            ));
        };

        $button = new GtkButton();
        $button->set_child(Demo::label('next icon'));
        $button->connect('clicked', function () use (&$at, $apply): void {
            $at++;
            $apply();
        });
        $column->append($button);
        $apply();
        return $column;
    },
    460,
    260,
);
