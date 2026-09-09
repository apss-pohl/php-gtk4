<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkGrid;
use Gtk4\GtkOrientation;
use Gtk4\GtkPositionType;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkGrid - rows and columns.
 *
 * A GtkBox lines children up; a grid attaches each one to a cell (column, row)
 * with a span (width, height). The page builds a 3x3 of buttons - click one and
 * query_child() reports where it sits - and a timer inserts a row, removes a
 * column, toggles homogeneity and spacing so the layout visibly reflows.
 *
 *   bin/php-gtk4 examples/demo.php GtkGrid
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkGrid',
    'rows and columns - attach() a child to a cell with a span',
    function (GtkWindow $win): GtkWidget {
        $grid = new GtkGrid();
        $grid->set_row_spacing(6);
        $grid->set_column_spacing(6);
        $grid->set_vexpand(true);

        $status = Demo::label();
        $describe = function (string $extra = '') use ($grid, $status): void {
            $status->set_markup(sprintf(
                "<tt>row_spacing        %d\ncolumn_spacing     %d\nrow_homogeneous    %s\ncolumn_homogeneous %s</tt>%s",
                $grid->get_row_spacing(),
                $grid->get_column_spacing(),
                $grid->get_row_homogeneous() ? 'true' : 'false',
                $grid->get_column_homogeneous() ? 'true' : 'false',
                $extra === '' ? '' : "\n" . $extra,
            ));
        };

        // attach(child, column, row, width, height): a 3x3 of buttons.
        for ($row = 0; $row < 3; $row++) {
            for ($col = 0; $col < 3; $col++) {
                $cell = GtkButton::new_with_label(sprintf('%d,%d', $col, $row));
                $cell->set_hexpand(true);
                $cell->set_vexpand(true);
                $cell->connect('clicked', function (GtkButton $self) use ($grid, $describe): void {
                    // query_child() returns [column, row, width, height].
                    [$c, $r, $w, $h] = $grid->query_child($self);
                    $describe(sprintf(
                        '<b>%s</b> at column %d, row %d, span %dx%d',
                        $self->get_label(),
                        $c,
                        $r,
                        $w,
                        $h,
                    ));
                    Demo::status(sprintf('query_child() → [%d, %d, %d, %d]', $c, $r, $w, $h));
                });
                $grid->attach($cell, $col, $row, 1, 1);
            }
        }

        // A wide child spanning all three columns, placed below the last row.
        $wide = GtkButton::new_with_label('attach_next_to(): width 3');
        $wide->add_css_class('suggested-action');
        $grid->attach_next_to($wide, $grid->get_child_at(0, 2), GtkPositionType::Bottom, 3, 1);
        $wide->connect('clicked', function (GtkButton $self) use ($grid, $describe): void {
            [$c, $r, $w, $h] = $grid->query_child($self);
            $describe(sprintf('<b>wide</b> at column %d, row %d, span %dx%d', $c, $r, $w, $h));
        });

        // insert_row()/remove_column() shift the neighbours; the timer plays it out.
        $step = 0;
        GLib::timeout_add(1500, function () use ($grid, $describe, &$step): bool {
            match ($step++ % 6) {
                0 => $grid->insert_row(1),
                1 => $grid->set_row_homogeneous(true),
                2 => $grid->remove_row(1),
                3 => $grid->set_column_spacing(24),
                4 => $grid->set_column_homogeneous(true),
                default => (function () use ($grid): void {
                    $grid->set_row_homogeneous(false);
                    $grid->set_column_homogeneous(false);
                    $grid->set_column_spacing(6);
                })(),
            };
            $describe(sprintf('<small>step %d: baseline row %d</small>', $step, $grid->get_baseline_row()));
            return true;
        });

        $describe();

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $status->set_halign(GtkAlign::Start);
        $page->append($status);
        $page->append($grid);
        return $page;
    },
    560,
    420,
);
