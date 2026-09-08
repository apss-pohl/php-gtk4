<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkButton;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\PangoEllipsizeMode;
use Gtk4\PangoLayout;

/*
 * Gtk4\PangoLayout - the paragraph a widget measures before it draws it.
 *
 * Every label and text view has one underneath. It takes text (or markup), a width to wrap at
 * and a policy for what to do when it still does not fit, and it answers with a size in Pango
 * units - 1024 to the pixel - and the number of lines that came out. A widget's own context is
 * what a layout is built from, so it measures in the same font the widget will draw in.
 *
 *   bin/php-gtk4 examples/demo.php PangoLayout
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'PangoLayout',
    'the paragraph a widget measures before it draws it',
    function (GtkWindow $win): GtkWidget {
        $text = 'Pango lays this paragraph out, wrapping it where the width runs out and '
            . 'telling the widget how much room it needs.';

        /** @var list<array{string, int, PangoEllipsizeMode}> $steps */
        $steps = [
            ['no width: one long line', -1, PangoEllipsizeMode::None],
            ['width 260 px: it wraps', 260 * 1024, PangoEllipsizeMode::None],
            ['width 120 px: it wraps more', 120 * 1024, PangoEllipsizeMode::None],
            ['width 260 px, ellipsize at the end', 260 * 1024, PangoEllipsizeMode::End],
        ];

        $label = Demo::label();
        $at = 0;
        $render = function () use (&$at, $steps, $text, $label, $win): void {
            [$name, $width, $ellipsize] = $steps[$at % count($steps)];
            $layout = new PangoLayout($win->create_pango_context());
            $layout->set_text($text, -1);
            $layout->set_width($width);
            $layout->set_ellipsize($ellipsize);

            [$w, $h] = $layout->get_pixel_size();
            $label->set_markup(sprintf(
                "<span size=\"x-large\"><b>%d x %d px</b></span>\n"
                . "<small>%d line%s · %s</small>\n\n<small>%s</small>",
                $w,
                $h,
                $layout->get_line_count(),
                $layout->get_line_count() === 1 ? '' : 's',
                $layout->is_ellipsized() ? 'ellipsized' : 'all there',
                htmlspecialchars($name),
            ));
            Demo::status(sprintf(
                'width %s · %d Pango units to the pixel',
                $width < 0 ? 'unset' : (string) ($width / 1024) . ' px',
                1024,
            ));
        };

        $button = new GtkButton();
        $button->set_child($label);
        $button->connect('clicked', function () use (&$at, $render): void {
            $at++;
            $render();
        });
        $render();
        return $button;
    },
    460,
    240,
);
