<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkDisplay;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkDisplay - the connection to the windowing system.
 *
 * One display per X11 server or Wayland compositor: it owns the monitors, the
 * clipboard and the style providers, which is why Gtk::add_provider_for_display()
 * needs one. Handles are identities, so the display of any widget on screen is
 * the same PHP object as GdkDisplay::get_default().
 *
 *   bin/php-gtk4 examples/demo.php GdkDisplay
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkDisplay',
    'the connection to the windowing system: monitors, clipboard, style providers',
    function (GtkWindow $win): GtkWidget {
        $display = $win->get_display();
        $facts = Demo::label();

        $describe = function () use ($display, $facts): void {
            $facts->set_markup(sprintf(
                "<tt>get_name()             %s\nis_composited()        %s\nis_rgba()              %s\n"
                . "is_closed()            %s\nsupports_shadow_width  %s\nget_monitors()         %d monitor(s)\n"
                . 'get_default() === it   %s</tt>',
                htmlspecialchars($display->get_name()),
                $display->is_composited() ? 'true' : 'false',
                $display->is_rgba() ? 'true' : 'false',
                $display->is_closed() ? 'true' : 'false',
                $display->supports_shadow_width() ? 'true' : 'false',
                $display->get_monitors()->get_n_items(),
                $display === GdkDisplay::get_default() ? 'true' : 'false',
            ));
        };
        $describe();

        // beep() and flush() are the two harmless ones to press; close() would take the
        // application down with it, and open() connects to a second server entirely.
        $buttons = new GtkBox(GtkOrientation::Horizontal, 6);
        $actions = [
            'beep()' => static fn() => $display->beep(),
            'flush()' => static fn() => $display->flush(),
            'sync()' => static fn() => $display->sync(),
        ];
        foreach ($actions as $label => $call) {
            $button = GtkButton::new_with_label($label);
            $button->add_css_class('flat');
            $button->connect('clicked', function () use ($call, $label, $describe): void {
                $call();
                Demo::status("GdkDisplay::$label called");
                $describe();
            });
            $buttons->append($button);
        }

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($facts);
        $page->append($buttons);
        return $page;
    },
);
