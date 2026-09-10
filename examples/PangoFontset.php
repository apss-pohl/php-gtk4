<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkBox;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\PangoFontDescription;
use Gtk4\PangoLanguage;

/*
 * Gtk4\PangoFontset - the fonts a description resolves to, one per script. No single font has
 * every character, so what a context loads for "Sans 14" is a *set*, and get_font($wc) answers
 * which member draws a given character. Each line below is one character, drawn, and the family
 * the set picked for it - Latin, Greek and Cyrillic usually share a font, CJK and emoji rarely do.
 *
 *   bin/php-gtk4 examples/demo.php PangoFontset
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'PangoFontset',
    'the fonts one description resolves to, character by character',
    function (GtkWindow $win): GtkWidget {
        $context = $win->create_pango_context();
        $set = $context->load_fontset(PangoFontDescription::from_string('Sans 14'), PangoLanguage::get_default());
        if ($set === null) {
            return Demo::label('<b>nothing loads for Sans 14</b>');
        }

        $page = new GtkBox(GtkOrientation::Vertical, 6);
        $families = [];
        foreach (['a', 'ß', 'α', 'я', 'क', '漢', '😀'] as $char) {
            $font = $set->get_font(mb_ord($char));
            $family = $font->describe()->get_family() ?? '?';
            $families[$family] = true;
            // Set in the font the set answered with, so what is drawn is what is named.
            $line = new GtkLabel('');
            $line->set_markup(sprintf(
                '<span font_desc="%s">%s</span>  <small>U+%04X  →  %s</small>',
                htmlspecialchars($font->describe()->to_string()),
                htmlspecialchars($char),
                mb_ord($char),
                htmlspecialchars($family),
            ));
            $line->set_xalign(0.0);
            $page->append($line);
        }

        Demo::status(sprintf(
            '%d fonts for 7 characters · get_metrics().height %d units',
            count($families),
            $set->get_metrics()->get_height(),
        ));
        return $page;
    },
);
