<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkOrientation;
use Gtk4\GtkRevealer;
use Gtk4\GtkRevealerTransitionType;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkRevealerTransitionType - how a GtkRevealer animates.
 *
 * A GEnum bound as a native PHP enum: None, Crossfade, four slides and four
 * swings. The page reveals and hides a card every second and moves to the next
 * case on each hide, so every transition plays once in both directions.
 *
 *   bin/php-gtk4 examples/demo.php GtkRevealerTransitionType
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkRevealerTransitionType',
    'how a GtkRevealer animates - slide, swing, crossfade or nothing',
    function (GtkWindow $win): GtkWidget {
        $revealer = new GtkRevealer();
        $revealer->set_transition_duration(700);
        $revealer->set_vexpand(true);

        $card = Demo::label();
        $card->add_css_class('card');
        $card->set_size_request(220, 100);
        $revealer->set_child($card);

        // cases() is the ordinary PHP enum API - these are real enum instances.
        $cases = GtkRevealerTransitionType::cases();

        // Even steps reveal, odd steps hide; every second step moves to the next case.
        $show = function (int $step) use ($revealer, $card, $cases): void {
            $type = $cases[intdiv($step, 2) % count($cases)];
            $revealer->set_transition_type($type);      // typed setter takes the enum, never an int
            $card->set_markup(sprintf(
                "<b>%s</b> <small>(%d)</small>\n\n<small>%s</small>",
                $type->name,
                $type->value,
                implode(' · ', array_map(static fn(GtkRevealerTransitionType $t): string => $t->name, $cases)),
            ));
            $revealer->set_reveal_child($step % 2 === 0);
            Demo::status(sprintf('%s → %s', $type->name, $step % 2 === 0 ? 'reveal' : 'hide'));
        };

        $show(0);
        $step = 0;
        GLib::timeout_add(1000, function () use ($show, &$step): bool {
            $show(++$step);
            return true;
        });

        $legend = Demo::label(GtkRevealerTransitionType::from(4)->name . ' = GtkRevealerTransitionType::from(4)');
        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $legend->set_halign(GtkAlign::Start);
        $page->append($legend);
        $page->append($revealer);
        return $page;
    },
    480,
    360,
);
