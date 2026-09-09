<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GListModel;
use Gtk4\GObject;
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
use Gtk4\GtkTreeExpander;
use Gtk4\GtkTreeListModel;
use Gtk4\GtkTreeListRow;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkTreeListModel - a tree, flattened into a list.
 *
 * The replacement for GtkTreeStore: a root model plus a PHP callable that is
 * asked, per item, for that item's children - null means "leaf". Nothing is
 * built until a row is expanded, so a deep tree costs only what is unfolded.
 * The model hands out GtkTreeListRows (position, depth, expanded), and the list
 * shows them like any other list model.
 *
 *   bin/php-gtk4 examples/demo.php GtkTreeListModel
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkTreeListModel',
    'a tree, flattened into a list',
    function (GtkWindow $win): GtkWidget {
        // A tiny "file system": the create function is called with the item that is being
        // expanded and answers with its children, or null for a leaf.
        $children = [
            'src' => ['core', 'Gtk', 'Gdk'],
            'core' => ['object.cpp', 'marshal.cpp', 'boxed.cpp'],
            'Gtk' => ['GtkListView.cpp', 'GtkTreeListModel.cpp'],
            'Gdk' => ['GdkDisplay.cpp'],
            'tests' => ['ListViewTest.php', 'TreeListTest.php'],
        ];
        $asked = 0;

        $model = new GtkTreeListModel(
            new GtkStringList(['src', 'tests', 'README.md']),
            false,
            false,
            function (GObject $item) use ($children, &$asked): ?GListModel {
                $asked++;
                $name = $item instanceof GtkStringObject ? $item->get_string() : '';
                return isset($children[$name]) ? new GtkStringList($children[$name]) : null;
            },
        );

        $factory = new GtkSignalListItemFactory();
        $factory->connect('setup', function (GtkSignalListItemFactory $f, GtkListItem $item): void {
            // The expander draws the triangle and the indent; the label is its child.
            $expander = new GtkTreeExpander();
            $label = new GtkLabel();
            $label->set_halign(GtkAlign::Start);
            $expander->set_child($label);
            $item->set_child($expander);
        });
        $factory->connect('bind', function (
            GtkSignalListItemFactory $f,
            GtkListItem $item,
        ) use (
            $model,
            &$asked
        ): void {
            $expander = $item->get_child();
            $row = $item->get_item();
            if (!$expander instanceof GtkTreeExpander || !$row instanceof GtkTreeListRow) {
                return;
            }
            $expander->set_list_row($row);
            $label = $expander->get_child();
            $object = $row->get_item();
            if ($label instanceof GtkLabel && $object instanceof GtkStringObject) {
                $label->set_markup(sprintf(
                    '%s  <small>depth %d</small>',
                    htmlspecialchars($object->get_string()),
                    $row->get_depth(),
                ));
            }
            Demo::status(sprintf(
                '%d rows shown, the create function was asked %d time(s)',
                $model->get_n_items(),
                $asked,
            ));
        });

        $view = new GtkListView(new GtkSingleSelection($model), $factory);
        $scroller = new GtkScrolledWindow();
        $scroller->set_policy(GtkPolicyType::Never, GtkPolicyType::Automatic);
        $scroller->set_child($view);
        $scroller->set_vexpand(true);

        $page = new GtkBox(GtkOrientation::Vertical, 8);
        $page->append(Demo::label(
            "<b>click the triangles</b>\n"
            . '<small>children are created on the first expand, and never again</small>',
        ));
        $page->append($scroller);
        return $page;
    },
    460,
    420,
);
