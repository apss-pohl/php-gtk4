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
 * Gtk4\GtkStyleProviderPriority - who wins when two stylesheets disagree.
 *
 * Several providers can be attached to the same display; for every property the
 * one added with the highest priority decides. The two providers below set the
 * same background on the same class, one at FALLBACK and one at USER, and the
 * button detaches the loud one so the quiet one shows through.
 *
 *   bin/php-gtk4 examples/demo.php GtkStyleProviderPriority
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkStyleProviderPriority',
    'the priorities a provider can be attached with, lowest to highest',
    function (GtkWindow $win): GtkWidget {
        $display = $win->get_display();

        $quiet = new GtkCssProvider();
        $quiet->load_from_string('.priority-demo { background: #77767b; color: #ffffff;'
            . ' border-radius: 8px; padding: 16px; font-size: 18px; }');
        $loud = new GtkCssProvider();
        $loud->load_from_string('.priority-demo { background: #e01b24; }');

        // Both providers match the same widget; USER (800) beats FALLBACK (1), so the
        // card is red until the loud one is detached - then the grey one shows again.
        Gtk::add_provider_for_display($display, $quiet, GtkStyleProviderPriority::FALLBACK);
        Gtk::add_provider_for_display($display, $loud, GtkStyleProviderPriority::USER);

        $card = new GtkLabel('two providers, one class');
        $card->add_css_class('priority-demo');

        $constants = array_filter(new \ReflectionClass(GtkStyleProviderPriority::class)->getConstants(), 'is_int');
        asort($constants);
        $table = Demo::label('<tt>' . implode("\n", array_map(
            static fn(string $name, int $value): string => sprintf('%-12s %3d', $name, $value),
            array_keys($constants),
            $constants,
        )) . '</tt>');

        $on = true;
        $toggle = GtkButton::new_with_label('detach the USER provider');
        $toggle->add_css_class('flat');
        $toggle->connect('clicked', function (GtkButton $self) use ($display, $loud, &$on): void {
            if ($on) {
                Gtk::remove_provider_for_display($display, $loud);
                $self->set_label('attach it again (USER)');
            } else {
                Gtk::add_provider_for_display($display, $loud, GtkStyleProviderPriority::USER);
                $self->set_label('detach the USER provider');
            }
            $on = !$on;
            Demo::status($on ? 'USER wins' : 'only FALLBACK is attached');
        });
        Demo::status('USER wins');

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($card);
        $page->append($toggle);
        $page->append($table);
        return $page;
    },
);
