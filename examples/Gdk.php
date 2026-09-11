<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\Gdk;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkEventControllerKey;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\Gdk - the GDK namespace constants: Gdk::KEY_Return, Gdk::BUTTON_PRIMARY,
 * Gdk::EVENT_STOP, ... - the C GDK_* names without their prefix.
 *
 * A key-pressed handler compares its keyval against these; nothing else in the binding
 * carries them (a program used to write 0xff0d itself). The page listens for keys and names
 * the one you press by looking the keyval up among the KEY_* constants, and answers
 * Gdk::EVENT_STOP for Escape so the key goes no further.
 *
 *   bin/php-gtk4 examples/demo.php Gdk
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'Gdk',
    'the GDK constants: KEY_* keysyms, BUTTON_*, EVENT_STOP / EVENT_PROPAGATE',
    function (GtkWindow $win): GtkWidget {
        // keyval -> constant name, once; several names share a value (KEY_Escape only once,
        // but KEY_KP_Enter next to KEY_Return-like duplicates), the first one wins.
        $names = [];
        foreach (new \ReflectionClass(Gdk::class)->getConstants() as $name => $value) {
            if (str_starts_with($name, 'KEY_') && is_int($value)) {
                $names[$value] ??= $name;
            }
        }

        $pad = new GtkLabel('click here, then press keys');
        $pad->add_css_class('card');
        $pad->set_size_request(420, 120);
        $pad->set_focusable(true);
        $pad->set_can_focus(true);

        $readout = Demo::label(sprintf(
            "<tt>Gdk::KEY_Return  = 0x%04x\nGdk::KEY_Escape  = 0x%04x\nGdk::BUTTON_PRIMARY = %d\n"
            . "Gdk::EVENT_STOP  = %s\n\n%d KEY_* constants\n\nlast key: -</tt>",
            Gdk::KEY_Return,
            Gdk::KEY_Escape,
            Gdk::BUTTON_PRIMARY,
            var_export(Gdk::EVENT_STOP, true),
            count($names),
        ));
        $readout->set_halign(GtkAlign::Start);

        $controller = new GtkEventControllerKey();
        $pad->add_controller($controller);
        $controller->connect(
            'key-pressed',
            function (GtkEventControllerKey $c, int $keyval, int $keycode, int $state) use ($readout, $names): bool {
                $name = $names[$keyval] ?? sprintf('(no KEY_ constant for 0x%x)', $keyval);
                $readout->set_markup(sprintf(
                    "<tt>Gdk::KEY_Return  = 0x%04x\nGdk::KEY_Escape  = 0x%04x\nGdk::BUTTON_PRIMARY = %d\n"
                    . "Gdk::EVENT_STOP  = %s\n\n%d KEY_* constants\n\nlast key: <b>Gdk::%s</b> = 0x%04x%s</tt>",
                    Gdk::KEY_Return,
                    Gdk::KEY_Escape,
                    Gdk::BUTTON_PRIMARY,
                    var_export(Gdk::EVENT_STOP, true),
                    count($names),
                    htmlspecialchars($name),
                    $keyval,
                    $keyval === Gdk::KEY_Escape ? '  (claimed: EVENT_STOP)' : '',
                ));
                Demo::status("keyval $keyval is Gdk::$name");
                return $keyval === Gdk::KEY_Escape ? Gdk::EVENT_STOP : Gdk::EVENT_PROPAGATE;
            },
        );

        $box = new GtkBox(GtkOrientation::Vertical, 12);
        $box->append($pad);
        $box->append($readout);
        return $box;
    },
);
