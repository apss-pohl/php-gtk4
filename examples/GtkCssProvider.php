<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\Gtk;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkCssProvider;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkStyleProviderPriority;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkCssProvider - a stylesheet, loaded from PHP.
 *
 * GTK 4 styles widgets with CSS. A provider holds the stylesheet, the display
 * hands it to every widget on it, and the CSS classes of a widget
 * (GtkWidget::add_css_class) decide what it matches. Loading new CSS into a
 * provider that is already attached restyles everything on screen at once - the
 * buttons below swap the theme under a live label.
 *
 *   bin/php-gtk4 examples/demo.php GtkCssProvider
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkCssProvider',
    'a stylesheet: CSS written in PHP, applied to every widget on a display',
    function (GtkWindow $win): GtkWidget {
        // `.css-demo` is the class the card below carries; everything else on the
        // display is untouched, which is why a page may attach a provider at all.
        $themes = [
            'blue' => '.css-demo { background: linear-gradient(to bottom, #3584e4, #1c71d8);'
                . ' color: #ffffff; border-radius: 12px; padding: 18px; font-size: 18px; font-weight: bold; }',
            'paper' => '.css-demo { background: #fdfdfd; color: #241f31; border: 2px dashed #77767b;'
                . ' padding: 18px; font-size: 18px; }',
            'ink' => '.css-demo { background: #241f31; color: #2ec27e; border-radius: 4px;'
                . ' padding: 18px; font-family: monospace; font-size: 18px; }',
        ];

        $provider = new GtkCssProvider();
        $provider->load_from_string($themes['blue']);

        // One provider, one display, one priority. The display comes from the window;
        // GdkDisplay::get_default() is the same one for a single-display session.
        Gtk::add_provider_for_display($win->get_display(), $provider, GtkStyleProviderPriority::APPLICATION);

        $card = new GtkLabel('styled by CSS');
        $card->add_css_class('css-demo');
        $card->set_hexpand(true);

        $dump = Demo::label();
        $show = function (string $name) use ($provider, $dump): void {
            // to_string() gives back what GTK parsed, not the text that was fed in.
            $dump->set_markup('<small><tt>' . htmlspecialchars(trim($provider->to_string())) . '</tt></small>');
            Demo::status("theme: $name");
        };
        $show('blue');

        $buttons = new GtkBox(GtkOrientation::Horizontal, 6);
        foreach (array_keys($themes) as $name) {
            $button = GtkButton::new_with_label($name);
            $button->add_css_class('flat');
            $button->connect('clicked', function () use ($provider, $themes, $name, $show): void {
                $provider->load_from_string($themes[$name]);   // no re-adding, no redraw call
                $show($name);
            });
            $buttons->append($button);
        }

        // load_from_path() and load_from_resource() read the same CSS from a file or a
        // GResource, load_from_bytes() from a string of bytes, and load_named('Adwaita',
        // 'dark') loads an installed theme instead.
        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($card);
        $page->append($buttons);
        $page->append($dump);
        return $page;
    },
);
