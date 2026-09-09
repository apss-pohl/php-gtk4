<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkSizeGroup;
use Gtk4\GtkSizeGroupMode;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkSizeGroupMode - which dimension a GtkSizeGroup equalises.
 *
 * None leaves every member alone, Horizontal makes them share a width,
 * Vertical a height, Both both. Three buttons with different label lengths and
 * line counts are members of one group; the page cycles the mode so each case
 * shows on the buttons themselves.
 *
 *   bin/php-gtk4 examples/demo.php GtkSizeGroupMode
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkSizeGroupMode',
    'which dimension a size group equalises - none, width, height or both',
    function (GtkWindow $win): GtkWidget {
        $group = new GtkSizeGroup(GtkSizeGroupMode::None);

        $row = new GtkBox(GtkOrientation::Horizontal, 6);
        $row->set_halign(GtkAlign::Start);
        $row->set_valign(GtkAlign::Start);
        foreach (['short', "two\nlines", "the longest\nof the\nthree"] as $text) {
            $button = GtkButton::new_with_label($text);
            $group->add_widget($button);
            $row->append($button);
        }

        $cases = GtkSizeGroupMode::cases();
        $step = 0;
        $status = Demo::label();
        $show = function () use ($group, $cases, $status, &$step): void {
            $mode = $cases[$step % count($cases)];
            $group->set_mode($mode);
            $status->set_markup(sprintf(
                "mode <b>%s</b> <small>(%d)</small>\n<small>%s</small>",
                $group->get_mode()->name,
                $mode->value,
                implode(' · ', array_map(static fn(GtkSizeGroupMode $m): string => $m->name, $cases)),
            ));
        };

        $show();
        GLib::timeout_add(1200, function () use ($show, &$step): bool {
            $step++;
            $show();
            return true;
        });

        Demo::status(GtkSizeGroupMode::from(3)->name . ' = GtkSizeGroupMode::from(3)');

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($status);
        $page->append($row);
        return $page;
    },
    480,
    340,
);
