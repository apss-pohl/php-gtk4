<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkBox;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkOverflow;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkOverflow - whether a widget draws outside its allocation.
 *
 * A 120 px wide box holds a label that is much wider. Visible lets the text spill
 * over the neighbours, Hidden clips it at the box edge.
 *
 *   bin/php-gtk4 examples/demo.php GtkOverflow
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkOverflow',
    'whether a widget may draw outside its allocation',
    function (GtkWindow $win): GtkWidget {
        $row = new GtkBox(GtkOrientation::Horizontal, 12);
        $box = new GtkBox(GtkOrientation::Horizontal, 0);
        $box->set_size_request(120, 40);
        $box->set_hexpand(false);
        $label = new GtkLabel();
        $label->set_markup('<b>this text is far wider than its 120 px box</b>');
        $box->append($label);
        $row->append($box);
        $row->append(Demo::label('neighbour'));

        $cases = GtkOverflow::cases();
        $step = 0;
        $show = function () use ($box, $cases, &$step): void {
            $case = $cases[$step % count($cases)];
            $box->set_overflow($case);
            Demo::status(sprintf('set_overflow(GtkOverflow::%s)', $case->name));
        };
        $show();
        GLib::timeout_add(1500, function () use ($show, &$step): bool {
            $step++;
            $show();
            return true;
        });
        return $row;
    },
    480,
    340,
);
