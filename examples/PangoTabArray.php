<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkTextView;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\GtkWrapMode;
use Gtk4\PangoTabAlign;
use Gtk4\PangoTabArray;

/*
 * Gtk4\PangoTabArray - where the tab stops are.
 *
 * A text widget lays tabs out to the positions in one of these, in pixels or in Pango units.
 * The text below is tab-separated; click to move the stops and watch the columns line up
 * differently. Setting null puts the widget back on its default stops.
 *
 *   bin/php-gtk4 examples/demo.php PangoTabArray
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'PangoTabArray',
    'where the tab stops are, and what the columns do about it',
    function (GtkWindow $win): GtkWidget {
        $rows = [
            "name\tborn\tknown for",
            "Ada Lovelace\t1815\tthe first program",
            "Grace Hopper\t1906\tthe compiler",
            "Alan Turing\t1912\tthe machine",
        ];

        $view = new GtkTextView();
        $view->set_wrap_mode(GtkWrapMode::None);
        $view->set_editable(false);
        $view->set_monospace(true);
        $view->set_left_margin(12);
        $view->set_top_margin(12);
        $view->set_vexpand(true);
        $view->get_buffer()->set_text(implode("\n", $rows), -1);

        /** @var list<array{string, ?list<int>}> $steps */
        $steps = [
            ['the widget default (every 8 characters)', null],
            ['stops at 120 and 200 px', [120, 200]],
            ['stops at 160 and 320 px', [160, 320]],
            ['one stop at 90 px - the third column follows the default', [90]],
        ];

        $at = 0;
        $apply = function () use (&$at, $steps, $view): void {
            [$name, $positions] = $steps[$at % count($steps)];
            if ($positions === null) {
                $view->set_tabs(null);
            } else {
                $tabs = new PangoTabArray(count($positions), true);   // true: in pixels
                foreach ($positions as $i => $x) {
                    $tabs->set_tab($i, PangoTabAlign::Left, $x);
                }
                $view->set_tabs($tabs);
            }
            Demo::status($name . ' - click for the next');
        };

        $button = new GtkButton();
        $button->set_child(Demo::label('move the tab stops'));
        $button->connect('clicked', function () use (&$at, $apply): void {
            $at++;
            $apply();
        });

        $column = new GtkBox(GtkOrientation::Vertical, 12);
        $column->append($view);
        $column->append($button);
        $apply();
        return $column;
    },
    520,
    300,
);
