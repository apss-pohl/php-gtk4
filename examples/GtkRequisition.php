<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkRequisition;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkRequisition - a widget's minimum and natural size as a value (boxed record).
 *
 * Click the button to make the label longer; every click reads
 * get_preferred_size() again and shows both requisitions. A requisition is a
 * value: ->width / ->height are plain properties, clone copies it, == compares.
 *
 *   bin/php-gtk4 examples/demo.php GtkRequisition
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkRequisition',
    'minimum and natural size as a value',
    function (GtkWindow $win): GtkWidget {
        $box = new GtkBox(GtkOrientation::Vertical, 12);
        $label = new GtkLabel('size me');
        $label->set_wrap(true);
        $label->set_max_width_chars(24);
        $report = new GtkLabel('');
        $report->set_use_markup(true);
        $show = function () use ($label, $report): void {
            [$min, $nat] = $label->get_preferred_size();
            $fmt = fn(GtkRequisition $r): string => "{$r->width} x {$r->height}";
            $report->set_markup("minimum <b>{$fmt($min)}</b>   natural <b>{$fmt($nat)}</b>");
        };
        $button = GtkButton::new_with_label('longer');
        $button->connect('clicked', function () use ($label, $show): void {
            $label->set_text($label->get_text() . ' and more');
            $show();
        });
        $show();
        $box->append($label);
        $box->append($report);
        $box->append($button);

        return $box;
    },
);
