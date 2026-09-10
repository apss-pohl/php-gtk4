<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\PangoFontDescription;

/*
 * Gtk4\PangoFont - a font the backend loaded for a description. A description is a wish
 * ("Serif Bold 16"); the font is what the font map actually found for it, and describe() says
 * how close it came. PHP never builds one: PangoContext::load_font() and PangoFontMap::load_font()
 * hand them out. Click through the descriptions to see what each one resolves to.
 *
 *   bin/php-gtk4 examples/demo.php PangoFont
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'PangoFont',
    'what the font map loads for a description, and how close it came',
    function (GtkWindow $win): GtkWidget {
        $context = $win->create_pango_context();
        $wishes = ['Sans 12', 'Serif Bold 16', 'Monospace Italic 11', 'Nonexistent Family 14'];
        $at = 0;

        $sample = new GtkLabel('');
        $facts = Demo::label();

        $show = function () use (&$at, $wishes, $context, $sample, $facts): void {
            $wish = $wishes[$at % count($wishes)];
            $font = $context->load_font(PangoFontDescription::from_string($wish));
            if ($font === null) {
                $facts->set_markup("<b>$wish</b>: nothing loads for it");
                return;
            }
            $got = $font->describe();
            $face = $font->get_face();
            $sample->set_markup(sprintf(
                '<span font_desc="%s">Hamburgefonstiv 0123</span>',
                htmlspecialchars($got->to_string()),
            ));
            $facts->set_markup(sprintf(
                "<tt>wish                 %s\ndescribe()           %s\n"
                . "get_face()           %s of %s\nhas_char('ß')        %s\n"
                . "has_char('漢')       %s\nget_metrics().height %d units</tt>",
                htmlspecialchars($wish),
                htmlspecialchars($got->to_string()),
                htmlspecialchars($face->get_face_name()),
                htmlspecialchars($face->get_family()->get_name()),
                $font->has_char(mb_ord('ß')) ? 'true' : 'false',
                $font->has_char(mb_ord('漢')) ? 'true' : 'false',
                $font->get_metrics(null)->get_height(),
            ));
            Demo::status($got->to_string());
        };
        $show();

        $next = GtkButton::new_with_label('next description');
        $next->connect('clicked', function () use (&$at, $show): void {
            $at++;
            $show();
        });

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($sample);
        $page->append($facts);
        $page->append($next);
        return $page;
    },
);
