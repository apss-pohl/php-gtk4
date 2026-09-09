<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkScrollUnit;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkEventControllerScroll;
use Gtk4\GtkEventControllerScrollFlags;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkScrollUnit - what a scroll delta of 1.0 means.
 *
 * Wheel: one notch of a mouse wheel, the app decides how far that is. Surface:
 * the delta is already in surface pixels (touchpads, high-resolution wheels),
 * scroll the content by exactly that much. A native PHP enum with two cases;
 * GtkEventControllerScroll::get_unit() reports it for the scroll being handled,
 * so scroll over the pad with a wheel and with a touchpad to see it change.
 *
 *   bin/php-gtk4 examples/demo.php GdkScrollUnit
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkScrollUnit',
    'Wheel notches or Surface pixels - the unit of a scroll delta',
    function (GtkWindow $win): GtkWidget {
        $pad = new GtkLabel('scroll over me with a wheel, then with a touchpad');
        $pad->add_css_class('card');
        $pad->set_size_request(420, 120);

        $controller = new GtkEventControllerScroll(GtkEventControllerScrollFlags::BOTH_AXES);
        $pad->add_controller($controller);

        $readout = Demo::label();
        $readout->set_halign(GtkAlign::Start);
        $counts = ['Wheel' => 0, 'Surface' => 0];
        $show = function (?GdkScrollUnit $current) use ($readout, &$counts): void {
            $rows = array_map(
                static fn(GdkScrollUnit $u): string => sprintf(
                    '%s %-8s = %d   scrolls %d',
                    $u === $current ? '>' : ' ',
                    $u->name,
                    $u->value,
                    $counts[$u->name],
                ),
                GdkScrollUnit::cases(),
            );
            $readout->set_markup(sprintf(
                "<tt>%s\n\nget_unit() = <b>%s</b>\nGdkScrollUnit::from(1) = %s</tt>",
                implode("\n", $rows),
                $current === null ? 'nothing scrolled yet' : $current->name,
                GdkScrollUnit::from(1)->name,
            ));
        };

        $onScroll = function (GtkEventControllerScroll $c, float $dx, float $dy) use ($pad, $show, &$counts): bool {
            $unit = $c->get_unit();
            $counts[$unit->name]++;
            $pad->set_text(sprintf(
                'dy %+.2f %s',
                $dy,
                $unit === GdkScrollUnit::Wheel ? 'wheel notches' : 'surface pixels',
            ));
            Demo::status($unit->name);
            $show($unit);
            return true;
        };
        $controller->connect('scroll', $onScroll);

        $show(null);
        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($readout);
        $page->append($pad);
        return $page;
    },
    600,
    300,
);
