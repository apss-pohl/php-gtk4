<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkFontDialog;
use Gtk4\GtkFontDialogButton;
use Gtk4\GtkFontLevel;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\PangoFontDescription;

/*
 * Gtk4\GtkFontDialogButton - GTK 4.10's replacement for the deprecated GtkFontButton. It owns a
 * GtkFontDialog, opens it on click and keeps the answer in its `font-desc` property, a
 * PangoFontDescription. set_level() decides how much of a font the dialog lets one choose;
 * use_font()/use_size() make the button label preview the choice.
 *
 * The description below names "Sans", one of the alias families Pango always lists: GTK before
 * 4.18 dereferences NULL when the family is not in the font map (gtkfontdialogbutton.c,
 * update_font_data(), fixed upstream in 4.18) and answers with a GLib CRITICAL.
 *
 *   bin/php-gtk4 examples/demo.php GtkFontDialogButton
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkFontDialogButton',
    'a button that owns a GtkFontDialog and keeps the chosen font',
    function (GtkWindow $win): GtkWidget {
        $dialog = new GtkFontDialog();
        $dialog->set_title('Pick a font');

        $button = new GtkFontDialogButton($dialog);
        $button->set_font_desc(PangoFontDescription::from_string('Sans 14'));
        $button->set_level(GtkFontLevel::Font);
        $button->set_use_font(true);
        $button->set_use_size(false);
        $button->set_halign(GtkAlign::Center);

        $sample = new GtkLabel('');
        $facts = Demo::label();

        $show = function () use ($button, $sample, $facts): void {
            $font = $button->get_font_desc();
            $spelling = $font?->to_string() ?? 'none';
            $sample->set_markup(sprintf(
                '<span font="%s">Grumpy wizards make toxic brew</span>',
                htmlspecialchars($spelling),
            ));
            $facts->set_markup(sprintf(
                "<tt>get_font_desc()  %s\nget_level()      %s\nget_use_font()   %s</tt>",
                htmlspecialchars($spelling),
                $button->get_level()->name,
                $button->get_use_font() ? 'true' : 'false',
            ));
            Demo::status($spelling);
        };
        $show();
        $button->connect('notify::font-desc', $show);

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($button);
        $page->append($sample);
        $page->append($facts);
        return $page;
    },
);
