<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GMenu;
use Gtk4\GMenuModel;
use Gtk4\GSimpleAction;
use Gtk4\GtkAlign;
use Gtk4\GtkApplication;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkMenuButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GMenuModel - the read side of a menu: items, their attributes, their links.
 *
 * A menu model is a tree of items; each item carries attributes ("label", "action",
 * "target") and links to other models ("section", "submenu"). GMenu is the writable
 * implementation, this page reads one back: get_n_items(), get_item_attribute_value(),
 * get_item_link() and the items-changed signal that fires on every mutation. The
 * GtkMenuButton at the top shows the same model as GTK renders it; the button below
 * mutates it so items-changed has something to report.
 *
 *   bin/php-gtk4 examples/demo.php GMenuModel
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GMenuModel',
    'the read side of a menu: items, attributes and links',
    function (GtkWindow $win, GtkApplication $app): GtkWidget {
        $readout = Demo::label();
        $readout->set_halign(GtkAlign::Start);
        $last = 'nothing chosen yet';

        // One action for every item; the target tells them apart.
        $pick = new GSimpleAction('model-pick', 's');
        $pick->connect('activate', function (GSimpleAction $self, mixed $what) use (&$last): void {
            $last = is_string($what) ? $what : '?';
        });
        $app->add_action($pick);

        $edit = new GMenu();
        $edit->append('Cut', 'app.model-pick::cut');
        $edit->append('Paste', 'app.model-pick::paste');

        $menu = new GMenu();
        $menu->append('Open', 'app.model-pick::open');
        $menu->append_section('Edit', $edit);
        $menu->append_submenu('Recent', new GMenu());

        $events = [];
        $menu->connect(
            'items-changed',
            function (GMenuModel $self, int $pos, int $removed, int $added) use (&$events): void {
                $events[] = sprintf('items-changed(position=%d, removed=%d, added=%d)', $pos, $removed, $added);
            },
        );

        // Walk the tree the way GTK does: attributes on each item, links recursively.
        $dump = function (GMenuModel $model, int $depth = 0) use (&$dump): string {
            $out = '';
            for ($i = 0; $i < $model->get_n_items(); $i++) {
                $label = $model->get_item_attribute_value($i, 'label', 's');
                $action = $model->get_item_attribute_value($i, 'action', 's');
                $target = $model->get_item_attribute_value($i, 'target', 's');
                $out .= sprintf(
                    "%s[%d] label=%s action=%s target=%s\n",
                    str_repeat('  ', $depth),
                    $i,
                    var_export($label, true),
                    var_export($action, true),
                    var_export($target, true),
                );
                foreach (['section', 'submenu'] as $link) {
                    $child = $model->get_item_link($i, $link);
                    if ($child instanceof GMenuModel) {
                        $pad = str_repeat('  ', $depth);
                        $out .= sprintf("%s  %s -> %d item(s)\n", $pad, $link, $child->get_n_items());
                        $out .= $dump($child, $depth + 2);
                    }
                }
            }
            return $out;
        };

        $render = function () use ($readout, $menu, $dump, &$last, &$events): void {
            $readout->set_markup(sprintf(
                "<b>GMenuModel</b> · get_n_items() = %d · is_mutable() = %s\n\n<tt>%s</tt>\n"
                . "chosen: <b>%s</b>\n\n<small><tt>%s</tt></small>",
                $menu->get_n_items(),
                $menu->is_mutable() ? 'true' : 'false',
                htmlspecialchars(rtrim($dump($menu))),
                htmlspecialchars($last),
                htmlspecialchars(implode("\n", array_slice($events, -4)) ?: 'no items-changed yet'),
            ));
        };

        // The model rendered: open it and pick something.
        $open = new GtkMenuButton();
        $open->set_label('open the model');
        $open->set_menu_model($menu);
        $open->set_halign(GtkAlign::Center);
        $pick->connect('activate', function () use ($render): void {
            $render();
        });

        // Mutate the model so items-changed fires; the popover follows the model live.
        $step = 0;
        $mutate = GtkButton::new_with_label('mutate the model');
        $mutate->connect('clicked', function () use ($menu, &$step, $render): void {
            match ($step++ % 3) {
                0 => $menu->prepend('New', 'app.model-pick::new'),
                1 => $menu->remove(0),
                default => $menu->items_changed(0, 0, 0),   // a no-op notification, by hand
            };
            $render();
        });

        $render();
        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($open);
        $page->append($mutate);
        $page->append($readout);
        return $page;
    },
    560,
    460,
);
