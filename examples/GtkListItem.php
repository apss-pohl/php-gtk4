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
 * Gtk4\GtkListItem - the row a factory fills in.
 *
 * Never constructed from PHP: GTK hands one to `setup` (no item yet, `child` is
 * what you put in it) and to `bind` (`item` and `position` set). It is also
 * where a row's own flags live - selectable and activatable. Every third row
 * below refuses selection, so clicking it leaves the selection where it was.
 *
 *   bin/php-gtk4 examples/demo.php GtkListItem
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkListItem',
    'the row a factory fills in: child, item, position, selectable',
    function (GtkWindow $win): GtkWidget {
        $model = new GtkSingleSelection(new GtkStringList([
            'alpha', 'bravo', 'charlie', 'delta', 'echo', 'foxtrot', 'golf', 'hotel', 'india',
        ]));

        $factory = new GtkSignalListItemFactory();
        $factory->connect('setup', function (GtkSignalListItemFactory $f, GtkListItem $item): void {
            $label = new GtkLabel();
            $label->set_halign(GtkAlign::Start);
            $item->set_child($label);
        });
        $factory->connect('bind', function (GtkSignalListItemFactory $f, GtkListItem $item): void {
            $position = $item->get_position();
            $object = $item->get_item();
            // Every third row is a header: not selectable, not activatable.
            $item->set_selectable($position % 3 !== 0);
            $item->set_activatable($position % 3 !== 0);
            $item->set_accessible_label('row ' . $position);
            $label = $item->get_child();
            if ($label instanceof GtkLabel && $object instanceof GtkStringObject) {
                $label->set_markup(sprintf(
                    '<tt>%2d</tt>  %s%s',
                    $position,
                    htmlspecialchars($object->get_string()),
                    $item->get_selectable() ? '' : '   <small>(not selectable)</small>',
                ));
            }
        });

        $view = new GtkListView($model, $factory);
        $view->set_single_click_activate(true);
        $view->connect('activate', function (GtkListView $v, int $position) use ($model): void {
            $item = $model->get_item($position);
            Demo::status(sprintf(
                'activated %d (%s) - selection is at %d',
                $position,
                $item instanceof GtkStringObject ? $item->get_string() : '?',
                $model->get_selected(),
            ));
        });

        $scroller = new GtkScrolledWindow();
        $scroller->set_policy(GtkPolicyType::Never, GtkPolicyType::Automatic);
        $scroller->set_child($view);
        $scroller->set_vexpand(true);

        $page = new GtkBox(GtkOrientation::Vertical, 8);
        $page->append(Demo::label(
            "<b>one GtkListItem per visible row</b>\n"
            . '<small>rows 0, 3, 6 … set selectable(false) on themselves</small>',
        ));
        $page->append($scroller);
        return $page;
    },
    440,
    400,
);
