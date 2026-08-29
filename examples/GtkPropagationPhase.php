<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkEventControllerKey;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkPropagationPhase;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkPropagationPhase - at which stage of event delivery a controller runs.
 *
 * An event travels from the toplevel down to the target widget (Capture),
 * arrives (Target) and travels back up (Bubble); None switches the controller
 * off without removing it. A native PHP enum; the page cycles the cases on a
 * real key controller with set_propagation_phase() and reads them back, so you
 * can type into the pad and see which phases still deliver keys.
 *
 *   bin/php-gtk4 examples/demo.php GtkPropagationPhase
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkPropagationPhase',
    'Capture / Target / Bubble / None - when a controller sees an event',
    function (GtkWindow $win): GtkWidget {
        $pad = new GtkLabel('click here and type');
        $pad->add_css_class('card');
        $pad->set_size_request(420, 110);
        $pad->set_focusable(true);
        $pad->set_can_focus(true);

        $controller = new GtkEventControllerKey();
        $pad->add_controller($controller);
        $seen = [];
        $controller->connect('key-pressed', function (GtkEventControllerKey $c, int $keyval) use ($pad, &$seen): bool {
            $phase = $c->get_propagation_phase()->name;
            $seen[$phase] = ($seen[$phase] ?? 0) + 1;
            $pad->set_text(sprintf('keyval 0x%x arrived in %s', $keyval, $phase));
            return false;
        });

        $readout = Demo::label();
        $readout->set_halign(GtkAlign::Start);
        $cases = GtkPropagationPhase::cases();
        $step = 0;
        $show = function () use ($readout, $controller, $cases, &$step, &$seen): void {
            $phase = $cases[$step % count($cases)];
            $controller->set_propagation_phase($phase);
            $current = $controller->get_propagation_phase();
            $rows = array_map(
                static fn(GtkPropagationPhase $p): string => sprintf(
                    '%s %-8s = %d   keys seen %d',
                    $p === $current ? '>' : ' ',
                    $p->name,
                    $p->value,
                    $seen[$p->name] ?? 0,
                ),
                $cases,
            );
            $readout->set_markup(sprintf(
                "<tt>%s\n\nget_propagation_phase() = <b>%s</b>%s</tt>",
                implode("\n", $rows),
                $current->name,
                $current === GtkPropagationPhase::None ? '  (controller off - no keys arrive)' : '',
            ));
            Demo::status($current->name);
        };

        $show();
        GLib::timeout_add(2500, function () use ($show, &$step): bool {
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
    340,
);
