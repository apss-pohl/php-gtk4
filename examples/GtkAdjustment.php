<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAdjustment;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkScrolledWindow;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkAdjustment - a bounded value with increments.
 *
 * The model behind every scrollbar, scale and spin button: value, lower, upper,
 * step/page increment and page size, plus the `value-changed` and `changed`
 * signals. Here one adjustment is the scrolled window's vertical adjustment and
 * also drives the readout; a timeout steps the value, the buttons jump by page
 * and clamp the bounds.
 *
 *   bin/php-gtk4 examples/demo.php GtkAdjustment
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkAdjustment',
    'a bounded value with increments - the model behind scrollbars and scales',
    function (GtkWindow $win): GtkWidget {
        $adj = new GtkAdjustment(0.0, 0.0, 100.0, 1.0, 10.0, 10.0);

        $scrolled = new GtkScrolledWindow();
        $scrolled->set_vexpand(true);
        $scrolled->set_has_frame(true);
        $column = new GtkBox(GtkOrientation::Vertical, 4);
        for ($i = 1; $i <= 40; $i++) {
            $line = new GtkLabel(sprintf('item %02d', $i));
            $line->set_halign(GtkAlign::Start);
            $line->add_css_class('card');
            $column->append($line);
        }
        $scrolled->set_child($column);
        // GTK reconfigures the bounds to fit the child once it is allocated.
        $scrolled->set_vadjustment($adj);

        $status = Demo::label();
        $changes = 0;
        $describe = function () use ($adj, $status, &$changes): void {
            $status->set_markup(sprintf(
                "<tt>value  %6.1f   lower %5.1f   upper %6.1f\nstep   %6.1f   page  %5.1f   size  %6.1f\n"
                . 'value-changed fired %d times</tt>',
                $adj->get_value(),
                $adj->get_lower(),
                $adj->get_upper(),
                $adj->get_step_increment(),
                $adj->get_page_increment(),
                $adj->get_page_size(),
                $changes,
            ));
        };
        $adj->connect('value-changed', function () use ($describe, &$changes): void {
            $changes++;
            $describe();
        });
        // `changed` is the other signal: bounds or page size moved.
        $adj->connect('changed', function () use ($describe): void {
            $describe();
        });

        // set_value() clamps to [lower, upper - page_size] on its own.
        GLib::timeout_add(150, function () use ($adj): bool {
            $next = $adj->get_value() + $adj->get_step_increment() * 2;
            $adj->set_value($next > $adj->get_upper() - $adj->get_page_size() ? 0.0 : $next);
            return true;
        });

        $pageDown = GtkButton::new_with_label('page increment');
        $pageDown->connect('clicked', function () use ($adj): void {
            $adj->set_value($adj->get_value() + $adj->get_page_increment());
        });
        $clamp = GtkButton::new_with_label('clamp_page(0, 50)');
        $clamp->connect('clicked', function () use ($adj): void {
            $adj->clamp_page(0.0, 50.0);
        });
        $reset = GtkButton::new_with_label('configure()');
        $reset->connect('clicked', function () use ($adj): void {
            $adj->configure(0.0, 0.0, 100.0, 1.0, 10.0, 10.0);
        });

        $describe();

        $buttons = new GtkBox(GtkOrientation::Horizontal, 6);
        foreach ([$pageDown, $clamp, $reset] as $button) {
            $buttons->append($button);
        }
        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $status->set_halign(GtkAlign::Start);
        $page->append($status);
        $page->append($buttons);
        $page->append($scrolled);
        return $page;
    },
    540,
    420,
);
