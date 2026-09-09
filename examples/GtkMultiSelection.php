<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkAlign;
use Gtk4\GtkBitset;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkLabel;
use Gtk4\GtkListItem;
use Gtk4\GtkListView;
use Gtk4\GtkMultiSelection;
use Gtk4\GtkOrientation;
use Gtk4\GtkSignalListItemFactory;
use Gtk4\GtkStringList;
use Gtk4\GtkStringObject;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkMultiSelection - any number of selected items.
 *
 * Ctrl-click and shift-click work in the list; from PHP the same selection is
 * driven with select_item()/select_range()/select_all() and read back as a
 * GtkBitset. set_selection() applies a whole set at once through a mask - only
 * the positions in the mask are touched, which is how a "select every second
 * row" button leaves the rest alone.
 *
 *   bin/php-gtk4 examples/demo.php GtkMultiSelection
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkMultiSelection',
    'any number of selected items, read back as a GtkBitset',
    function (GtkWindow $win): GtkWidget {
        $names = ['alpha', 'bravo', 'charlie', 'delta', 'echo', 'foxtrot', 'golf', 'hotel'];
        $selection = new GtkMultiSelection(new GtkStringList($names));

        $factory = new GtkSignalListItemFactory();
        $factory->connect('setup', function (GtkSignalListItemFactory $f, GtkListItem $item): void {
            $label = new GtkLabel();
            $label->set_halign(GtkAlign::Start);
            $item->set_child($label);
        });
        $factory->connect('bind', function (GtkSignalListItemFactory $f, GtkListItem $item): void {
            $label = $item->get_child();
            $object = $item->get_item();
            if ($label instanceof GtkLabel && $object instanceof GtkStringObject) {
                $label->set_text($object->get_string());
            }
        });

        $report = Demo::label();
        $describe = function (string $note) use ($selection, $names, $report): void {
            $set = $selection->get_selection();
            $marks = '';
            for ($i = 0; $i < count($names); $i++) {
                $marks .= $set->contains($i) ? '■' : '·';
            }
            $report->set_markup(sprintf(
                "<b>%s</b>\n<tt>%s   %d of %d selected</tt>",
                htmlspecialchars($note),
                $marks,
                $set->get_size(),
                count($names),
            ));
        };
        $selection->connect('selection-changed', function () use ($describe): void {
            $describe('selection-changed');
        });

        /** @var list<array{string, callable(): void}> $steps */
        $steps = [
            ['select_range(2, 3)', function () use ($selection): void {
                $selection->select_range(2, 3, true);
            }],
            ['every second row, through set_selection()', function () use ($selection, $names): void {
                $wanted = GtkBitset::new_empty();
                for ($i = 0; $i < count($names); $i += 2) {
                    $wanted->add($i);
                }
                $selection->set_selection($wanted, GtkBitset::new_range(0, count($names)));
            }],
            ['select_all()', function () use ($selection): void {
                $selection->select_all();
            }],
            ['unselect_item(0)', function () use ($selection): void {
                $selection->unselect_item(0);
            }],
            ['unselect_all()', function () use ($selection): void {
                $selection->unselect_all();
            }],
        ];
        $step = 0;
        $button = GtkButton::new_with_label('next selection step');
        $button->connect('clicked', function () use ($steps, &$step, $describe): void {
            [$note, $run] = $steps[$step++ % count($steps)];
            $run();
            $describe($note);
        });

        $view = new GtkListView($selection, $factory);
        $view->set_vexpand(true);

        $describe('nothing selected yet');
        $page = new GtkBox(GtkOrientation::Vertical, 8);
        $page->append($report);
        $page->append($view);
        $page->append($button);
        return $page;
    },
    480,
    420,
);
