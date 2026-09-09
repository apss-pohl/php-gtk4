<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkFileFilter;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkFileFilter - what a GtkFileDialog offers to show. It is a GtkFilter like the ones
 * in the list examples, so it also answers match() and get_strictness() directly; the
 * attributes it needs (standard::display-name, standard::content-type) come from the
 * suffixes, patterns and mime types it was given.
 *
 *   bin/php-gtk4 examples/demo.php GtkFileFilter
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkFileFilter',
    'what a file dialog offers to show: suffixes, patterns, mime types',
    function (GtkWindow $win): GtkWidget {
        $out = Demo::label();

        $describe = function (GtkFileFilter $filter) use ($out): void {
            $out->set_markup(sprintf(
                "<tt>name        %s\nstrictness  %s\nattributes  %s\nas GVariant %s</tt>",
                htmlspecialchars((string) $filter->get_name()),
                $filter->get_strictness()->name,
                htmlspecialchars(implode(', ', $filter->get_attributes()) ?: '-'),
                htmlspecialchars(substr(var_export($filter->to_gvariant(), true), 0, 90)),
            ));
        };

        $filter = new GtkFileFilter();
        $filter->set_name('Images');
        $describe($filter);

        $buttons = new GtkBox(GtkOrientation::Horizontal, 6);
        $steps = [
            'add_suffix(png)' => static fn(GtkFileFilter $f) => $f->add_suffix('png'),
            'add_pattern(*.md)' => static fn(GtkFileFilter $f) => $f->add_pattern('*.md'),
            'add_mime_type' => static fn(GtkFileFilter $f) => $f->add_mime_type('image/jpeg'),
            'add_pixbuf_formats' => static fn(GtkFileFilter $f) => $f->add_pixbuf_formats(),
        ];
        foreach ($steps as $label => $step) {
            $button = GtkButton::new_with_label($label);
            $button->add_css_class('flat');
            $button->connect('clicked', function () use ($filter, $step, $describe, $label): void {
                $step($filter);
                $describe($filter);
                Demo::status($label);
            });
            $buttons->append($button);
        }

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($out);
        $page->append($buttons);
        return $page;
    },
);
