<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\CairoContext;
use Gtk4\GdkRGBA;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkColorDialog;
use Gtk4\GtkColorDialogButton;
use Gtk4\GtkDrawingArea;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkColorDialogButton - GTK 4.10's replacement for the deprecated GtkColorButton. The
 * button owns a GtkColorDialog and runs the whole async choose_rgba()/finish() dance itself;
 * PHP only reads the result, which arrives as the `rgba` property changing. Click the swatch
 * button, pick a colour, and the drawing below repaints in it.
 *
 *   bin/php-gtk4 examples/demo.php GtkColorDialogButton
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkColorDialogButton',
    'a button that owns a GtkColorDialog and keeps the chosen colour',
    function (GtkWindow $win): GtkWidget {
        $dialog = new GtkColorDialog();
        $dialog->set_title('Pick a colour');
        $dialog->set_with_alpha(true);

        $button = new GtkColorDialogButton($dialog);
        $button->set_rgba(new GdkRGBA('#3584e4'));
        $button->set_halign(GtkAlign::Center);

        $swatch = Demo::canvas(
            220,
            90,
            function (GtkDrawingArea $area, CairoContext $cr, int $w, int $h) use ($button): void {
                $cr->set_source_color($button->get_rgba());
                $cr->rectangle(0, 0, $w, $h);
                $cr->fill();
            },
        );
        $facts = Demo::label();

        // No `color-set` signal as GtkColorButton had: the answer is the property.
        $show = function () use ($button, $swatch, $facts): void {
            $rgba = $button->get_rgba();
            $facts->set_markup(sprintf(
                "<tt>get_rgba()  %s\n\$button->rgba  %s\nget_dialog()   %s</tt>",
                htmlspecialchars($rgba->to_string()),
                htmlspecialchars($button->rgba?->to_string() ?? 'none'),
                $button->get_dialog()?->get_title() ?? 'none',
            ));
            $swatch->queue_draw();
            Demo::status($rgba->to_string());
        };
        $show();
        $button->connect('notify::rgba', $show);

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($button);
        $page->append($swatch);
        $page->append($facts);
        return $page;
    },
);
