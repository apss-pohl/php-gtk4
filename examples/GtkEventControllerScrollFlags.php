<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkEventControllerScroll;
use Gtk4\GtkEventControllerScrollFlags;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkEventControllerScrollFlags - what a scroll controller listens to.
 *
 * A GFlags type, bound as a class of int constants you OR together: VERTICAL
 * and HORIZONTAL (BOTH_AXES is their sum), DISCRETE to get whole wheel clicks
 * instead of smooth deltas, KINETIC to receive `decelerate` after a touchpad
 * fling. The page walks through the combinations on a live controller with
 * set_flags() / get_flags() - scroll over the pad and note which axis still
 * reports under each combination.
 *
 *   bin/php-gtk4 examples/demo.php GtkEventControllerScrollFlags
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkEventControllerScrollFlags',
    'VERTICAL | HORIZONTAL | DISCRETE | KINETIC - OR-able int constants',
    function (GtkWindow $win): GtkWidget {
        $pad = new GtkLabel('scroll over me');
        $pad->add_css_class('card');
        $pad->set_size_request(420, 110);

        $controller = new GtkEventControllerScroll(GtkEventControllerScrollFlags::NONE);
        $pad->add_controller($controller);
        $controller->connect('scroll', function (GtkEventControllerScroll $c, float $dx, float $dy) use ($pad): bool {
            $pad->set_text(sprintf('dx %+.2f  dy %+.2f  (flags %d)', $dx, $dy, $c->get_flags()));
            return true;
        });

        /** @var array<string, int> $constants */
        $constants = array_filter(new \ReflectionClass(GtkEventControllerScrollFlags::class)->getConstants(), 'is_int');
        $combos = [
            'VERTICAL' => GtkEventControllerScrollFlags::VERTICAL,
            'HORIZONTAL' => GtkEventControllerScrollFlags::HORIZONTAL,
            'BOTH_AXES' => GtkEventControllerScrollFlags::BOTH_AXES,
            'BOTH_AXES | DISCRETE' => GtkEventControllerScrollFlags::BOTH_AXES
                | GtkEventControllerScrollFlags::DISCRETE,
            'VERTICAL | KINETIC' => GtkEventControllerScrollFlags::VERTICAL | GtkEventControllerScrollFlags::KINETIC,
            'NONE' => GtkEventControllerScrollFlags::NONE,
        ];

        $readout = Demo::label();
        $readout->set_halign(GtkAlign::Start);
        $step = 0;
        $show = function () use ($readout, $controller, $constants, $combos, &$step): void {
            $name = array_keys($combos)[$step % count($combos)];
            $controller->set_flags($combos[$name]);
            $flags = $controller->get_flags();
            $rows = array_map(
                static fn(string $k, int $v): string => sprintf(
                    '%s %-10s = %2d  (0b%04b)',
                    ($v !== 0 && ($flags & $v) === $v) || ($v === 0 && $flags === 0) ? '>' : ' ',
                    $k,
                    $v,
                    $v,
                ),
                array_keys($constants),
                $constants,
            );
            $readout->set_markup(sprintf(
                "<tt>%s\n\nset_flags(<b>%s</b>)\nget_flags() = %d  unit %s</tt>",
                implode("\n", $rows),
                $name,
                $flags,
                $controller->get_unit()->name,
            ));
            Demo::status($name);
        };

        $show();
        GLib::timeout_add(2000, function () use ($show, &$step): bool {
            $step++;
            $show();
            return true;
        });

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($readout);
        $page->append($pad);
        return $page;
    },
    600,
    380,
);
