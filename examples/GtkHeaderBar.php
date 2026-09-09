<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkHeaderBar;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkHeaderBar - the title bar with widgets in it.
 *
 * Normally handed to GtkWindow::set_titlebar(); here it sits inside the page,
 * because the page cannot take over the demo window. pack_start()/pack_end() add
 * widgets at either edge, set_title_widget() replaces the centred title,
 * set_show_title_buttons() and set_decoration_layout() control the window
 * buttons (close, minimize, maximize - the layout string is "left:right").
 * Clicking the button cycles through them.
 *
 *   bin/php-gtk4 examples/demo.php GtkHeaderBar
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkHeaderBar',
    'the title bar with widgets in it',
    function (GtkWindow $win): GtkWidget {
        $bar = new GtkHeaderBar();
        $bar->add_css_class('card');
        $title = new GtkLabel('a GtkHeaderBar');
        $title->add_css_class('title');
        $bar->set_title_widget($title);
        $back = GtkButton::new_from_icon_name('go-previous-symbolic');
        $bar->pack_start($back);
        $menu = GtkButton::new_from_icon_name('open-menu-symbolic');
        $bar->pack_end($menu);

        $readout = Demo::label();
        $note = 'pack_start(back), pack_end(menu), set_title_widget(label)';
        $render = function () use ($readout, $bar, &$note): void {
            $tw = $bar->get_title_widget();
            $readout->set_markup(sprintf(
                "<tt>title_widget       %s\nshow_title_buttons %s\ndecoration_layout  %s</tt>\n\nlast: <i>%s</i>",
                $tw instanceof GtkLabel ? '"' . htmlspecialchars($tw->get_text()) . '"' : var_export($tw, true),
                $bar->get_show_title_buttons() ? 'true' : 'false',
                var_export($bar->get_decoration_layout(), true),
                htmlspecialchars($note),
            ));
        };

        $extra = GtkButton::new_with_label('extra');
        $step = 0;
        $next = GtkButton::new_with_label('next change');
        $next->connect('clicked', function () use ($bar, $extra, $title, &$step, &$note, $render): void {
            [$note, $apply] = match ($step++ % 6) {
                0 => [
                    "set_decoration_layout('close:minimize,maximize')",
                    static fn() => $bar->set_decoration_layout('close:minimize,maximize'),
                ],
                1 => ['set_show_title_buttons(false)', static fn() => $bar->set_show_title_buttons(false)],
                2 => ['pack_start(extra)', static fn() => $bar->pack_start($extra)],
                3 => ['set_title_widget(null)', static fn() => $bar->set_title_widget(null)],
                4 => ['remove(extra)', static fn() => $bar->remove($extra)],
                default => ['back to the start', static function () use ($bar, $title): void {
                    $bar->set_decoration_layout(null);
                    $bar->set_show_title_buttons(true);
                    $bar->set_title_widget($title);
                }],
            };
            $apply();
            $render();
        });

        $render();
        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($bar);
        $next->set_halign(GtkAlign::Center);
        $page->append($next);
        $page->append($readout);
        return $page;
    },
    560,
    340,
);
