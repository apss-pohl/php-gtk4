<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAdjustment;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkScrollable;
use Gtk4\GtkScrollablePolicy;
use Gtk4\GtkScrolledWindow;
use Gtk4\GtkViewport;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkScrollable - the interface a GtkScrolledWindow talks to.
 *
 * Anything that can scroll itself (GtkViewport, GtkTextView, GtkListView, ...)
 * implements GtkScrollable: two adjustments and two scroll policies. The page
 * puts a GtkViewport inside a scrolled window, swaps in adjustments of its own
 * and drives the vertical one from a timeout - the scrollbar follows because
 * the scrolled window reads the same GtkAdjustment through this interface.
 *
 *   bin/php-gtk4 examples/demo.php GtkScrollable
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkScrollable',
    'the interface between a scrolled window and what it scrolls',
    function (GtkWindow $win): GtkWidget {
        $scrolled = new GtkScrolledWindow();
        $scrolled->set_vexpand(true);
        $scrolled->set_has_frame(true);

        $viewport = new GtkViewport();
        $column = new GtkBox(GtkOrientation::Vertical, 4);
        for ($i = 1; $i <= 30; $i++) {
            $line = new GtkLabel(sprintf('line %02d', $i));
            $line->set_halign(GtkAlign::Start);
            $line->add_css_class('card');
            $column->append($line);
        }
        $viewport->set_child($column);
        $scrolled->set_child($viewport);

        // The interface setters: our adjustment replaces the one GTK made.
        $vadj = new GtkAdjustment(0.0, 0.0, 100.0, 1.0, 10.0, 10.0);
        $viewport->set_vadjustment($vadj);
        $viewport->set_vscroll_policy(GtkScrollablePolicy::Natural);

        $status = Demo::label();
        $describe = function () use ($viewport, $scrolled, $vadj, $status): void {
            $status->set_markup(sprintf(
                "<tt>%s implements %s\nsame adjustment  %s\nvscroll_policy   %s\nvalue            %.0f / %.0f</tt>",
                $viewport::class,
                implode(', ', array_keys(class_implements($viewport) ?: [])),
                $scrolled->get_vadjustment() === $vadj ? 'yes (via the interface)' : 'no',
                $viewport->get_vscroll_policy()->name,
                $vadj->get_value(),
                $vadj->get_upper(),
            ));
        };
        $vadj->connect('value-changed', function () use ($describe): void {
            $describe();
        });

        // Bounce the value between the ends: the viewport and its scrollbar move.
        $dir = 1.0;
        GLib::timeout_add(100, function () use ($vadj, &$dir): bool {
            $max = max(0.0, $vadj->get_upper() - $vadj->get_page_size());
            $next = $vadj->get_value() + $dir * max(4.0, $max / 40);
            if ($next >= $max || $next <= 0.0) {
                $dir = -$dir;
            }
            $vadj->set_value(min($max, max(0.0, $next)));
            return true;
        });

        $describe();
        Demo::status(GtkScrollable::class . ' is a PHP interface');

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $status->set_halign(GtkAlign::Start);
        $page->append($status);
        $page->append($scrolled);
        return $page;
    },
    520,
    420,
);
