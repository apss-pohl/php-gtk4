<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkLabel;
use Gtk4\GtkListItem;
use Gtk4\GtkListView;
use Gtk4\GtkMultiSelection;
use Gtk4\GtkNoSelection;
use Gtk4\GtkOrientation;
use Gtk4\GtkSignalListItemFactory;
use Gtk4\GtkSingleSelection;
use Gtk4\GtkStringList;
use Gtk4\GtkStringObject;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkSelectionModel - the list model a list widget can select in.
 *
 * The interface every list widget takes: a GListModel plus "which positions are
 * selected", answered as a GtkBitset. The three implementations differ only in
 * what they allow - one item, any number, none - and the button swaps them under
 * the same GtkListView, so the same rows change what clicking them does.
 *
 *   bin/php-gtk4 examples/demo.php GtkSelectionModel
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkSelectionModel',
    'the list model a list widget can select in',
    function (GtkWindow $win): GtkWidget {
        $items = new GtkStringList(['alpha', 'bravo', 'charlie', 'delta', 'echo', 'foxtrot']);

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

        $view = new GtkListView(null, $factory);
        $view->set_vexpand(true);

        // GtkSelectionModel declares what an implementor must provide; the utility methods
        // (get_selection, and GObject's connect) live on the classes that implement it.
        $report = Demo::label();
        $describe = function (
            GtkSingleSelection|GtkMultiSelection|GtkNoSelection $model,
            string $name,
        ) use ($report): void {
            $set = $model->get_selection();
            $positions = [];
            for ($i = 0; $i < $set->get_size(); $i++) {
                $positions[] = $set->get_nth($i);
            }
            $report->set_markup(sprintf(
                "<b>%s</b>\n<tt>get_selection() = {%s}   (%d of %d)</tt>\n"
                . '<small>click the rows - ctrl-click and shift-click work in a multi selection</small>',
                $name,
                implode(', ', $positions),
                $set->get_size(),
                $model->get_n_items(),
            ));
        };

        /** @var list<array{string, GtkSingleSelection|GtkMultiSelection|GtkNoSelection}> $models */
        $models = [
            ['GtkSingleSelection - one item at a time', new GtkSingleSelection($items)],
            ['GtkMultiSelection - any number of items', new GtkMultiSelection($items)],
            ['GtkNoSelection - nothing is ever selected', new GtkNoSelection($items)],
        ];
        foreach ($models as [$name, $model]) {
            $model->connect('selection-changed', function () use ($model, $name, $describe): void {
                $describe($model, $name);
            });
        }

        $step = 0;
        $use = function () use ($view, $models, &$step, $describe): void {
            [$name, $model] = $models[$step % count($models)];
            $view->set_model($model);
            $describe($model, $name);
        };
        $button = GtkButton::new_with_label('next selection model');
        $button->connect('clicked', function () use (&$step, $use): void {
            $step++;
            $use();
        });

        $use();
        $page = new GtkBox(GtkOrientation::Vertical, 8);
        $page->append($report);
        $page->append($view);
        $page->append($button);
        return $page;
    },
    500,
    400,
);
