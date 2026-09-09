<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkLabel;
use Gtk4\GtkListItem;
use Gtk4\GtkListView;
use Gtk4\GtkOrientation;
use Gtk4\GtkPolicyType;
use Gtk4\GtkScrolledWindow;
use Gtk4\GtkSignalListItemFactory;
use Gtk4\GtkSingleSelection;
use Gtk4\GtkStringList;
use Gtk4\GtkStringObject;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkListView - one widget per visible row, over a list model of any size.
 *
 * The replacement for GtkTreeView's list mode: the widget shows a
 * GtkSelectionModel and asks a GtkListItemFactory for the widget of each row -
 * and only for the rows on screen, so the 500 items below cost a handful of
 * labels. `activate` fires on double click (or single, with
 * set_single_click_activate()) and carries the position, never the item.
 *
 *   bin/php-gtk4 examples/demo.php GtkListView
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkListView',
    'one widget per visible row, over a list model of any size',
    function (GtkWindow $win): GtkWidget {
        $names = [];
        for ($i = 1; $i <= 500; $i++) {
            $names[] = sprintf('item %03d', $i);
        }
        $model = new GtkSingleSelection(new GtkStringList($names));

        // setup builds the row widget once, bind fills it in for the item it now shows -
        // the same label is reused for a different row as the list scrolls.
        $built = 0;
        $factory = new GtkSignalListItemFactory();
        $factory->connect('setup', function (
            GtkSignalListItemFactory $f,
            GtkListItem $item,
        ) use (&$built): void {
            $built++;
            $label = new GtkLabel();
            $label->set_halign(GtkAlign::Start);
            $item->set_child($label);
        });
        $factory->connect('bind', function (GtkSignalListItemFactory $f, GtkListItem $item): void {
            $label = $item->get_child();
            $object = $item->get_item();
            if ($label instanceof GtkLabel && $object instanceof GtkStringObject) {
                $label->set_markup(sprintf(
                    '<tt>%3d</tt>  %s',
                    $item->get_position(),
                    htmlspecialchars($object->get_string()),
                ));
            }
        });

        $view = new GtkListView($model, $factory);
        $view->set_show_separators(true);
        $view->set_single_click_activate(true);
        $view->connect('activate', function (GtkListView $v, int $position) use ($model, &$built): void {
            $item = $model->get_item($position);
            Demo::status(sprintf(
                'activate at %d: %s · %d row widgets built so far',
                $position,
                $item instanceof GtkStringObject ? $item->get_string() : '?',
                $built,
            ));
        });

        $scroller = new GtkScrolledWindow();
        $scroller->set_policy(GtkPolicyType::Never, GtkPolicyType::Automatic);
        $scroller->set_child($view);
        $scroller->set_vexpand(true);

        $page = new GtkBox(GtkOrientation::Vertical, 8);
        $page->append(Demo::label(
            '<b>500 items, a handful of widgets</b>'
            . "\n<small>click a row: the factory built only what you can see</small>",
        ));
        $page->append($scroller);
        return $page;
    },
    460,
    420,
);
