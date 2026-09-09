<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkBox - the layout container.
 *
 * GTK 4 dropped GtkContainer: a window or a button holds exactly one child, and
 * this is what holds several. The application you are looking at is three of
 * them - header row, sidebar column, content - so this page shows the API on a
 * box of its own instead: appending, spacing, homogeneity, orientation, and who
 * gets the space nobody asked for.
 *
 *   bin/php-gtk4 examples/demo.php GtkBox
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkBox',
    'the layout container - what holds more than one widget',
    function (GtkWindow $win): GtkWidget {
        $demo = new GtkBox(GtkOrientation::Horizontal, 6);
        $demo->set_vexpand(true);

        // Three children with different expand flags: only the middle one grows.
        $left = new GtkLabel('append()');
        $middle = new GtkLabel('hexpand');
        $middle->set_hexpand(true);
        $right = GtkButton::new_with_label('remove me');
        foreach ([$left, $middle, $right] as $child) {
            $child->add_css_class('card');
            $demo->append($child);
        }
        // prepend() puts one at the front instead.
        $demo->prepend(new GtkLabel('prepend()'));

        $status = Demo::label();
        $describe = function () use ($demo, $status): void {
            $names = array_map(
                static fn(GtkWidget $child): string => $child instanceof GtkLabel
                    ? $child->get_text()
                    : (string) ($child instanceof GtkButton ? $child->get_label() : '?'),
                $demo->get_children(),
            );
            $status->set_markup(sprintf(
                "<tt>orientation  %s\nspacing      %d\nhomogeneous  %s\nchildren     %s</tt>",
                $demo->get_orientation()->name,
                $demo->get_spacing(),
                $demo->get_homogeneous() ? 'true' : 'false',
                htmlspecialchars(implode(' | ', $names)),
            ));
        };

        // remove() is also how you move a child: GTK inserts, it never reparents.
        $right->connect('clicked', function (GtkButton $self) use ($demo, $describe): void {
            $demo->remove($self);
            $describe();
        });

        // Cycle spacing / homogeneous / orientation so the effect is visible.
        $step = 0;
        GLib::timeout_add(1400, function () use ($demo, $describe, &$step): bool {
            match ($step++ % 4) {
                0 => $demo->set_spacing(24),
                1 => $demo->set_homogeneous(true),
                2 => $demo->set_orientation(GtkOrientation::Vertical),
                default => (function () use ($demo): void {
                    $demo->set_spacing(6);
                    $demo->set_homogeneous(false);
                    $demo->set_orientation(GtkOrientation::Horizontal);
                })(),
            };
            $describe();
            return true;
        });

        $describe();

        // A box inside a box: they are ordinary widgets.
        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $status->set_halign(GtkAlign::Start);
        $page->append($status);
        $page->append($demo);
        return $page;
    },
    560,
    360,
);
