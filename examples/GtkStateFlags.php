<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkButton;
use Gtk4\GtkStateFlags;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkStateFlags - the bitmask of a widget state: active, hovered, selected, insensitive, focused, ...
 *
 * GFlags stay ints in PHP with the values as constants. get_state_flags() is read
 * on a timer and decoded bit by bit; hover the button, click it, or wait for the
 * timer to toggle INSENSITIVE on and off.
 *
 *   bin/php-gtk4 examples/demo.php GtkStateFlags
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkStateFlags',
    'a widget state as flag bits',
    function (GtkWindow $win): GtkWidget {
        $label = Demo::label();
        $button = new GtkButton();
        $button->set_child($label);
        $button->set_size_request(320, 80);

        $bits = array_filter(new \ReflectionClass(GtkStateFlags::class)->getConstants(), 'is_int');
        $tick = 0;
        GLib::timeout_add(400, function () use ($button, $label, $bits, &$tick): bool {
            if (++$tick % 8 === 0) {
                $button->set_state_flags(GtkStateFlags::INSENSITIVE, false);
            } elseif ($tick % 8 === 4) {
                $button->unset_state_flags(GtkStateFlags::INSENSITIVE);
            }
            $flags = $button->get_state_flags();
            $set = [];
            foreach ($bits as $name => $bit) {
                if ($bit !== 0 && ($flags & $bit) !== 0) {
                    $set[] = $name;
                }
            }
            $label->set_markup(sprintf(
                "get_state_flags() = <b>%d</b>\n%s",
                $flags,
                $set === [] ? 'NORMAL' : implode(' | ', $set),
            ));
            return true;
        });
        return $button;
    },
    480,
    340,
);
