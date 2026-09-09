<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBaselinePosition;
use Gtk4\GtkBox;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkBaselinePosition - where a box places the baseline when its children are taller than the text.
 *
 * Two labels with very different font sizes are aligned on their baselines; the
 * box decides whether that baseline sits at the top, centre or bottom of the row.
 *
 *   bin/php-gtk4 examples/demo.php GtkBaselinePosition
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkBaselinePosition',
    'where a box puts the shared text baseline',
    function (GtkWindow $win): GtkWidget {
        $box = new GtkBox(GtkOrientation::Horizontal, 16);
        $box->set_size_request(300, 120);
        $big = new GtkLabel();
        $big->set_markup('<span size="xx-large">Big</span>');
        $small = new GtkLabel();
        $small->set_markup('<span size="small">small text on the same baseline</span>');
        foreach ([$big, $small] as $label) {
            $label->set_valign(GtkAlign::BaselineFill);
            $box->append($label);
        }

        $cases = GtkBaselinePosition::cases();
        $step = 0;
        $show = function () use ($box, $cases, &$step): void {
            $case = $cases[$step % count($cases)];
            $box->set_baseline_position($case);
            Demo::status(sprintf('set_baseline_position(GtkBaselinePosition::%s)', $case->name));
        };
        $show();
        GLib::timeout_add(1500, function () use ($show, &$step): bool {
            $step++;
            $show();
            return true;
        });
        return $box;
    },
    480,
    340,
);
