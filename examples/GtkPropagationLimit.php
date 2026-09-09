<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkEventControllerMotion;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkPropagationLimit;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkPropagationLimit - whether a controller sees events from other native surfaces.
 *
 * A widget hierarchy can span more than one native surface (a popover is its
 * own). SameNative, the default, restricts a controller to events targeted at
 * widgets on the same surface as its own; None lets it handle events from
 * children on other surfaces too. A two-case native PHP enum; the page flips a
 * motion controller between the two with set_propagation_limit() and reads
 * the value back.
 *
 *   bin/php-gtk4 examples/demo.php GtkPropagationLimit
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkPropagationLimit',
    'SameNative or None - events from other native surfaces',
    function (GtkWindow $win): GtkWidget {
        $pad = new GtkLabel('move the pointer over me');
        $pad->add_css_class('card');
        $pad->set_size_request(420, 110);

        $controller = new GtkEventControllerMotion();
        $pad->add_controller($controller);
        $seen = [];
        $onMotion = function (GtkEventControllerMotion $c, float $x, float $y) use ($pad, &$seen): void {
            $limit = $c->get_propagation_limit()->name;
            $seen[$limit] = ($seen[$limit] ?? 0) + 1;
            $pad->set_text(sprintf('%.0f, %.0f under limit %s', $x, $y, $limit));
        };
        $controller->connect('motion', $onMotion);

        $readout = Demo::label();
        $readout->set_halign(GtkAlign::Start);
        $cases = GtkPropagationLimit::cases();
        $step = 0;
        $show = function () use ($readout, $controller, $cases, &$step, &$seen): void {
            $controller->set_propagation_limit($cases[$step % count($cases)]);
            $current = $controller->get_propagation_limit();
            $rows = array_map(
                static fn(GtkPropagationLimit $l): string => sprintf(
                    '%s %-10s = %d   motion events %d',
                    $l === $current ? '>' : ' ',
                    $l->name,
                    $l->value,
                    $seen[$l->name] ?? 0,
                ),
                $cases,
            );
            $readout->set_markup(sprintf(
                "<tt>%s\n\nget_propagation_limit() = <b>%s</b>\nGtkPropagationLimit::from(1) = %s</tt>",
                implode("\n", $rows),
                $current->name,
                GtkPropagationLimit::from(1)->name,
            ));
            Demo::status($current->name);
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
    300,
);
