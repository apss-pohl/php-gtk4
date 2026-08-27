<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkButton;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkWidget - the abstract base every widget inherits.
 *
 * Not instantiable from PHP; this drives the inherited API on a button. The
 * alignment walks through the corners on a timer, so the widget visibly moves
 * around its allocation while the label reports the rest of the state.
 *
 *   bin/php-gtk4 examples/demo.php GtkWidget
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkWidget',
    'the abstract base every widget inherits',
    function (GtkWindow $win): GtkWidget {
        $label = Demo::label();
        $button = new GtkButton();
        $button->set_child($label);

        $button->set_name('demo-widget');
        $button->set_tooltip_text('hover me: GtkWidget::set_tooltip_text()');
        $button->set_size_request(230, 110);
        $button->set_css_classes(['suggested-action', 'pill']);   // GStrv <-> list<string>

        /** @var list<array{GtkAlign, GtkAlign}> $corners */
        $corners = [
            [GtkAlign::Start, GtkAlign::Start],
            [GtkAlign::End, GtkAlign::Start],
            [GtkAlign::Center, GtkAlign::Center],
            [GtkAlign::End, GtkAlign::End],
            [GtkAlign::Start, GtkAlign::End],
            [GtkAlign::Fill, GtkAlign::Fill],
        ];

        $step = 0;
        $show = function () use ($button, $label, $corners, &$step): void {
            [$halign, $valign] = $corners[$step % count($corners)];
            $button->set_halign($halign);        // typed setter, enum only
            $button->valign = $valign;           // same thing as a PHP property
            [$reqWidth, $reqHeight] = $button->get_size_request();   // out params come back as a list
            $root = $button->get_root();
            $label->set_markup(sprintf(
                "<b>%s</b> / <b>%s</b>\n\n<tt>name          %s\n"
                . "size_request  %d x %d\ncss_classes   %s\nsensitive     %s\nvisible       %s\n"
                . "parent        %s\nroot          %s</tt>",
                $button->get_halign()->name,
                $button->valign->name,
                (string) $button->get_name(),
                $reqWidth,
                $reqHeight,
                implode(' ', $button->get_css_classes()),
                $button->get_sensitive() ? 'true' : 'false',
                $button->is_visible() ? 'true' : 'false',
                $label->get_parent() instanceof GtkWidget ? 'GtkButton' : 'none',
                $root instanceof GtkWidget ? $root::class : 'none',
            ));
        };

        $show();
        GLib::timeout_add(900, function () use ($show, &$step): bool {
            $step++;
            $show();
            return true;    // keep the source
        });

        $button->grab_focus();
        return $button;
    },
    520,
    380,
);
