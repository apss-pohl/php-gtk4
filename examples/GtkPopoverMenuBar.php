<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GMenu;
use Gtk4\GMenuItem;
use Gtk4\GSimpleAction;
use Gtk4\GtkAlign;
use Gtk4\GtkApplication;
use Gtk4\GtkBox;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkPopoverMenuBar;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkPopoverMenuBar - a menu bar built from a GMenuModel.
 *
 * Every top-level submenu of the model becomes a bar entry; the entries open as
 * GtkPopoverMenus. This is the in-page counterpart of GtkApplication::set_menubar()
 * (see GtkApplicationWindow): a bar you place yourself. Items trigger app actions;
 * add_child() drops a real widget into an item carrying a "custom" attribute.
 *
 *   bin/php-gtk4 examples/demo.php GtkPopoverMenuBar
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkPopoverMenuBar',
    'a menu bar built from a GMenuModel',
    function (GtkWindow $win, GtkApplication $app): GtkWidget {
        $readout = Demo::label();
        $chosen = 'nothing yet';

        $pick = new GSimpleAction('bar-pick', 's');
        $app->add_action($pick);

        $file = new GMenu();
        $file->append('New', 'app.bar-pick::new');
        $file->append('Open…', 'app.bar-pick::open');
        $file->append('Quit', 'app.bar-pick::quit');
        $help = new GMenu();
        $help->append('About', 'app.bar-pick::about');
        $badge = new GMenuItem();
        $badge->set_attribute_value('custom', 'version');
        $help->append_item($badge);

        $model = new GMenu();
        $model->append_submenu('File', $file);
        $model->append_submenu('Help', $help);

        $bar = GtkPopoverMenuBar::new_from_model($model);
        $version = new GtkLabel('php-gtk4 ' . \Gtk4\VERSION);
        $version->add_css_class('dim-label');
        $added = $bar->add_child($version, 'version');

        $render = function () use ($readout, $bar, &$chosen, $added): void {
            $bar_model = $bar->get_menu_model();
            $readout->set_markup(sprintf(
                "<b>GtkPopoverMenuBar</b>\n<tt>get_menu_model()  %d top-level entries\nadd_child()       %s</tt>\n\n"
                . "chosen: <b>%s</b>\n\n<small>open File or Help in the bar above</small>",
                $bar_model === null ? 0 : $bar_model->get_n_items(),
                $added ? 'true (a label under Help)' : 'false',
                htmlspecialchars($chosen),
            ));
        };
        $pick->connect('activate', function (GSimpleAction $self, mixed $what) use (&$chosen, $render): void {
            $chosen = is_string($what) ? $what : '?';
            $render();
        });

        $render();
        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $bar->set_halign(GtkAlign::Start);
        $page->append($bar);
        $page->append($readout);
        return $page;
    },
    520,
    320,
);
