<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GMenu;
use Gtk4\GMenuItem;
use Gtk4\GSimpleAction;
use Gtk4\GtkAlign;
use Gtk4\GtkApplication;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkMenuButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkPopoverMenu;
use Gtk4\GtkPopoverMenuFlags;
use Gtk4\GtkSpinner;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkPopoverMenu - a popover that renders a GMenuModel.
 *
 * new_from_model() turns a model into a popover; set_menu_model() swaps it later.
 * An item whose "custom" attribute names an id is a slot for a real widget added
 * with add_child(widget, id) - the spinner here - and removed with remove_child().
 * The GtkMenuButton owns the popover; the readout shows the last item chosen.
 *
 *   bin/php-gtk4 examples/demo.php GtkPopoverMenu
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkPopoverMenu',
    'a popover that renders a GMenuModel',
    function (GtkWindow $win, GtkApplication $app): GtkWidget {
        $readout = Demo::label();
        $chosen = 'nothing yet';

        $pick = new GSimpleAction('popmenu-pick', 's');
        $app->add_action($pick);

        $menu = new GMenu();
        $menu->append('First', 'app.popmenu-pick::first');
        $menu->append('Second', 'app.popmenu-pick::second');
        $slot = new GMenuItem();
        $slot->set_attribute_value('custom', 'busy');    // a slot for add_child()
        $menu->append_item($slot);
        $sub = new GMenu();
        $sub->append('Nested', 'app.popmenu-pick::nested');
        $menu->append_submenu('More', $sub);

        $popover = GtkPopoverMenu::new_from_model($menu);
        $spinner = new GtkSpinner();
        $spinner->set_spinning(true);
        $added = $popover->add_child($spinner, 'busy');

        $button = new GtkMenuButton();
        $button->set_label('GtkPopoverMenu');
        $button->set_popover($popover);
        $button->set_halign(GtkAlign::Center);

        $render = function () use ($readout, $popover, &$chosen, &$added): void {
            $model = $popover->get_menu_model();
            $readout->set_markup(sprintf(
                "<tt>menu_model  %s\nflags       %s\nadd_child   %s</tt>\n\nchosen: <b>%s</b>",
                $model === null ? 'null' : sprintf('%d item(s)', $model->get_n_items()),
                $popover->get_flags() === GtkPopoverMenuFlags::NESTED ? 'NESTED' : 'SLIDING',
                $added ? 'true (spinner in the "busy" slot)' : 'false',
                htmlspecialchars($chosen),
            ));
        };
        $pick->connect('activate', function (GSimpleAction $self, mixed $what) use (&$chosen, $render): void {
            $chosen = is_string($what) ? $what : '?';
            $render();
        });

        // add_child()/remove_child() toggle the custom widget in place. Swapping the
        // model rebuilds the popover's content, so that is shown on a second popover.
        $toggle = GtkButton::new_with_label('remove_child() / add_child()');
        $toggle->connect('clicked', function () use ($popover, $spinner, &$added, $render): void {
            $added = $added ? !$popover->remove_child($spinner) : $popover->add_child($spinner, 'busy');
            $render();
        });

        $alt = new GMenu();
        $alt->append('Only item', 'app.popmenu-pick::only');
        $other = GtkPopoverMenu::new_from_model($alt);
        $second = new GtkMenuButton();
        $second->set_label('set_menu_model()');
        $second->set_popover($other);
        $swap = GtkButton::new_with_label('swap the second model');
        $swap->connect('clicked', function () use ($other, $menu, $alt): void {
            $other->set_menu_model($other->get_menu_model() === $alt ? $menu : $alt);
        });

        $render();
        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $row = new GtkBox(GtkOrientation::Horizontal, 6);
        $row->set_halign(GtkAlign::Center);
        $row->append($button);
        $row->append($second);
        $page->append($row);
        $page->append($toggle);
        $page->append($swap);
        $page->append($readout);
        return $page;
    },
    520,
    360,
);
