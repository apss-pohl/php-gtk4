<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkCheckButton;
use Gtk4\GtkLabel;
use Gtk4\GtkListItem;
use Gtk4\GtkListView;
use Gtk4\GtkOrientation;
use Gtk4\GtkSignalListItemFactory;
use Gtk4\GtkSingleSelection;
use Gtk4\GtkStringList;
use Gtk4\GtkStringObject;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkSingleSelection - one selected item, as a position and as an object.
 *
 * `selected` is the position (GTK_INVALID_LIST_POSITION, 4294967295, when there
 * is none) and `selected-item` the object at it; both are properties, so
 * `notify::selected` reports every change. Two flags decide what "none" means:
 * autoselect keeps something selected at all times, can-unselect allows clicking
 * the selection away. The check buttons below toggle them.
 *
 *   bin/php-gtk4 examples/demo.php GtkSingleSelection
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkSingleSelection',
    'one selected item, as a position and as an object',
    function (GtkWindow $win): GtkWidget {
        $selection = new GtkSingleSelection(
            new GtkStringList(['alpha', 'bravo', 'charlie', 'delta', 'echo']),
        );

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
        $describe = function () use ($selection, $report): void {
            $position = $selection->get_selected();
            $item = $selection->get_selected_item();
            $report->set_markup(sprintf(
                "<tt>selected       %s\nselected-item  %s\nautoselect     %s\ncan-unselect   %s</tt>",
                $position === 0xFFFFFFFF ? 'GTK_INVALID_LIST_POSITION' : (string) $position,
                $item instanceof GtkStringObject ? '"' . htmlspecialchars($item->get_string()) . '"' : 'null',
                $selection->get_autoselect() ? 'true' : 'false',
                $selection->get_can_unselect() ? 'true' : 'false',
            ));
        };
        $selection->connect('notify::selected', $describe);

        $autoselect = GtkCheckButton::new_with_label('autoselect');
        $autoselect->set_active(true);
        $autoselect->connect('toggled', function () use ($selection, $autoselect, $describe): void {
            $selection->set_autoselect($autoselect->get_active());
            $describe();
        });
        $canUnselect = GtkCheckButton::new_with_label('can-unselect (ctrl-click the selected row)');
        $canUnselect->connect('toggled', function () use ($selection, $canUnselect, $describe): void {
            $selection->set_can_unselect($canUnselect->get_active());
            $describe();
        });

        $view = new GtkListView($selection, $factory);
        $view->set_vexpand(true);

        $describe();
        $page = new GtkBox(GtkOrientation::Vertical, 8);
        $page->append($report);
        $page->append($view);
        $page->append($autoselect);
        $page->append($canUnselect);
        return $page;
    },
    480,
    420,
);
