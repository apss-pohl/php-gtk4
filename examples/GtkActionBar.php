<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkActionBar;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkActionBar - a bar of secondary actions, at the bottom of a view.
 *
 * Three places to put something: pack_start(), pack_end() and one centre widget. The bar can be
 * revealed and hidden with an animation, which is what set_revealed() is for - a selection mode
 * that appears when there is something selected, in most applications. The button above toggles
 * it; the bar itself reports what was clicked.
 *
 *   bin/php-gtk4 examples/demo.php GtkActionBar
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkActionBar',
    'a bar of secondary actions, revealed when it is wanted',
    function (GtkWindow $win): GtkWidget {
        $column = new GtkBox(GtkOrientation::Vertical, 12);
        $column->set_vexpand(true);

        $content = Demo::label(
            "<span size=\"x-large\"><b>the view</b></span>\n"
            . '<small>the bar below belongs to it, not to the window</small>',
        );
        $content->set_vexpand(true);
        $column->append($content);

        $bar = new GtkActionBar();
        $action = static function (string $name) use ($bar): GtkButton {
            $button = new GtkButton();
            $button->set_child(Demo::label($name));
            $button->connect('clicked', static function () use ($name, $bar): void {
                Demo::status(sprintf('%s · the bar is %s', $name, $bar->get_revealed() ? 'revealed' : 'hidden'));
            });
            return $button;
        };
        $bar->pack_start($action('Delete'));
        $bar->pack_start($action('Move'));
        $bar->set_center_widget(Demo::label('<b>3 selected</b>'));
        $bar->pack_end($action('Cancel'));

        $toggle = new GtkButton();
        $toggle->set_child(Demo::label('hide the bar'));
        $toggle->connect('clicked', static function (GtkButton $b) use ($bar, $toggle): void {
            $revealed = !$bar->get_revealed();
            $bar->set_revealed($revealed);
            $toggle->set_child(Demo::label($revealed ? 'hide the bar' : 'reveal the bar'));
            Demo::status('set_revealed(' . ($revealed ? 'true' : 'false') . ')');
        });
        $column->append($toggle);
        $column->append($bar);

        Demo::status('click an action, or hide the bar');
        return $column;
    },
    460,
    300,
);
