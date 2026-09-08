<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkFlowBox;
use Gtk4\GtkFlowBoxChild;
use Gtk4\GtkSelectionMode;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkFlowBoxChild - the wrapper a GtkFlowBox puts around every child.
 *
 * append() takes any widget and wraps it in one of these unless it already is one, which is why
 * get_child_at_index() answers with a GtkFlowBoxChild and not with the label that was appended.
 * The wrapper is what carries the position and the selection. The left half of this page appends
 * labels and lets GTK wrap them; the right half appends the wrappers itself.
 *
 *   bin/php-gtk4 examples/demo.php GtkFlowBoxChild
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkFlowBoxChild',
    'the wrapper a flow box puts around every child',
    function (GtkWindow $win): GtkWidget {
        $box = new GtkFlowBox();
        $box->set_selection_mode(GtkSelectionMode::Single);
        $box->set_max_children_per_line(3);
        $box->set_row_spacing(8);
        $box->set_column_spacing(8);

        // Appended as plain labels: GTK wraps each one.
        foreach (['wrapped', 'by', 'GTK'] as $text) {
            $label = Demo::label('<b>' . $text . '</b>');
            $label->set_size_request(110, 50);
            $box->append($label);
        }
        // Appended as wrappers built here, so the page owns them.
        foreach (['built', 'by', 'PHP'] as $text) {
            $child = new GtkFlowBoxChild();
            $inner = Demo::label('<i>' . $text . '</i>');
            $inner->set_size_request(110, 50);
            $child->set_child($inner);
            $box->append($child);
        }

        $box->connect('child-activated', static function (GtkFlowBox $b, GtkFlowBoxChild $child): void {
            $inner = $child->get_child();
            Demo::status(sprintf(
                'child %d of 6 · selected: %s · its own child is a %s',
                $child->get_index(),
                $child->is_selected() ? 'yes' : 'no',
                $inner === null ? 'nothing' : $inner::class,
            ));
        });
        Demo::status('every child is a GtkFlowBoxChild - click one');
        return $box;
    },
    460,
    280,
);
