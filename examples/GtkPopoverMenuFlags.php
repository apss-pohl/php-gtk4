<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GMenu;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkMenuButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkPopoverMenu;
use Gtk4\GtkPopoverMenuFlags;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkPopoverMenuFlags - how a GtkPopoverMenu opens submenus.
 *
 * A GFlags bound as an int-constant class. SLIDING (the default) replaces the
 * popover's content with the submenu and offers a back row; NESTED opens the
 * submenu as a second popover next to the first. Both buttons show the same
 * model, one built with new_from_model_full(model, flags), the other switched
 * afterwards with set_flags().
 *
 *   bin/php-gtk4 examples/demo.php GtkPopoverMenuFlags
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkPopoverMenuFlags',
    'how a GtkPopoverMenu opens submenus: sliding or nested',
    function (GtkWindow $win): GtkWidget {
        $deep = new GMenu();
        $deep->append('Deep', 'app.none');
        $sub = new GMenu();
        $sub->append('Nested item', 'app.none');
        $sub->append_submenu('Deeper', $deep);
        $menu = new GMenu();
        $menu->append('Top item', 'app.none');
        $menu->append_submenu('Submenu', $sub);

        $sliding = GtkPopoverMenu::new_from_model_full($menu, GtkPopoverMenuFlags::SLIDING);
        $nested = GtkPopoverMenu::new_from_model($menu);
        $nested->set_flags(GtkPopoverMenuFlags::NESTED);

        $row = new GtkBox(GtkOrientation::Horizontal, 24);
        $row->set_halign(GtkAlign::Center);
        $constants = array_filter(new \ReflectionClass(GtkPopoverMenuFlags::class)->getConstants(), 'is_int');
        foreach ([$sliding, $nested] as $popover) {
            $flags = $popover->get_flags();
            $name = array_search($flags, $constants, true);
            $button = new GtkMenuButton();
            $button->set_label(sprintf('%s (%d)', is_string($name) ? $name : '?', $flags));
            $button->set_popover($popover);
            $row->append($button);
        }

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append(Demo::label(
            "<b>GtkPopoverMenuFlags</b>\n<small>open each and go into \"Submenu\": one slides, one nests</small>",
        ));
        $page->append($row);
        return $page;
    },
    520,
    300,
);
