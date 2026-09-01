<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GListModel;
use Gtk4\GObject;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkLabel;
use Gtk4\GtkListItem;
use Gtk4\GtkListView;
use Gtk4\GtkOrientation;
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
 * Gtk4\GtkTreeExpander - the triangle and the indent of a tree row.
 *
 * The one widget that makes a GtkTreeListModel look like a tree: give it the
 * row (set_list_row()) and it draws the expander, indents by the row's depth
 * and toggles `expanded` when clicked. Everything else in the row is its child.
 * The timer walks the two indent flags so their effect is visible.
 *
 *   bin/php-gtk4 examples/demo.php GtkTreeExpander
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkTreeExpander',
    'the triangle and the indent of a tree row',
    function (GtkWindow $win): GtkWidget {
        $children = ['docs' => ['PLAN.md', 'TODO.md'], 'src' => ['core', 'Gtk'], 'core' => ['object.cpp']];
        $model = new GtkTreeListModel(
            new GtkStringList(['docs', 'src']),
            false,
            true,   // autoexpand: everything is unfolded, so the indents are visible at once
            fn(GObject $item): ?GListModel => $item instanceof GtkStringObject
                && isset($children[$item->get_string()])
                    ? new GtkStringList($children[$item->get_string()])
                    : null,
        );

        /** @var list<GtkTreeExpander> $expanders */
        $expanders = [];
        $factory = new GtkSignalListItemFactory();
        $factory->connect('setup', function (
            GtkSignalListItemFactory $f,
            GtkListItem $item,
        ) use (&$expanders): void {
            $expander = new GtkTreeExpander();
            $label = new GtkLabel();
            $label->set_halign(GtkAlign::Start);
            $expander->set_child($label);
            $item->set_child($expander);
            $expanders[] = $expander;
        });
        $factory->connect('bind', function (GtkSignalListItemFactory $f, GtkListItem $item): void {
            $expander = $item->get_child();
            $row = $item->get_item();
            if (!$expander instanceof GtkTreeExpander || !$row instanceof GtkTreeListRow) {
                return;
            }
            $expander->set_list_row($row);
            $label = $expander->get_child();
            $object = $row->get_item();
            if ($label instanceof GtkLabel && $object instanceof GtkStringObject) {
                $label->set_text($object->get_string());
            }
        });

        $view = new GtkListView(new GtkSingleSelection($model), $factory);
        $view->set_vexpand(true);

        $caption = Demo::label();
        /** @var list<array{bool, bool}> $combinations */
        $combinations = [[true, true], [false, true], [true, false], [false, false]];
        $step = 0;
        $apply = function () use (&$expanders, $caption, $combinations, &$step): void {
            [$indentDepth, $indentIcon] = $combinations[$step % count($combinations)];
            foreach ($expanders as $expander) {
                $expander->set_indent_for_depth($indentDepth);
                $expander->set_indent_for_icon($indentIcon);
            }
            $caption->set_markup(sprintf(
                "<b>indent-for-depth %s · indent-for-icon %s</b>\n"
                . '<small>the expander is what turns a flat list into a tree</small>',
                $indentDepth ? 'on' : 'off',
                $indentIcon ? 'on' : 'off',
            ));
        };
        $apply();
        GLib::timeout_add(1600, function () use (&$step, $apply): bool {
            $step++;
            $apply();
            return true;
        });

        $page = new GtkBox(GtkOrientation::Vertical, 8);
        $page->append($caption);
        $page->append($view);
        return $page;
    },
    460,
    400,
);
