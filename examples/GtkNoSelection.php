<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkLabel;
use Gtk4\GtkListItem;
use Gtk4\GtkListView;
use Gtk4\GtkNoSelection;
use Gtk4\GtkOrientation;
use Gtk4\GtkSignalListItemFactory;
use Gtk4\GtkStringList;
use Gtk4\GtkStringObject;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkNoSelection - a list that cannot be selected in.
 *
 * A list widget always wants a GtkSelectionModel, so a read-only list gets this
 * one: it passes the model through and answers every selection request with
 * false. Rows still activate (double click, or single with
 * set_single_click_activate()), which is how a menu-like list works.
 *
 *   bin/php-gtk4 examples/demo.php GtkNoSelection
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkNoSelection',
    'a list that cannot be selected in',
    function (GtkWindow $win): GtkWidget {
        $model = new GtkNoSelection(new GtkStringList([
            'php gtk4', 'a list model', 'a factory', 'a widget', 'no selection',
        ]));

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

        $view = new GtkListView($model, $factory);
        $view->set_single_click_activate(true);
        $view->set_vexpand(true);
        $view->connect('activate', function (GtkListView $v, int $position) use ($model): void {
            $item = $model->get_item($position);
            Demo::status(sprintf(
                'activate %d (%s) - and still nothing is selected',
                $position,
                $item instanceof GtkStringObject ? $item->get_string() : '?',
            ));
        });

        $report = Demo::label();
        $describe = function () use ($model, $report): void {
            $report->set_markup(sprintf(
                "<tt>select_item(0, true) = %s\nis_selected(0)       = %s\n"
                . 'get_selection()      = %d position(s)</tt>',
                $model->select_item(0, true) ? 'true' : 'false',
                $model->is_selected(0) ? 'true' : 'false',
                $model->get_selection()->get_size(),
            ));
        };
        $ask = GtkButton::new_with_label('try to select the first row');
        $ask->connect('clicked', $describe);

        $describe();
        $page = new GtkBox(GtkOrientation::Vertical, 8);
        $page->append(Demo::label('<b>rows activate, nothing selects</b>'));
        $page->append($view);
        $page->append($report);
        $page->append($ask);
        return $page;
    },
    480,
    400,
);
