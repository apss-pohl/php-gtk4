<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkPaned;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkPaned - two children and a draggable divider.
 *
 * The start child sits left (or top), the end child right (or bottom), and the
 * user drags the handle between them. The page slides the divider on a timer,
 * reports get_position(), and the buttons flip wide_handle, shrink and the
 * orientation so you can see what each flag lets the handle do.
 *
 *   bin/php-gtk4 examples/demo.php GtkPaned
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkPaned',
    'two children and a draggable divider between them',
    function (GtkWindow $win): GtkWidget {
        $paned = new GtkPaned(GtkOrientation::Horizontal);
        $paned->set_vexpand(true);

        $start = Demo::label('<b>start child</b>');
        $start->add_css_class('card');
        $start->set_size_request(60, 60);
        $end = Demo::label('<b>end child</b>');
        $end->add_css_class('card');
        $end->set_size_request(60, 60);
        $paned->set_start_child($start);
        $paned->set_end_child($end);
        $paned->set_position(200);

        $status = Demo::label();
        $describe = function () use ($paned, $status): void {
            $status->set_markup(sprintf(
                "<tt>orientation        %s\nposition           %d\nwide_handle        %s\n"
                . "shrink_start_child %s\nresize_end_child   %s</tt>",
                $paned->get_orientation()->name,
                $paned->get_position(),
                $paned->get_wide_handle() ? 'true' : 'false',
                $paned->get_shrink_start_child() ? 'true' : 'false',
                $paned->get_resize_end_child() ? 'true' : 'false',
            ));
        };

        // The divider walks back and forth: set_position() is in pixels from the start.
        $target = 100;
        GLib::timeout_add(900, function () use ($paned, $describe, &$target): bool {
            $target = $target >= 300 ? 100 : $target + 50;
            $paned->set_position($target);
            $describe();
            Demo::status('position ' . $paned->get_position());
            return true;
        });

        $wide = GtkButton::new_with_label('wide handle');
        $wide->connect('clicked', function () use ($paned, $describe): void {
            $paned->set_wide_handle(!$paned->get_wide_handle());
            $describe();
        });
        $shrink = GtkButton::new_with_label('shrink start child');
        $shrink->connect('clicked', function () use ($paned, $describe): void {
            $paned->set_shrink_start_child(!$paned->get_shrink_start_child());
            $describe();
        });
        $flip = GtkButton::new_with_label('flip orientation');
        $flip->connect('clicked', function () use ($paned, $describe): void {
            $paned->set_orientation(match ($paned->get_orientation()) {
                GtkOrientation::Horizontal => GtkOrientation::Vertical,
                GtkOrientation::Vertical => GtkOrientation::Horizontal,
            });
            $describe();
        });

        $describe();

        $buttons = new GtkBox(GtkOrientation::Horizontal, 6);
        $buttons->append($wide);
        $buttons->append($shrink);
        $buttons->append($flip);

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $status->set_halign(GtkAlign::Start);
        $page->append($status);
        $page->append($buttons);
        $page->append($paned);
        return $page;
    },
    560,
    400,
);
