<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkToplevel;
use Gtk4\GdkToplevelLayout;
use Gtk4\GtkApplication;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkToplevelLayout - what a toplevel asks the window manager for when it is presented:
 * maximized, fullscreen (on which monitor), resizable.
 *
 * The value is a boxed record built by hand and handed to GdkToplevel::present(). "Not
 * specified" is a real state: get_maximized() and get_fullscreen() answer null until the
 * setter ran, which is what lets a layout leave one of them alone. The page keeps one
 * layout, edits it with the buttons and presents its own window with it each time.
 *
 *   bin/php-gtk4 examples/demo.php GdkToplevelLayout
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkToplevelLayout',
    'maximized / fullscreen / resizable, as a value GdkToplevel::present() takes',
    function (GtkWindow $win, GtkApplication $app): GtkWidget {
        $label = Demo::label();
        $layout = new GdkToplevelLayout();

        $demo = new GtkWindow();
        $demo->set_application($app);
        $demo->set_title('GdkToplevelLayout demo');
        $demo->set_default_size(320, 200);
        $demo->set_child(Demo::label('Presented with the layout the page on the left edits.'));
        $demo->present();

        $show = static fn(?bool $v): string => $v === null ? 'unspecified' : ($v ? 'true' : 'false');
        $refresh = function () use ($label, $layout, $show): void {
            $monitor = $layout->get_fullscreen_monitor();
            $label->set_markup(sprintf(
                "<b>GdkToplevelLayout</b>\nmaximized <tt>%s</tt>\nfullscreen <tt>%s</tt> on <tt>%s</tt>\n"
                . "resizable <tt>%s</tt>\n\nequal(new layout): <tt>%s</tt>\n\n<i>edit, then present</i>",
                $show($layout->get_maximized()),
                $show($layout->get_fullscreen()),
                $monitor === null ? 'any monitor' : htmlspecialchars((string) $monitor->get_connector()),
                $layout->get_resizable() ? 'true' : 'false',
                $layout->equal(new GdkToplevelLayout()) ? 'true' : 'false',
            ));
        };
        $refresh();

        $buttons = new GtkBox(GtkOrientation::Horizontal, 6);
        $toggle = function (string $text, callable $edit) use ($buttons, $refresh): void {
            $button = new GtkButton();
            $button->set_label($text);
            $button->connect('clicked', function () use ($edit, $refresh): void {
                $edit();
                $refresh();
            });
            $buttons->append($button);
        };
        $toggle('maximized', fn() => $layout->set_maximized(!($layout->get_maximized() ?? false)));
        $toggle('fullscreen', fn() => $layout->set_fullscreen(!($layout->get_fullscreen() ?? false), null));
        $toggle('resizable', fn() => $layout->set_resizable(!$layout->get_resizable()));
        $toggle('present()', function () use ($demo, $layout): void {
            $surface = $demo->get_surface();
            if ($surface instanceof GdkToplevel) {
                $surface->present($layout);
                Demo::status('GdkToplevel::present($layout)');
            }
        });

        $box = new GtkBox(GtkOrientation::Vertical, 12);
        $box->append($label);
        $box->append($buttons);
        return $box;
    },
);
