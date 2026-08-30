<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GAsyncResult;
use Gtk4\GdkRGBA;
use Gtk4\GError;
use Gtk4\GObject;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkColorDialog;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkColorDialog - the async colour chooser. choose_rgba() returns at once and
 * choose_rgba_finish() answers with a GdkRGBA (a boxed value, copied into PHP), or throws
 * when the dialog was dismissed. The swatch below is drawn with the colour that comes back.
 *
 *   bin/php-gtk4 examples/demo.php GtkColorDialog
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkColorDialog',
    'the async colour chooser: choose_rgba() and a GdkRGBA back',
    function (GtkWindow $win): GtkWidget {
        $colour = new GdkRGBA('#3584e4');
        $swatch = Demo::canvas(220, 90, function ($area, $cr, int $w, int $h) use (&$colour): void {
            $cr->set_source_color($colour);
            $cr->rectangle(0, 0, $w, $h);
            $cr->fill();
        });
        $label = Demo::label('<tt>' . $colour->to_string() . '</tt>');

        $dialog = new GtkColorDialog();
        $dialog->set_title('Pick a colour');
        $dialog->set_with_alpha(true);

        $pick = GtkButton::new_with_label('choose_rgba()');
        $pick->add_css_class('suggested-action');
        $pick->connect('clicked', function () use ($dialog, $win, $swatch, $label, &$colour): void {
            $dialog->choose_rgba($win, $colour, null, function (
                ?GObject $source,
                GAsyncResult $result,
            ) use (
                $dialog,
                $swatch,
                $label,
                &$colour,
            ): void {
                try {
                    $picked = $dialog->choose_rgba_finish($result);
                    if ($picked === null) {
                        return;   // GTK answers NULL only together with the GError below
                    }
                    $colour = $picked;
                    $label->set_markup('<tt>' . $colour->to_string() . '</tt>');
                    $swatch->queue_draw();
                    Demo::status($colour->to_string());
                } catch (GError $e) {
                    $label->set_markup('<i>' . htmlspecialchars($e->getMessage()) . '</i>');
                    Demo::status('dismissed');
                }
            });
        });

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($swatch);
        $page->append($label);
        $page->append($pick);
        return $page;
    },
);
