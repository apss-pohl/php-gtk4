<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkCornerType;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkPolicyType;
use Gtk4\GtkScrolledWindow;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkCornerType - which corner the scrolled window's child sits in.
 *
 * GtkScrolledWindow::set_placement() takes a corner: the child goes there and
 * the scrollbars take the opposite edges. TopLeft is the default (bars right
 * and bottom); the page cycles the four cases on a window with both bars
 * forced on, so you can watch them swap sides.
 *
 *   bin/php-gtk4 examples/demo.php GtkCornerType
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkCornerType',
    'which corner the scrolled child sits in - the scrollbars take the others',
    function (GtkWindow $win): GtkWidget {
        $scrolled = new GtkScrolledWindow();
        $scrolled->set_vexpand(true);
        $scrolled->set_has_frame(true);
        $scrolled->set_policy(GtkPolicyType::Always, GtkPolicyType::Always);
        $scrolled->set_overlay_scrolling(false);

        $grid = new GtkBox(GtkOrientation::Vertical, 4);
        for ($y = 0; $y < 12; $y++) {
            $row = new GtkBox(GtkOrientation::Horizontal, 4);
            for ($x = 0; $x < 10; $x++) {
                $cell = new GtkLabel(sprintf('%d,%d', $x, $y));
                $cell->set_size_request(64, 28);
                $cell->add_css_class('card');
                $row->append($cell);
            }
            $grid->append($row);
        }
        $scrolled->set_child($grid);

        $cases = GtkCornerType::cases();
        $step = 0;
        $status = Demo::label();
        $show = function () use ($scrolled, $cases, $status, &$step): void {
            $corner = $cases[$step % count($cases)];
            $scrolled->set_placement($corner);
            $status->set_markup(sprintf(
                "placement <b>%s</b> <small>(%d)</small>\n<small>%s</small>",
                $scrolled->get_placement()->name,
                $corner->value,
                implode(' · ', array_map(static fn(GtkCornerType $c): string => $c->name, $cases)),
            ));
        };

        $show();
        GLib::timeout_add(1200, function () use ($show, &$step): bool {
            $step++;
            $show();
            return true;
        });

        Demo::status('unset_placement() goes back to ' . GtkCornerType::TopLeft->name);

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $status->set_halign(GtkAlign::Center);
        $page->append($status);
        $page->append($scrolled);
        return $page;
    },
    480,
    400,
);
