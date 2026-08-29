<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkGesturePan;
use Gtk4\GtkOrientation;
use Gtk4\GtkPanDirection;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkPanDirection - which way a GtkGesturePan went.
 *
 * A GEnum bound as a native PHP enum, the first argument of the `pan` signal:
 * Left/Right for a horizontal gesture, Up/Down for a vertical one. The label
 * cycles through the cases on a timer so every arrow is seen, and a real
 * GtkGesturePan on the label - horizontal by default - takes over the moment
 * you drag it, showing the direction GTK actually reported.
 *
 *   bin/php-gtk4 examples/demo.php GtkPanDirection
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkPanDirection',
    'which way a GtkGesturePan went',
    function (GtkWindow $win): GtkWidget {
        $face = Demo::label();
        $face->set_size_request(480, 260);
        $face->add_css_class('card');

        $arrows = [
            GtkPanDirection::Left->name => '←',
            GtkPanDirection::Right->name => '→',
            GtkPanDirection::Up->name => '↑',
            GtkPanDirection::Down->name => '↓',
        ];
        $cases = GtkPanDirection::cases();
        $step = 0;
        /** @var bool $fromInput */
        $fromInput = false;

        $show = static function (
            GtkPanDirection $dir,
            string $source,
            float $offset = 0.0,
        ) use (
            $face,
            $arrows,
            $cases
        ): void {
            $face->set_markup(sprintf(
                "<span size='xx-large'>%s</span>\n\n<b>GtkPanDirection::%s</b> <small>(%d)</small>\n%s\n\n"
                . '<small>%s</small>',
                $arrows[$dir->name],
                $dir->name,
                $dir->value,
                htmlspecialchars($source . ($offset > 0 ? sprintf(' by %.0f px', $offset) : '')),
                implode(' · ', array_map(static fn(GtkPanDirection $d): string => $d->name, $cases)),
            ));
        };

        $show($cases[0], 'cycling - drag me left or right');
        GLib::timeout_add(1000, function () use ($show, $cases, &$step, &$fromInput): bool {
            if ($fromInput) {
                return true;    // once real input arrives the timer stops overriding it
            }
            $step = ($step + 1) % count($cases);
            $show($cases[$step], 'cycling - drag me left or right');
            return true;
        });

        // The real thing: a horizontal pan only ever reports Left or Right.
        $pan = new GtkGesturePan(GtkOrientation::Horizontal);
        $face->add_controller($pan);
        $pan->connect('pan', function (
            GtkGesturePan $g,
            GtkPanDirection $dir,
            float $offset,
        ) use (
            $show,
            &$fromInput
        ): void {
            $fromInput = true;
            $show($dir, 'from the pan signal', $offset);
            Demo::status(sprintf('pan %s = GtkPanDirection::from(%d)', $dir->name, $dir->value));
        });

        return $face;
    },
    520,
    360,
);
