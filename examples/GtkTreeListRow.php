<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GListModel;
use Gtk4\GObject;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkStringList;
use Gtk4\GtkStringObject;
use Gtk4\GtkTreeListModel;
use Gtk4\GtkTreeListRow;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkTreeListRow - one node of a GtkTreeListModel.
 *
 * What the model hands out instead of the items themselves (unless it is a
 * passthrough model): the item, where it sits, how deep it is, whether it can
 * be expanded and whether it is. Expanding a row is what inserts its children
 * into the flat list - this page does it from PHP, without a widget in sight,
 * and prints the list after every step.
 *
 *   bin/php-gtk4 examples/demo.php GtkTreeListRow
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkTreeListRow',
    'one node of a GtkTreeListModel: item, depth, children, expanded',
    function (GtkWindow $win): GtkWidget {
        $children = [
            'animals' => ['birds', 'mammals'],
            'birds' => ['owl', 'raven'],
            'mammals' => ['bat', 'whale'],
            'plants' => ['moss', 'fern'],
        ];
        $model = new GtkTreeListModel(
            new GtkStringList(['animals', 'plants', 'minerals']),
            false,
            false,
            fn(GObject $item): ?GListModel => $item instanceof GtkStringObject
                && isset($children[$item->get_string()])
                    ? new GtkStringList($children[$item->get_string()])
                    : null,
        );

        $face = Demo::label();
        $render = function (string $note) use ($model, $face): void {
            $lines = [];
            for ($i = 0; $i < $model->get_n_items(); $i++) {
                $row = $model->get_row($i);
                if (!$row instanceof GtkTreeListRow) {
                    continue;
                }
                $item = $row->get_item();
                $lines[] = sprintf(
                    '%2d %s%s%s',
                    $row->get_position(),
                    str_repeat('    ', $row->get_depth()),
                    $item instanceof GtkStringObject ? htmlspecialchars($item->get_string()) : '?',
                    $row->is_expandable() ? ($row->get_expanded() ? '  ▾' : '  ▸') : '',
                );
            }
            $face->set_markup(sprintf(
                "<b>%s</b>\n\n<tt>%s</tt>\n\n<small>click for the next step</small>",
                htmlspecialchars($note),
                implode("\n", $lines),
            ));
        };

        $expand = function (int $position, bool $expanded) use ($model): string {
            $row = $model->get_row($position);
            if (!$row instanceof GtkTreeListRow) {
                return "no row at $position";
            }
            $row->set_expanded($expanded);
            $parent = $row->get_parent()?->get_item();
            return sprintf(
                'row %d: depth %d, %s, parent %s, %d child(ren)',
                $position,
                $row->get_depth(),
                $expanded ? 'expanded' : 'collapsed',
                $parent instanceof GtkStringObject ? $parent->get_string() : 'none',
                $row->get_children()?->get_n_items() ?? 0,
            );
        };

        /** @var list<callable(): string> $steps */
        $steps = [
            fn(): string => $expand(0, true),    // animals
            fn(): string => $expand(1, true),    // birds, now at position 1
            fn(): string => $expand(1, false),
            fn(): string => $expand(0, false),
        ];
        $step = 0;
        $button = new GtkButton();
        $button->set_child($face);
        $button->connect('clicked', function () use ($steps, &$step, $render): void {
            $render($steps[$step++ % count($steps)]());
        });

        $render('nothing expanded yet - only the roots are in the list');
        $page = new GtkBox(GtkOrientation::Vertical, 8);
        $page->append($button);
        return $page;
    },
    500,
    420,
);
