<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkLabel;
use Gtk4\GtkListItem;
use Gtk4\GtkListTabBehavior;
use Gtk4\GtkListView;
use Gtk4\GtkOrientation;
use Gtk4\GtkSignalListItemFactory;
use Gtk4\GtkSingleSelection;
use Gtk4\GtkStringList;
use Gtk4\GtkStringObject;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkListTabBehavior - where the Tab key goes inside a list.
 *
 * All leaves the list entirely (the default: a list is one tab stop), Item
 * steps from row to row, Cell steps to every focusable widget inside a row -
 * which is what a GtkColumnView with entries in its cells wants. Tab into the
 * list below and try each case; the rows hold two buttons, so Item and Cell
 * differ.
 *
 *   bin/php-gtk4 examples/demo.php GtkListTabBehavior
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkListTabBehavior',
    'where the Tab key goes inside a list',
    function (GtkWindow $win): GtkWidget {
        $model = new GtkSingleSelection(new GtkStringList(['first', 'second', 'third', 'fourth']));

        $factory = new GtkSignalListItemFactory();
        $factory->connect('setup', function (GtkSignalListItemFactory $f, GtkListItem $item): void {
            $row = new GtkBox(GtkOrientation::Horizontal, 8);
            $label = new GtkLabel();
            $label->set_halign(GtkAlign::Start);
            $label->set_hexpand(true);
            $row->append($label);
            $row->append(GtkButton::new_with_label('edit'));
            $row->append(GtkButton::new_with_label('remove'));
            $item->set_child($row);
        });
        $factory->connect('bind', function (GtkSignalListItemFactory $f, GtkListItem $item): void {
            $row = $item->get_child();
            $object = $item->get_item();
            if ($row instanceof GtkBox && $object instanceof GtkStringObject) {
                $label = $row->get_first_child();
                if ($label instanceof GtkLabel) {
                    $label->set_text($object->get_string());
                }
            }
        });

        $view = new GtkListView($model, $factory);
        $view->set_vexpand(true);

        $caption = Demo::label();
        $cases = GtkListTabBehavior::cases();
        $hints = [
            'All' => 'Tab leaves the list',
            'Item' => 'Tab moves from row to row',
            'Cell' => 'Tab moves to every focusable widget in a row',
        ];
        $step = 0;
        $show = function () use ($view, $caption, $cases, $hints, &$step): void {
            $behavior = $cases[$step % count($cases)];
            $view->set_tab_behavior($behavior);
            $caption->set_markup(sprintf(
                "<b>GtkListTabBehavior::%s (%d)</b>\n<small>%s</small>",
                $behavior->name,
                $behavior->value,
                $hints[$behavior->name],
            ));
        };

        $next = GtkButton::new_with_label('next behavior');
        $next->connect('clicked', function () use (&$step, $show): void {
            $step++;
            $show();
        });

        $show();
        $page = new GtkBox(GtkOrientation::Vertical, 8);
        $page->append($caption);
        $page->append($view);
        $page->append($next);
        return $page;
    },
    480,
    400,
);
