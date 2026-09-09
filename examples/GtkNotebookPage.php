<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkLabel;
use Gtk4\GtkNotebook;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkNotebookPage - the per-child record a GtkNotebook keeps.
 *
 * Never constructed by you: GtkNotebook::get_page($child) returns it, and
 * get_pages() is a list model of them. Its only method is get_child(); the
 * rest - tab_label, position, reorderable, detachable, tab_expand - are
 * GObject properties read as $page->tab_label. A timer walks the pages and
 * prints each record.
 *
 *   bin/php-gtk4 examples/demo.php GtkNotebookPage
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkNotebookPage',
    'the per-child record a notebook keeps, reachable through get_page()',
    function (GtkWindow $win): GtkWidget {
        $notebook = new GtkNotebook();
        $notebook->set_vexpand(true);
        foreach (['Alpha', 'Beta', 'Gamma'] as $i => $title) {
            $face = Demo::label("<span size='xx-large'><b>$title</b></span>");
            $notebook->append_page($face, new GtkLabel($title));
            $notebook->set_tab_reorderable($face, $i % 2 === 0);
        }

        $status = Demo::label();
        $status->set_halign(GtkAlign::Start);
        $step = 0;
        $show = function () use ($notebook, $status, &$step): void {
            $n = $notebook->get_n_pages();
            $index = $step % $n;
            $child = $notebook->get_nth_page($index);
            if ($child === null) {
                return;
            }
            // get_page($child) is the record; asking twice gives the same object.
            $page = $notebook->get_page($child);
            assert($page !== null);  // null only for a widget that is not a page of this notebook
            $same = $notebook->get_page($child) === $page;
            $notebook->set_current_page($index);
            $status->set_markup(sprintf(
                "<tt>get_page(get_nth_page(%d))   of %d\n  get_child() === child   %s\n"
                . "  tab_label        %s\n  position         %d\n  reorderable      %s\n"
                . "  detachable       %s\n  same object twice  %s\n  get_pages()      %s</tt>",
                $index,
                $n,
                $page->get_child() === $child ? 'yes' : 'no',
                htmlspecialchars((string) $page->tab_label),
                (int) $page->position,
                $page->reorderable ? 'true' : 'false',
                $page->detachable ? 'true' : 'false',
                $same ? 'yes' : 'no',
                $notebook->get_pages()::class,
            ));
            Demo::status(sprintf('%s %d: %s', $page::class, $index, (string) $page->tab_label));
        };

        $show();
        GLib::timeout_add(1300, function () use ($show, &$step): bool {
            $step++;
            $show();
            return true;
        });

        $box = new GtkBox(GtkOrientation::Vertical, 12);
        $box->append($status);
        $box->append($notebook);
        return $box;
    },
    520,
    380,
);
