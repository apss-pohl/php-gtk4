<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GMenu;
use Gtk4\GSimpleAction;
use Gtk4\GtkAlign;
use Gtk4\GtkApplication;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkMenuButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkPopover;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkMenuButton - a button that opens a popover.
 *
 * What it opens is either a GMenuModel (set_menu_model - GTK builds the
 * GtkPopoverMenu), a popover of your own (set_popover), or whatever
 * set_create_popup_func() builds the moment the button is pressed. The look
 * cycles too: label vs icon, frame, always_show_arrow, primary. active mirrors
 * whether the popover is up; popup()/popdown() drive it from code.
 *
 *   bin/php-gtk4 examples/demo.php GtkMenuButton
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkMenuButton',
    'a button that opens a popover',
    function (GtkWindow $win, GtkApplication $app): GtkWidget {
        $readout = Demo::label();
        $chosen = 'nothing yet';
        $built = 0;

        $pick = new GSimpleAction('mb-pick', 's');
        $app->add_action($pick);
        $menu = new GMenu();
        $menu->append('One', 'app.mb-pick::one');
        $menu->append('Two', 'app.mb-pick::two');

        $button = new GtkMenuButton();
        $button->set_label('menu model');
        $button->set_menu_model($menu);
        $button->set_halign(GtkAlign::Center);
        $button->set_size_request(200, 60);

        $render = function () use ($readout, $button, &$chosen, &$built): void {
            $readout->set_markup(sprintf(
                "<tt>label             %s\nicon_name         %s\nmenu_model        %s\npopover           %s\n"
                . "active            %s\nhas_frame         %s\nalways_show_arrow %s\nprimary           %s\n"
                . "create_popup_func called %d time(s)</tt>\n\nchosen: <b>%s</b>",
                var_export($button->get_label(), true),
                var_export($button->get_icon_name(), true),
                $button->get_menu_model() === null ? 'null' : 'GMenu',
                $button->get_popover() === null ? 'null' : $button->get_popover()::class,
                $button->get_active() ? 'true' : 'false',
                $button->get_has_frame() ? 'true' : 'false',
                $button->get_always_show_arrow() ? 'true' : 'false',
                $button->get_primary() ? 'true' : 'false',
                $built,
                htmlspecialchars($chosen),
            ));
        };
        $pick->connect('activate', function (GSimpleAction $self, mixed $what) use (&$chosen, $render): void {
            $chosen = is_string($what) ? $what : '?';
            $render();
        });
        $button->connect('notify::active', function () use ($render): void {
            $render();
        });

        // Popover content is a button around a label: GTK moves focus into a popover
        // when it opens and wants something focusable there.
        $bubble = function (string $markup): GtkButton {
            $b = new GtkButton();
            $b->set_child(Demo::label($markup));
            return $b;
        };
        $custom = new GtkPopover();
        $custom->set_child($bubble('<b>set_popover()</b>: any GtkPopover'));

        $step = 0;
        $next = GtkButton::new_with_label('next shape, then popup()');
        $next->connect('clicked', function () use ($button, $custom, $bubble, $menu, &$step, &$built, $render): void {
            match ($step++ % 6) {
                0 => (function () use ($button, $custom): void {
                    $button->set_popover($custom);
                    $button->set_label('own popover');
                })(),
                1 => (function () use ($button, $bubble, &$built): void {
                    // Called every time the button is pressed with nothing to show yet.
                    $button->set_popover(null);
                    $button->set_create_popup_func(function (GtkMenuButton $self) use ($bubble, &$built): void {
                        $built++;
                        $pop = new GtkPopover();
                        $pop->set_child($bubble(sprintf('built on demand (#%d)', $built)));
                        $self->set_popover($pop);
                    });
                    $button->set_label('create_popup_func');
                })(),
                2 => (function () use ($button, $menu): void {
                    // A menu model brings its own popover; the create func stays
                    // installed but is only asked when there is no popover at all.
                    $button->set_menu_model($menu);
                    $button->set_icon_name('open-menu-symbolic');
                })(),
                3 => $button->set_has_frame(false),
                4 => (function () use ($button): void {
                    $button->set_always_show_arrow(true);
                    $button->set_primary(true);
                })(),
                default => (function () use ($button): void {
                    $button->set_label('menu model');
                    $button->set_has_frame(true);
                    $button->set_always_show_arrow(false);
                    $button->set_primary(false);
                })(),
            };
            $button->popup();
            $render();
        });
        $close = GtkButton::new_with_label('popdown()');
        $close->connect('clicked', function () use ($button): void {
            $button->popdown();
        });

        $render();
        $row = new GtkBox(GtkOrientation::Horizontal, 6);
        $row->set_halign(GtkAlign::Center);
        $row->append($next);
        $row->append($close);
        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($button);
        $page->append($row);
        $page->append($readout);
        return $page;
    },
    560,
    460,
);
