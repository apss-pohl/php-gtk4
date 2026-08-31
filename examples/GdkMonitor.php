<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkDisplay;
use Gtk4\GdkMonitor;
use Gtk4\GtkAlign;
use Gtk4\GtkApplication;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkMonitor - one physical output of the display.
 *
 * GdkDisplay::get_monitors() lists them; each knows its geometry in display
 * coordinates, its connector/model strings (where the backend can tell) and
 * its scale. The button opens a window fullscreened on a monitor with
 * GtkWindow::fullscreen_on_monitor().
 *
 *   bin/php-gtk4 examples/demo.php GdkMonitor
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkMonitor',
    'one physical output of the display',
    function (GtkWindow $win, GtkApplication $app): GtkWidget {
        $display = GdkDisplay::get_default();
        $monitors = $display?->get_monitors();

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        if ($display === null || $monitors === null || $monitors->get_n_items() === 0) {
            $page->append(Demo::label('<i>no display / no monitors</i>'));
            return $page;
        }

        for ($i = 0; $i < $monitors->get_n_items(); $i++) {
            $monitor = $monitors->get_item($i);
            if (!$monitor instanceof GdkMonitor) {
                continue;
            }
            $g = $monitor->get_geometry();
            $row = new GtkBox(GtkOrientation::Horizontal, 12);
            // Not Demo::label(): a wrapping label inside a horizontal box makes GTK's
            // height-for-width warn about its own estimate.
            $label = new GtkLabel();
            $label->set_markup(sprintf(
                "<b>%s</b> <small>%s</small>\n<tt>%d×%d</tt> at <tt>%+d%+d</tt>"
                . ' · scale <tt>%.2f</tt> · <tt>%.1f</tt> Hz',
                $monitor->get_connector() ?? "monitor $i",
                htmlspecialchars($monitor->get_model() ?? ''),
                $g->width,
                $g->height,
                $g->x,
                $g->y,
                $monitor->get_scale(),
                $monitor->get_refresh_rate() / 1000,
            ));
            $row->append($label);

            $go = GtkButton::new_with_label('fullscreen here');
            $go->connect('clicked', function () use ($app, $monitor): void {
                // A window of this page's own - never fullscreen the demo window.
                $screen = new GtkWindow();
                $screen->set_application($app);
                $leave = GtkButton::new_with_label('leave fullscreen (close)');
                $leave->connect('clicked', fn() => $screen->close());
                $screen->set_child($leave);
                $screen->fullscreen_on_monitor($monitor);
                $screen->present();
            });
            $row->append($go);
            $row->set_halign(GtkAlign::Center);
            $page->append($row);
        }
        Demo::status($monitors->get_n_items() . ' monitor(s)');
        return $page;
    },
    560,
    300,
);
