<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GMenu;
use Gtk4\GSimpleAction;
use Gtk4\GtkApplication;
use Gtk4\GtkApplicationWindow;
use Gtk4\GtkButton;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkApplicationWindow - a GtkWindow that is also an action map.
 *
 * Actions added to it (add_action, it is a GActionMap) are reachable as
 * "win.<name>" - from menus and shortcuts - next to the application's
 * "app.<name>" ones, and
 * set_show_menubar(true) renders GtkApplication::set_menubar()'s model as a
 * menu bar inside the window. get_id() is the number the application counts its
 * windows with.
 *
 * The demo mounts pages in a plain GtkWindow, so this page opens a
 * GtkApplicationWindow of its own; the button presents it, its own close button
 * closes it. The readout shows what lookup_action() finds on the window.
 *
 *   bin/php-gtk4 examples/demo.php GtkApplicationWindow
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkApplicationWindow',
    'a GtkWindow that is also an action map',
    function (GtkWindow $win, GtkApplication $app): GtkWidget {
        $label = Demo::label();
        $button = new GtkButton();
        $button->set_child($label);
        $count = 0;

        // The menubar is application-wide; a GtkApplicationWindow shows it when asked.
        $winMenu = new GMenu();
        $winMenu->append('Say hello', 'win.hello');
        $winMenu->append('Close', 'win.close');
        $menubar = new GMenu();
        $menubar->append_submenu('Window', $winMenu);
        $app->set_menubar($menubar);

        $appWin = new GtkApplicationWindow($app);
        $appWin->set_title('GtkApplicationWindow demo');
        $appWin->set_default_size(360, 200);
        $appWin->set_show_menubar(true);
        $face = Demo::label();
        $appWin->set_child($face);

        $render = function () use ($label, $face, $appWin, &$count): void {
            $text = sprintf(
                "<tt>get_id()                 %d\nget_show_menubar()       %s\nlookup_action('hello')   %s\n"
                . "lookup_action('close')   %s\nlookup_action('nothing') %s\nwin.hello fired          %d time(s)</tt>",
                $appWin->get_id(),
                $appWin->get_show_menubar() ? 'true' : 'false',
                $appWin->lookup_action('hello')?->get_name() ?? 'null',
                $appWin->lookup_action('close')?->get_name() ?? 'null',
                $appWin->lookup_action('nothing')?->get_name() ?? 'null',
                $count,
            );
            $label->set_markup("<b>GtkApplicationWindow</b>\n\n$text\n\n<i>click to present the window</i>");
            $face->set_markup("$text\n\n<small>use the Window menu above</small>");
        };

        // "win." actions live on the window, not the application.
        $hello = new GSimpleAction('hello');
        $hello->connect('activate', function () use (&$count, $render): void {
            $count++;
            $render();
        });
        $appWin->add_action($hello);
        $close = new GSimpleAction('close');
        $close->connect('activate', function () use ($appWin): void {
            $appWin->close();
        });
        $appWin->add_action($close);

        $button->connect('clicked', function () use ($appWin, $render): void {
            $appWin->present();
            $render();
        });

        $render();
        return $button;
    },
    560,
    300,
);
