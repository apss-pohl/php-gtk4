<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkScrolledWindow;
use Gtk4\GtkViewport;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkViewport - the adapter that makes any widget scrollable.
 *
 * A GtkScrolledWindow can only scroll a GtkScrollable; for everything else it
 * silently wraps the child in a GtkViewport. This page builds that viewport by
 * hand, so its hadjustment/vadjustment are ours to drive: a timeout pans the
 * grid of labels, and the button toggles scroll_to_focus.
 *
 *   bin/php-gtk4 examples/demo.php GtkViewport
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkViewport',
    'the adapter that makes any widget scrollable',
    function (GtkWindow $win): GtkWidget {
        $scrolled = new GtkScrolledWindow();
        $scrolled->set_vexpand(true);
        $scrolled->set_has_frame(true);

        // The explicit viewport shares the scrolled window's adjustments.
        $viewport = new GtkViewport($scrolled->get_hadjustment(), $scrolled->get_vadjustment());
        $scrolled->set_child($viewport);

        // A wide and tall grid of cells: both axes have somewhere to go.
        $rows = new GtkBox(GtkOrientation::Vertical, 4);
        for ($y = 0; $y < 20; $y++) {
            $row = new GtkBox(GtkOrientation::Horizontal, 4);
            for ($x = 0; $x < 12; $x++) {
                $cell = new GtkLabel(sprintf('%d,%d', $x, $y));
                $cell->set_size_request(60, 28);
                $cell->add_css_class('card');
                $row->append($cell);
            }
            $rows->append($row);
        }
        $viewport->set_child($rows);

        $status = Demo::label();
        $describe = function () use ($viewport, $scrolled, $status): void {
            $h = $viewport->get_hadjustment();
            $v = $viewport->get_vadjustment();
            $child = $scrolled->get_child();
            $status->set_markup(sprintf(
                "<tt>child            %s\nhadjustment      %.0f / %.0f\nvadjustment      %.0f / %.0f\n"
                . 'scroll_to_focus  %s</tt>',
                $child === null ? 'null' : $child::class,
                $h?->get_value() ?? 0.0,
                $h?->get_upper() ?? 0.0,
                $v?->get_value() ?? 0.0,
                $v?->get_upper() ?? 0.0,
                $viewport->get_scroll_to_focus() ? 'true' : 'false',
            ));
        };

        // Pan diagonally through the grid via the viewport's own adjustments.
        $t = 0;
        GLib::timeout_add(120, function () use ($viewport, $describe, &$t): bool {
            $t++;
            $h = $viewport->get_hadjustment();
            $v = $viewport->get_vadjustment();
            if ($h !== null && $v !== null) {
                $spanH = max(0.0, $h->get_upper() - $h->get_page_size());
                $spanV = max(0.0, $v->get_upper() - $v->get_page_size());
                $phase = (1 - cos($t / 20)) / 2;
                $h->set_value($spanH * $phase);
                $v->set_value($spanV * $phase);
            }
            $describe();
            return true;
        });

        $toggle = GtkButton::new_with_label('toggle scroll_to_focus');
        $toggle->connect('clicked', function () use ($viewport, $describe): void {
            $viewport->set_scroll_to_focus(!$viewport->get_scroll_to_focus());
            $describe();
        });

        $describe();

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $status->set_halign(GtkAlign::Start);
        $toggle->set_halign(GtkAlign::Start);
        $page->append($status);
        $page->append($toggle);
        $page->append($scrolled);
        return $page;
    },
    520,
    420,
);
