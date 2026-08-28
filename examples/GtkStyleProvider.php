<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\Gtk;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkCssProvider;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkStyleProvider;
use Gtk4\GtkStyleProviderPriority;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkStyleProvider - what a display accepts as a source of style.
 *
 * The interface itself has no methods: it is the type Gtk::add_provider_for_display()
 * and remove_provider_for_display() take, and GtkCssProvider is the implementation
 * GTK ships. Attaching and detaching one is a live operation - the button below
 * toggles the provider and the card follows it immediately.
 *
 *   bin/php-gtk4 examples/demo.php GtkStyleProvider
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkStyleProvider',
    'the interface a display takes style from - GtkCssProvider implements it',
    function (GtkWindow $win): GtkWidget {
        $provider = new GtkCssProvider();
        $provider->load_from_string(
            '.provider-demo { background: #2ec27e; color: #ffffff; border-radius: 8px;'
            . ' padding: 16px; font-size: 18px; font-weight: bold; }',
        );

        $card = new GtkLabel('styled while the provider is attached');
        $card->add_css_class('provider-demo');

        // The interface is what the two Gtk:: calls below accept; GtkCssProvider is the
        // implementation GTK ships, so it is what every page here passes them.
        $facts = Demo::label(sprintf(
            "<tt>%s\n  implements %s\n\nGtk::add_provider_for_display(display, %s, priority)\n"
            . 'Gtk::remove_provider_for_display(display, %s)</tt>',
            GtkCssProvider::class,
            implode(', ', class_implements($provider) ?: ['-']),
            GtkStyleProvider::class,
            GtkStyleProvider::class,
        ));

        $display = $win->get_display();
        $attached = false;
        $toggle = GtkButton::new_with_label('attach');
        $toggle->add_css_class('suggested-action');
        $toggle->connect('clicked', function (GtkButton $self) use ($display, $provider, &$attached): void {
            if ($attached) {
                Gtk::remove_provider_for_display($display, $provider);
                $self->set_label('attach');
            } else {
                Gtk::add_provider_for_display($display, $provider, GtkStyleProviderPriority::APPLICATION);
                $self->set_label('detach');
            }
            $attached = !$attached;
            Demo::status($attached ? 'provider attached' : 'provider detached');
        });

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($card);
        $page->append($toggle);
        $page->append($facts);
        return $page;
    },
);
