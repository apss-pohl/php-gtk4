<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkMemoryFormat;
use Gtk4\GdkMemoryTexture;
use Gtk4\GdkSurface;
use Gtk4\GdkToplevel;
use Gtk4\GdkToplevelLayout;
use Gtk4\GdkToplevelState;
use Gtk4\GtkApplication;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkToplevel - the surface side of a window: state flags and what a window cannot
 * do itself.
 *
 * A GtkWindow's surface is a GdkToplevel, implemented by a backend-private class (X11,
 * Wayland) - get_surface() answers with GdkToplevelObject, a GdkSurface that is the interface
 * with a body. Its `state` says what the window manager did (minimized, maximized, focused,
 * tiled, ...) and notifies on every change; minimize(), lower() and set_icon_list() are only
 * here. The page opens a window of its own, mirrors its state flags live, and offers the calls.
 *
 *   bin/php-gtk4 examples/demo.php GdkToplevel
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkToplevel',
    'the surface side of a window: state flags, minimize(), lower(), the icon list',
    function (GtkWindow $win, GtkApplication $app): GtkWidget {
        $label = Demo::label();

        $demo = new GtkWindow();
        $demo->set_application($app);
        $demo->set_title('GdkToplevel demo');
        $demo->set_default_size(320, 200);
        $demo->set_child(Demo::label('The page on the left mirrors this window\'s toplevel state.'));

        // Every GdkToplevelState bit, named, for the flags the surface reports.
        $bits = [];
        foreach (new \ReflectionClass(GdkToplevelState::class)->getConstants() as $name => $value) {
            if (is_int($value)) {
                $bits[$name] = $value;
            }
        }

        $refresh = function (GdkSurface $surface) use ($label, $bits): void {
            if (!$surface instanceof GdkToplevel) {
                $label->set_markup('not a toplevel surface');
                return;
            }
            $state = $surface->get_state();
            $rows = [];
            foreach ($bits as $name => $bit) {
                $on = ($state & $bit) !== 0;
                $rows[] = sprintf(
                    '<span foreground="%s">%s</span> %s',
                    $on ? Demo::GOOD : Demo::MUTED,
                    $on ? '●' : '○',
                    strtolower($name),
                );
            }
            $label->set_markup(sprintf(
                "<b>%s</b> · %d x %d\nstate <tt>%d</tt>\n\n%s\n\n<i>%s</i>",
                $surface::class,
                $surface->get_width(),
                $surface->get_height(),
                $state,
                implode("\n", $rows),
                'minimize, un-minimize, focus another window: the flags follow',
            ));
        };

        // The surface exists from realize on; notify::state is where the window manager reports.
        $demo->connect('realize', function (GtkWindow $window) use ($refresh): void {
            $surface = $window->get_surface();
            if ($surface === null) {
                return;
            }
            $surface->connect('notify::state', $refresh);
            $refresh($surface);
        });
        $demo->present();

        $toplevel = static fn(): ?GdkToplevel => ($s = $demo->get_surface()) instanceof GdkToplevel ? $s : null;

        $buttons = new GtkBox(GtkOrientation::Horizontal, 6);
        $minimize = new GtkButton();
        $minimize->set_label('minimize()');
        $minimize->connect('clicked', function () use ($toplevel): void {
            $t = $toplevel();
            Demo::status($t === null ? 'no surface' : 'minimize() -> ' . ($t->minimize() ? 'true' : 'false'));
        });
        $lower = new GtkButton();
        $lower->set_label('lower()');
        $lower->connect('clicked', function () use ($toplevel): void {
            $t = $toplevel();
            Demo::status($t === null ? 'no surface' : 'lower() -> ' . ($t->lower() ? 'true' : 'false'));
        });
        $maximize = new GtkButton();
        $maximize->set_label('present(maximized layout)');
        $maximize->connect('clicked', function () use ($toplevel): void {
            $layout = new GdkToplevelLayout();
            $layout->set_maximized(true);
            $toplevel()?->present($layout);
            Demo::status('present() with a maximized GdkToplevelLayout');
        });
        $icon = new GtkButton();
        $icon->set_label('set_icon_list()');
        $icon->connect('clicked', function () use ($toplevel): void {
            // a 32x32 accent-coloured square as raw RGBA pixels, one texture for the list
            $pixel = pack('C4', 0x35, 0x84, 0xe4, 0xff);
            $rgba = str_repeat($pixel, 32 * 32);
            $texture = new GdkMemoryTexture(32, 32, GdkMemoryFormat::R8g8b8a8, $rgba, 32 * 4);
            $toplevel()?->set_icon_list([$texture]);
            Demo::status('set_icon_list([one GdkTexture]) - shown by taskbars and window lists');
        });
        foreach ([$minimize, $lower, $maximize, $icon] as $b) {
            $buttons->append($b);
        }

        $box = new GtkBox(GtkOrientation::Vertical, 12);
        $box->append($label);
        $box->append($buttons);
        return $box;
    },
);
