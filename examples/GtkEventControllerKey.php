<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkModifierType;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkEventControllerKey;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkEventControllerKey - keyboard input, one signal per press and release.
 *
 * GTK 4 has no key-press-event on widgets: you attach a controller to a widget
 * that can take focus and listen to the controller. `key-pressed` and
 * `key-released` hand you the keyval, the hardware keycode and the modifier
 * state (GdkModifierType bits); `modifiers` fires when only a modifier changed.
 * Returning true from `key-pressed` claims the key so nothing further up the
 * hierarchy sees it. The pad below is a focusable label - click it or Tab to it,
 * then type.
 *
 *   bin/php-gtk4 examples/demo.php GtkEventControllerKey
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkEventControllerKey',
    'keyboard input - key-pressed / key-released with keyval, keycode and modifiers',
    function (GtkWindow $win): GtkWidget {
        $pad = new GtkLabel('click here, then type');
        $pad->add_css_class('card');
        $pad->set_size_request(420, 160);
        $pad->set_focusable(true);
        $pad->set_can_focus(true);

        $readout = Demo::label("<tt>key-pressed   -\nkey-released  -\nmodifiers     -\nclaimed       0</tt>");
        $readout->set_halign(GtkAlign::Start);

        $controller = new GtkEventControllerKey();
        $controller->set_name('demo-keys');
        $pad->add_controller($controller);

        $last = ['pressed' => '-', 'released' => '-', 'modifiers' => '-'];
        $claimed = 0;
        $show = function () use ($readout, &$last, &$claimed): void {
            $readout->set_markup(sprintf(
                "<tt>key-pressed   %s\nkey-released  %s\nmodifiers     %s\nclaimed       %d</tt>",
                htmlspecialchars($last['pressed']),
                htmlspecialchars($last['released']),
                htmlspecialchars($last['modifiers']),
                $claimed,
            ));
        };
        $describe = static function (int $keyval, int $keycode, int $state): string {
            // Printable ASCII keyvals are their own character; everything else stays numeric.
            $char = $keyval >= 0x20 && $keyval <= 0x7e ? chr($keyval) : '';
            $mods = array_keys(array_filter([
                'shift' => $state & GdkModifierType::SHIFT_MASK,
                'ctrl' => $state & GdkModifierType::CONTROL_MASK,
                'alt' => $state & GdkModifierType::ALT_MASK,
                'super' => $state & GdkModifierType::SUPER_MASK,
            ]));
            return sprintf(
                'keyval 0x%04x %-3s keycode %-3d state 0x%x %s',
                $keyval,
                $char,
                $keycode,
                $state,
                implode('+', $mods),
            );
        };

        $controller->connect(
            'key-pressed',
            function (
                GtkEventControllerKey $c,
                int $keyval,
                int $keycode,
                int $state,
            ) use (
                $pad,
                $describe,
                $show,
                &$last,
                &$claimed,
            ): bool {
                $last['pressed'] = $describe($keyval, $keycode, $state);
                $pad->set_text($keyval >= 0x20 && $keyval <= 0x7e ? "'" . chr($keyval) . "'" : "keyval $keyval");
                Demo::status('key-pressed on ' . get_debug_type($c->get_widget()));
                // Claim the space bar (true) so the demo's sidebar never sees it; pass the rest on.
                $claim = $keyval === 0x20;
                $claimed += $claim ? 1 : 0;
                $show();
                return $claim;
            },
        );
        $controller->connect(
            'key-released',
            function (
                GtkEventControllerKey $c,
                int $keyval,
                int $keycode,
                int $state,
            ) use (
                $describe,
                $show,
                &$last,
            ): void {
                $last['released'] = $describe($keyval, $keycode, $state);
                $show();
            },
        );
        $controller->connect('modifiers', function (GtkEventControllerKey $c, int $state) use ($show, &$last): bool {
            $last['modifiers'] = sprintf('state 0x%x  group %d', $state, $c->get_group());
            $show();
            return false;
        });

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($readout);
        $page->append($pad);
        Demo::status('the pad claims the space bar, passes everything else on');
        return $page;
    },
    600,
    360,
);
