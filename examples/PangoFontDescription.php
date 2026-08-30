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
use Gtk4\PangoStyle;
use Gtk4\PangoWeight;

/*
 * Gtk4\PangoFontDescription - a font as a value: family, size, style, weight, stretch. It is
 * a boxed handle, so it copies on clone and compares by value (equal()), and from_string() /
 * to_string() speak the "Cantarell Bold Italic 12" spelling Pango markup uses.
 *
 *   bin/php-gtk4 examples/demo.php PangoFontDescription
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'PangoFontDescription',
    'a font as a value: from_string(), to_string(), equal()',
    function (GtkWindow $win): GtkWidget {
        $font = PangoFontDescription::from_string('Cantarell 14');
        $sample = new GtkLabel('');
        $facts = Demo::label();

        $show = function () use (&$font, $sample, $facts): void {
            $sample->set_markup(sprintf(
                '<span font="%s">Grumpy wizards make toxic brew</span>',
                htmlspecialchars($font->to_string()),
            ));
            // A boxed value: the copy is independent, and equal() compares what they say.
            $copy = clone $font;
            $facts->set_markup(sprintf(
                "<tt>to_string      %s\nfamily         %s\nsize           %d\n"
                . "clone == self  %s\nsame handle    %s</tt>",
                htmlspecialchars($font->to_string()),
                htmlspecialchars((string) $font->get_family()),
                $font->get_size(),
                $font->equal($copy) ? 'true' : 'false',
                $font === $copy ? 'true' : 'false',
            ));
        };
        $show();

        $buttons = new GtkBox(GtkOrientation::Horizontal, 6);
        $steps = [
            'bolder' => static fn(PangoFontDescription $f) => $f->set_weight(PangoWeight::Bold),
            'italic' => static fn(PangoFontDescription $f) => $f->set_style(PangoStyle::Italic),
            'bigger' => static fn(PangoFontDescription $f) => $f->set_size($f->get_size() + 4096),
            'monospace' => static fn(PangoFontDescription $f) => $f->set_family('monospace'),
        ];
        foreach ($steps as $label => $step) {
            $button = GtkButton::new_with_label($label);
            $button->add_css_class('flat');
            $button->connect('clicked', function () use (&$font, $step, $show, $label): void {
                $step($font);
                $show();
                Demo::status($label);
            });
            $buttons->append($button);
        }

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($sample);
        $page->append($facts);
        $page->append($buttons);
        return $page;
    },
);
