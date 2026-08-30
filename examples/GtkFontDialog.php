<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GAsyncResult;
use Gtk4\GError;
use Gtk4\GObject;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkFontDialog;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\PangoFontDescription;

/*
 * Gtk4\GtkFontDialog - the async font chooser. choose_font_finish() answers with a
 * PangoFontDescription, which is what a widget wants; the face/family/map side of the API
 * is not bound (gen/report.md says so per method).
 *
 *   bin/php-gtk4 examples/demo.php GtkFontDialog
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkFontDialog',
    'the async font chooser: a PangoFontDescription back',
    function (GtkWindow $win): GtkWidget {
        $description = PangoFontDescription::from_string('Cantarell Italic 18');

        $sample = new GtkLabel('The quick brown fox');
        $sample->set_markup(sprintf(
            '<span font="%s">The quick brown fox</span>',
            htmlspecialchars($description->to_string()),
        ));
        $facts = Demo::label();

        $describe = function () use (&$description, $facts, $sample): void {
            $facts->set_markup(sprintf(
                "<tt>to_string  %s\nfamily     %s\nsize       %d (%s)\nstyle      %s\nweight     %s</tt>",
                htmlspecialchars($description->to_string()),
                htmlspecialchars((string) $description->get_family()),
                $description->get_size(),
                $description->get_size_is_absolute() ? 'absolute' : 'points * PANGO_SCALE',
                $description->get_style()->name,
                $description->get_weight()->name,
            ));
            $sample->set_markup(sprintf(
                '<span font="%s">The quick brown fox</span>',
                htmlspecialchars($description->to_string()),
            ));
        };
        $describe();

        $dialog = new GtkFontDialog();
        $dialog->set_title('Pick a font');

        $pick = GtkButton::new_with_label('choose_font()');
        $pick->add_css_class('suggested-action');
        $pick->connect('clicked', function () use ($dialog, $win, $describe, &$description): void {
            $dialog->choose_font($win, $description, null, function (
                ?GObject $source,
                GAsyncResult $result,
            ) use (
                $dialog,
                $describe,
                &$description
            ): void {
                try {
                    $picked = $dialog->choose_font_finish($result);
                    if ($picked === null) {
                        return;   // NULL only ever arrives with the GError caught below
                    }
                    $description = $picked;
                    $describe();
                    Demo::status($description->to_string());
                } catch (GError $e) {
                    Demo::status('dismissed: ' . $e->getMessage());
                }
            });
        });

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($sample);
        $page->append($facts);
        $page->append($pick);
        return $page;
    },
);
