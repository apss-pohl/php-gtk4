<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkFrame;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkFrame - a border with an optional caption.
 *
 * The caption is a plain string via set_label() or any widget via
 * set_label_widget(); set_label_align() slides it along the top edge. The page
 * cycles all three on a timer and the button inside swaps the caption for a
 * button of its own.
 *
 *   bin/php-gtk4 examples/demo.php GtkFrame
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkFrame',
    'a border with an optional caption - string or widget',
    function (GtkWindow $win): GtkWidget {
        $frame = new GtkFrame('set_label()');
        $frame->set_vexpand(true);

        $inner = GtkButton::new_with_label('swap the caption for a widget');
        $inner->set_halign(GtkAlign::Center);
        $inner->set_valign(GtkAlign::Center);
        $frame->set_child($inner);

        $status = Demo::label();
        $describe = function () use ($frame, $status): void {
            $widget = $frame->get_label_widget();
            $status->set_markup(sprintf(
                "<tt>label        %s\nlabel_align  %.2f\nlabel_widget %s\nchild        %s</tt>",
                htmlspecialchars($frame->get_label() ?? '(null)'),
                $frame->get_label_align(),
                $widget === null ? '(none)' : $widget::class,
                $frame->get_child() === null ? '(none)' : $frame->get_child()::class,
            ));
        };

        // A caption widget: a label with markup, or a button - anything.
        $inner->connect('clicked', function () use ($frame, $describe): void {
            if ($frame->get_label_widget() instanceof GtkButton) {
                $frame->set_label('set_label() again');
            } else {
                $caption = GtkButton::new_with_label('set_label_widget()');
                $caption->add_css_class('flat');
                $caption->connect('clicked', function () use ($frame, $describe): void {
                    $frame->set_label_widget(null);
                    $describe();
                });
                $frame->set_label_widget($caption);
            }
            $describe();
        });

        // label_align walks 0.0 → 1.0: the caption slides from the left edge to the right.
        $step = 0;
        GLib::timeout_add(1000, function () use ($frame, $describe, &$step): bool {
            $align = ($step++ % 5) / 4;
            $frame->set_label_align($align);
            if ($frame->get_label_widget() instanceof GtkLabel) {
                $frame->set_label(sprintf('set_label_align(%.2f)', $align));
            }
            $describe();
            Demo::status(sprintf('label_align %.2f', $frame->get_label_align()));
            return true;
        });

        $describe();

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $status->set_halign(GtkAlign::Start);
        $page->append($status);
        $page->append($frame);
        return $page;
    },
    520,
    360,
);
