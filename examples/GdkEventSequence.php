<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkEventSequence;
use Gtk4\GtkGestureClick;
use Gtk4\GtkGestureDrag;
use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkEventSequence - the identity of one touch point while a gesture runs.
 *
 * A gesture tracks every finger separately and every per-finger method takes the
 * sequence as its key: get_point($sequence), get_last_event($sequence),
 * get_sequence_state($sequence). The pointer has no sequence - its key is null - so
 * with a mouse every call below passes null and get_sequences() lists exactly that
 * one. On a touch screen each finger shows up as its own GdkEventSequence, and the
 * same finger is the same handle (===) for as long as it is down.
 *
 *   bin/php-gtk4 examples/demo.php GdkEventSequence
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkEventSequence',
    'one handle per finger - the key every per-touch gesture method takes',
    function (GtkWindow $win): GtkWidget {
        $area = new GtkLabel('press, drag and release here');
        $area->set_size_request(420, 220);
        $area->add_css_class('card');

        $readout = Demo::label('<i>waiting for a press</i>');
        $describe = function (GtkGestureDrag $drag, string $phase) use ($readout): void {
            $lines = ["<b>$phase</b>"];
            foreach ($drag->get_sequences() as $i => $sequence) {
                // GTK lists the pointer as null (get_debug_type() says so) although the GIR
                // types the elements non-null.
                $name = get_debug_type($sequence);
                $point = $drag->get_point($sequence);
                $event = $drag->get_last_event($sequence);
                $lines[] = sprintf(
                    'sequence %d: %s  point=%s  last event=%s  state=%s  (null = the pointer)',
                    $i,
                    htmlspecialchars($name),
                    $point === null ? '-' : sprintf('%.0f,%.0f', $point[0], $point[1]),
                    $event === null ? '-' : $event->get_event_type()->name,
                    $drag->get_sequence_state($sequence)->name,
                );
            }
            $current = $drag->get_current_sequence();
            $lines[] = 'get_current_sequence(): ' . ($current === null ? 'null' : $current::class);
            $readout->set_markup(implode("\n", $lines));
        };

        $drag = new GtkGestureDrag();
        $drag->connect('drag-begin', fn(GtkGestureDrag $g) => $describe($g, 'drag-begin'));
        $drag->connect('drag-update', fn(GtkGestureDrag $g) => $describe($g, 'drag-update'));
        $drag->connect('drag-end', fn(GtkGestureDrag $g) => $describe($g, 'drag-end'));
        $area->add_controller($drag);

        // The gesture signals themselves carry the sequence too (null for the pointer).
        $click = new GtkGestureClick();
        $click->connect('begin', function (GtkGestureClick $g, ?GdkEventSequence $sequence): void {
            Demo::status('begin: sequence ' . ($sequence === null ? 'null (pointer)' : $sequence::class));
        });
        $area->add_controller($click);

        $page = new \Gtk4\GtkBox(\Gtk4\GtkOrientation::Vertical, 12);
        $page->append($area);
        $page->append($readout);
        return $page;
    },
    520,
    380,
);
