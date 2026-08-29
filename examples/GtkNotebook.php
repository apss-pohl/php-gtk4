<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkLabel;
use Gtk4\GtkNotebook;
use Gtk4\GtkOrientation;
use Gtk4\GtkPositionType;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkNotebook - tabbed pages, the classic way.
 *
 * Every page gets a tab label; the user clicks tabs, drags them around when
 * reorderable, and tears them off when detachable. The buttons call
 * prev_page()/next_page(), a timer walks set_tab_pos() around the four sides,
 * and the status line shows get_current_page() and get_n_pages().
 *
 *   bin/php-gtk4 examples/demo.php GtkNotebook
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkNotebook',
    'tabbed pages - append_page(), next_page(), set_tab_pos()',
    function (GtkWindow $win): GtkWidget {
        $notebook = new GtkNotebook();
        $notebook->set_vexpand(true);
        $notebook->set_scrollable(true);
        foreach (['Editor', 'Terminal', 'Preview', 'Log'] as $i => $title) {
            $face = Demo::label(sprintf(
                "<span size='xx-large'><b>%s</b></span>\n<small>append_page(child, new GtkLabel('%s')) = %d</small>",
                $title,
                $title,
                $i,
            ));
            $notebook->append_page($face, new GtkLabel($title));
            $notebook->set_tab_reorderable($face, true);   // drag tabs into another order
            $notebook->set_tab_detachable($face, $i > 1);  // the last two tear off into a new window
        }

        $status = Demo::label();
        $status->set_halign(GtkAlign::Start);
        $describe = function () use ($notebook, $status): void {
            $child = $notebook->get_nth_page($notebook->get_current_page());
            $status->set_markup(sprintf(
                "<tt>current_page   %d of %d   (%s)\ntab_pos        %s\n"
                . "reorderable    %s   detachable %s\nshow_tabs      %s   show_border %s</tt>",
                $notebook->get_current_page(),
                $notebook->get_n_pages(),
                $child === null ? '-' : htmlspecialchars($notebook->get_tab_label_text($child) ?? '?'),
                $notebook->get_tab_pos()->name,
                $child !== null && $notebook->get_tab_reorderable($child) ? 'true ' : 'false',
                $child !== null && $notebook->get_tab_detachable($child) ? 'true' : 'false',
                $notebook->get_show_tabs() ? 'true ' : 'false',
                $notebook->get_show_border() ? 'true' : 'false',
            ));
        };
        $notebook->connect('switch-page', $describe);

        $prev = GtkButton::new_with_label('prev_page()');
        $prev->connect('clicked', function () use ($notebook, $describe): void {
            $notebook->prev_page();
            $describe();
        });
        $next = GtkButton::new_with_label('next_page()');
        $next->connect('clicked', function () use ($notebook, $describe): void {
            $notebook->next_page();
            $describe();
        });

        // The tabs wander around the four sides.
        $positions = GtkPositionType::cases();
        $step = 0;
        GLib::timeout_add(2000, function () use ($notebook, $positions, $describe, &$step): bool {
            $step++;
            $notebook->set_tab_pos($positions[$step % count($positions)]);
            $describe();
            Demo::status('tab_pos = ' . $notebook->get_tab_pos()->name);
            return true;
        });
        $describe();

        $buttons = new GtkBox(GtkOrientation::Horizontal, 6);
        $buttons->append($prev);
        $buttons->append($next);
        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($status);
        $page->append($buttons);
        $page->append($notebook);
        return $page;
    },
    560,
    400,
);
