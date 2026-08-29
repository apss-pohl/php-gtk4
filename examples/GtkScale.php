<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkBox;
use Gtk4\GtkOrientation;
use Gtk4\GtkPositionType;
use Gtk4\GtkScale;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkScale - a slider.
 *
 * GtkRange with a face: it can print its value next to the trough, draw marks
 * with Pango-markup captions, and highlight the stretch from the origin to the
 * slider. The page has a horizontal one with marks and a vertical one without,
 * `value-changed` from either updating the label, and a timer cycling digits,
 * value position and has-origin so their effect is visible.
 *
 *   bin/php-gtk4 examples/demo.php GtkScale
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkScale',
    'a slider with marks, a printed value and an origin highlight',
    function (GtkWindow $win): GtkWidget {
        // Horizontal, 0..100 in steps of 1, with marks above and below.
        $h = GtkScale::new_with_range(GtkOrientation::Horizontal, 0.0, 100.0, 1.0);
        $h->set_hexpand(true);
        $h->set_value(35.0);
        $h->set_digits(0);
        $h->add_mark(0.0, GtkPositionType::Bottom, 'min');
        $h->add_mark(50.0, GtkPositionType::Bottom, '<i>half</i>');
        $h->add_mark(100.0, GtkPositionType::Bottom, 'max');
        $h->add_mark(75.0, GtkPositionType::Top, null);   // a tick without a caption

        // Vertical, fractional, value printed at the top.
        $v = GtkScale::new_with_range(GtkOrientation::Vertical, 0.0, 1.0, 0.05);
        $v->set_vexpand(true);
        $v->set_value(0.6);
        $v->set_digits(2);
        $v->set_value_pos(GtkPositionType::Top);
        $v->set_inverted(true);           // 1.0 at the top, like a volume slider

        $status = Demo::label();
        $describe = function () use ($h, $v, $status): void {
            $status->set_markup(sprintf(
                "<tt>horizontal  %6.2f  digits %d  value_pos %-6s draw_value %-5s has_origin %s\n"
                . 'vertical    %6.2f  digits %d  value_pos %-6s inverted %s</tt>',
                $h->get_value(),
                $h->get_digits(),
                $h->get_value_pos()->name,
                $h->get_draw_value() ? 'true' : 'false',
                $h->get_has_origin() ? 'true' : 'false',
                $v->get_value(),
                $v->get_digits(),
                $v->get_value_pos()->name,
                $v->get_inverted() ? 'true' : 'false',
            ));
        };

        foreach (['horizontal' => $h, 'vertical' => $v] as $name => $scale) {
            $scale->connect('value-changed', function (GtkScale $self) use ($name, $describe): void {
                Demo::status(sprintf('%s value-changed -> %g', $name, $self->get_value()));
                $describe();
            });
        }

        // Cycle the presentation of the horizontal one.
        $positions = GtkPositionType::cases();
        $step = 0;
        GLib::timeout_add(1200, function () use ($h, $positions, $describe, &$step): bool {
            $h->set_value(fmod($h->get_value() + 15.0, 100.0));
            $h->set_value_pos($positions[$step % count($positions)]);
            $h->set_digits($step % 3);
            $h->set_has_origin($step % 2 === 0);
            $h->set_draw_value($step % 5 !== 4);
            $step++;
            $describe();
            return true;
        });

        $describe();

        $row = new GtkBox(GtkOrientation::Horizontal, 24);
        $row->set_vexpand(true);
        $row->append($h);
        $row->append($v);

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($row);
        $page->append($status);
        return $page;
    },
    600,
    340,
);
