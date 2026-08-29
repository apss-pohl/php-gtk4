<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GObject;
use Gtk4\GParamSpec;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkDropDown;
use Gtk4\GtkOrientation;
use Gtk4\GtkStringFilterMatchMode;
use Gtk4\GtkStringList;
use Gtk4\GtkStringObject;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkDropDown - pick one item out of a list model.
 *
 * The widget is a button that pops up its model; the selection is a position
 * (`selected`) and the item at it (`selected-item`), both plain GObject
 * properties that emit `notify`. Built here with new_from_strings(), so the
 * items are GtkStringObjects and the built-in string factory draws them. The
 * buttons drive set_selected() and swap the model, the timer toggles search
 * (type into the popup to filter) and the arrow; the label reports every notify.
 *
 *   bin/php-gtk4 examples/demo.php GtkDropDown
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkDropDown',
    'pick one item out of a list model',
    function (GtkWindow $win): GtkWidget {
        $planets = ['Mercury', 'Venus', 'Earth', 'Mars', 'Jupiter', 'Saturn', 'Uranus', 'Neptune'];
        $dropdown = GtkDropDown::new_from_strings($planets);
        $dropdown->set_halign(GtkAlign::Center);

        $report = Demo::label();
        $describe = function () use ($dropdown, $report): void {
            $item = $dropdown->get_selected_item();
            $model = $dropdown->get_model();
            $report->set_markup(sprintf(
                "<tt>selected       %d\nselected-item  %s\nmodel          %d items of %s\n"
                . "enable-search  %s  (%s)\nshow-arrow     %s</tt>",
                $dropdown->get_selected(),
                $item instanceof GtkStringObject ? '"' . htmlspecialchars($item->get_string()) . '"' : 'null',
                $model?->get_n_items() ?? 0,
                $model?->get_item_type() ?? '-',
                $dropdown->get_enable_search() ? 'true' : 'false',
                $dropdown->get_search_match_mode()->name,
                $dropdown->get_show_arrow() ? 'true' : 'false',
            ));
        };

        // Both properties notify: from a click in the popup as much as from set_selected().
        $notified = 0;
        foreach (['notify::selected', 'notify::selected-item'] as $signal) {
            $dropdown->connect($signal, function (GObject $obj, GParamSpec $spec) use ($describe, &$notified): void {
                $notified++;
                Demo::status(sprintf('notify::%s #%d', $spec->get_name(), $notified));
                $describe();
            });
        }

        $next = GtkButton::new_with_label('set_selected(+1)');
        $next->connect('clicked', function () use ($dropdown): void {
            $count = $dropdown->get_model()?->get_n_items() ?? 0;
            if ($count > 0) {
                $dropdown->set_selected(($dropdown->get_selected() + 1) % $count);
            }
        });

        // set_model() takes any GListModel; a GtkStringList is the one the default factory can show.
        $swap = GtkButton::new_with_label('set_model(moons)');
        $swap->connect('clicked', function (GtkButton $self) use ($dropdown, $planets, $describe): void {
            $moons = $dropdown->get_model()?->get_n_items() === count($planets);
            $dropdown->set_model(new GtkStringList(
                $moons ? ['Io', 'Europa', 'Ganymede', 'Callisto', 'Titan', 'Triton'] : $planets,
            ));
            $self->set_label($moons ? 'set_model(planets)' : 'set_model(moons)');
            $describe();
        });

        // Search and the arrow are visible on the widget itself: cycle them.
        $step = 0;
        GLib::timeout_add(2000, function () use ($dropdown, $describe, &$step): bool {
            match ($step++ % 4) {
                0 => $dropdown->set_enable_search(true),
                1 => $dropdown->set_search_match_mode(GtkStringFilterMatchMode::Prefix),
                2 => $dropdown->set_show_arrow(false),
                default => (function () use ($dropdown): void {
                    $dropdown->set_enable_search(false);
                    $dropdown->set_search_match_mode(GtkStringFilterMatchMode::Substring);
                    $dropdown->set_show_arrow(true);
                })(),
            };
            $describe();
            return true;
        });

        $describe();

        $row = new GtkBox(GtkOrientation::Horizontal, 6);
        $row->set_halign(GtkAlign::Center);
        $row->append($next);
        $row->append($swap);

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($dropdown);
        $page->append($row);
        $page->append($report);
        return $page;
    },
    520,
    360,
);
