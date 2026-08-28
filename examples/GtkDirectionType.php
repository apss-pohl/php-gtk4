<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkDirectionType;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkDirectionType - the six ways focus can move: tab forward/backward, up, down, left, right.
 *
 * child_focus() asks a container to move its focus in a direction the way a key
 * press would. A timer walks the four buttons with a different direction each time;
 * the focus ring shows where it ended up.
 *
 *   bin/php-gtk4 examples/demo.php GtkDirectionType
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkDirectionType',
    'the directions focus can move in',
    function (GtkWindow $win): GtkWidget {
        $box = new GtkBox(GtkOrientation::Horizontal, 8);
        foreach (['one', 'two', 'three', 'four'] as $name) {
            $box->append(GtkButton::new_with_label($name));
        }
        $box->set_halign(GtkAlign::Center);

        $cases = GtkDirectionType::cases();
        $step = 0;
        GLib::timeout_add(1200, function () use ($box, $cases, &$step): bool {
            $case = $cases[$step++ % count($cases)];
            $moved = $box->child_focus($case);
            Demo::status(sprintf('child_focus(GtkDirectionType::%s) = %s', $case->name, $moved ? 'true' : 'false'));
            return true;
        });
        return $box;
    },
    480,
    340,
);
