<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GMenu;
use Gtk4\GMenuItem;
use Gtk4\GSimpleAction;
use Gtk4\GtkAlign;
use Gtk4\GtkApplication;
use Gtk4\GtkBox;
use Gtk4\GtkMenuButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GMenuItem - one menu entry built by hand.
 *
 * GMenu::append() is shorthand for creating a GMenuItem and append_item()-ing it.
 * Building the item yourself is how you set what the shorthand cannot: an action
 * with a typed target (set_action_and_target_value - the radio group here), extra
 * attributes (set_attribute_value; "icon" and "hidden-when" are ones GTK reads),
 * and links to other models (set_submenu, set_section). The menu button shows the
 * result; the readout reads the attributes back with get_attribute_value().
 *
 *   bin/php-gtk4 examples/demo.php GMenuItem
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GMenuItem',
    'one menu entry built by hand',
    function (GtkWindow $win, GtkApplication $app): GtkWidget {
        $readout = Demo::label();
        $readout->set_halign(GtkAlign::Start);
        $log = [];

        // A stateful string action: items whose target matches the state show as radio buttons.
        $size = GSimpleAction::new_stateful('item-size', 's', 'medium');
        $size->connect('change-state', function (GSimpleAction $self, mixed $state) use (&$log): void {
            $self->set_state($state);
            $log[] = 'item-size -> ' . (is_string($state) ? $state : '?');
        });
        $plain = new GSimpleAction('item-plain');
        $plain->connect('activate', function () use (&$log): void {
            $log[] = 'item-plain activated';
        });
        $app->add_action($size);
        $app->add_action($plain);

        $menu = new GMenu();

        // The long way round for what append() does.
        $about = new GMenuItem();
        $about->set_label('About');
        $about->set_detailed_action('app.item-plain');
        $about->set_attribute_value('icon', 'help-about-symbolic');
        $menu->append_item($about);

        // A radio group: same action, one target per item.
        $sizes = new GMenu();
        foreach (['small', 'medium', 'large'] as $s) {
            $item = new GMenuItem(ucfirst($s));
            $item->set_action_and_target_value('app.item-size', $s);
            $sizes->append_item($item);
        }
        $section = GMenuItem::new_section('Size', $sizes);
        $menu->append_item($section);

        // A submenu via set_submenu() on a plain item.
        $more = new GMenu();
        $more->append('Deeper', 'app.item-plain');
        $sub = new GMenuItem('More');
        $sub->set_submenu($more);
        $menu->append_item($sub);

        // new_from_model() copies an existing item; here to move "About" to the end as well.
        $copy = GMenuItem::new_from_model($menu, 0);
        $copy->set_label('About (copy)');
        $menu->append_item($copy);

        $describe = function (string $name, GMenuItem $item): string {
            $parts = [];
            foreach (['label', 'action', 'target', 'icon'] as $attr) {
                $value = $item->get_attribute_value($attr, 's');
                if ($value !== null) {
                    $parts[] = sprintf('%s=%s', $attr, var_export($value, true));
                }
            }
            foreach (['section', 'submenu'] as $link) {
                $linked = $item->get_link($link);
                if ($linked !== null) {
                    $parts[] = sprintf('%s->%d item(s)', $link, $linked->get_n_items());
                }
            }
            return sprintf('%-8s %s', $name, implode(' ', $parts));
        };

        $render = function () use ($readout, $describe, $about, $section, $sub, $copy, $size, &$log): void {
            $rows = [
                $describe('about', $about),
                $describe('section', $section),
                $describe('sub', $sub),
                $describe('copy', $copy),
            ];
            $readout->set_markup(sprintf(
                "<b>GMenuItem</b>\n\n<tt>%s</tt>\n\nitem-size state: <b>%s</b>\n<small><tt>%s</tt></small>",
                htmlspecialchars(implode("\n", $rows)),
                htmlspecialchars(var_export($size->get_state(), true)),
                htmlspecialchars(implode("\n", array_slice($log, -4)) ?: 'pick something from the menu'),
            ));
        };
        foreach ([$size, $plain] as $action) {
            $action->connect('activate', function () use ($render): void {
                $render();
            });
        }

        $open = new GtkMenuButton();
        $open->set_label('items');
        $open->set_menu_model($menu);
        $open->set_halign(GtkAlign::Center);

        $render();
        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($open);
        $page->append($readout);
        return $page;
    },
    600,
    400,
);
