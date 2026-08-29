<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAdjustment;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkSpinButton;
use Gtk4\GtkSpinType;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkSpinButton - a number entry with +/- arrows.
 *
 * Under the entry sits a GtkAdjustment (value, bounds, increments); the spin
 * button adds digits, wrapping, snapping to ticks and numeric-only input on top.
 * The page has an integer one built with new_with_range() and a two-digit one
 * built from an explicit adjustment; `value-changed` from either updates the
 * label, and a timer spins the first one through the GtkSpinType directions.
 *
 *   bin/php-gtk4 examples/demo.php GtkSpinButton
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkSpinButton',
    'a number entry with arrows - ranges, increments, digits, wrap and spin()',
    function (GtkWindow $win): GtkWidget {
        $status = Demo::label();

        // Integer, 0..100 in steps of 1, page increment 10, wrapping at the ends.
        $count = GtkSpinButton::new_with_range(0.0, 100.0, 1.0);
        $count->set_increments(1.0, 10.0);
        $count->set_wrap(true);
        $count->set_value(42.0);

        // Fractional: adjustment (value, lower, upper, step, page, page_size=0), 2 digits.
        $adj = new GtkAdjustment(1.5, 0.0, 10.0, 0.25, 1.0, 0.0);
        $ratio = new GtkSpinButton($adj, 1.0, 2);
        $ratio->set_snap_to_ticks(true);
        $ratio->set_numeric(true);

        $describe = function () use ($status, $count, $ratio): void {
            [$min, $max] = $count->get_range();
            [$step, $page] = $count->get_increments();
            $status->set_markup(sprintf(
                "<tt>count  %3d   range %g..%g  step %g  page %g  wrap %s\n"
                . 'ratio  %5.2f range %g..%g  digits %d  text "%s"</tt>',
                $count->get_value_as_int(),
                $min,
                $max,
                $step,
                $page,
                $count->get_wrap() ? 'yes' : 'no',
                $ratio->get_value(),
                $ratio->get_adjustment()->get_lower(),
                $ratio->get_adjustment()->get_upper(),
                $ratio->get_digits(),
                htmlspecialchars($ratio->get_text()),
            ));
        };

        foreach (['count' => $count, 'ratio' => $ratio] as $name => $spin) {
            $spin->connect('value-changed', function (GtkSpinButton $self) use ($name, $describe): void {
                Demo::status(sprintf('%s value-changed -> %g', $name, $self->get_value()));
                $describe();
            });
        }

        // spin() is what the arrow buttons call; the timer walks the directions.
        $moves = [
            [GtkSpinType::StepForward, 1.0],
            [GtkSpinType::PageForward, 10.0],
            [GtkSpinType::StepBackward, 1.0],
            [GtkSpinType::End, 0.0],
            [GtkSpinType::StepForward, 1.0],    // wraps around to 0
            [GtkSpinType::Home, 0.0],
            [GtkSpinType::UserDefined, 42.0],
        ];
        $step = 0;
        GLib::timeout_add(900, function () use ($count, $moves, &$step): bool {
            [$type, $amount] = $moves[$step++ % count($moves)];
            $count->spin($type, $amount);
            return true;
        });

        $describe();

        $grid = new GtkBox(GtkOrientation::Vertical, 6);
        $grid->set_halign(GtkAlign::Center);
        foreach (['count (int, wraps)' => $count, 'ratio (2 digits, snaps to 0.25)' => $ratio] as $text => $spin) {
            $row = new GtkBox(GtkOrientation::Horizontal, 12);
            $label = new GtkLabel($text);
            $label->set_size_request(220, -1);
            $label->set_halign(GtkAlign::End);
            $row->append($label);
            $row->append($spin);
            $grid->append($row);
        }

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($grid);
        $page->append($status);
        return $page;
    },
    560,
    260,
);
