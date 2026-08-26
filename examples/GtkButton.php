<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkButton;
use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkButton - a widget that emits `clicked` when activated.
 *
 * The button's child is a GtkLabel that the clicked handler rewrites, so every
 * press is visible. activate() emits the same signal without a pointer.
 *
 *   bin/php-gtk4 examples/demo.php GtkButton
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkButton',
    'a widget that emits `clicked` when activated',
    function (GtkWindow $win): GtkWidget {
        $face = new GtkLabel();
        $face->set_markup("<span size=\"x-large\">Click me</span>\n<small>0 clicks so far</small>");

        $button = new GtkButton();
        $button->set_child($face);                     // a button holds one child widget
        $button->add_css_class('suggested-action');
        $button->set_tooltip_text('emits the clicked signal');

        $clicks = 0;
        $button->connect('clicked', function (GtkButton $self) use ($face, &$clicks): void {
            $clicks++;
            $face->set_markup(sprintf(
                "<span size=\"x-large\">Clicked <b>%d</b> time(s)</span>\n<small>label property: %s</small>",
                $clicks,
                htmlspecialchars((string) ($self->get_label() ?? 'null - the child is a widget, not text')),
            ));
        });

        // A plain text button for comparison, and a synthetic click.
        $button->activate();                           // GtkWidget::activate() -> clicked

        return $button;
    },
);
