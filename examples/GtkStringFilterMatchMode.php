<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkDropDown;
use Gtk4\GtkOrientation;
use Gtk4\GtkStringFilterMatchMode;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkStringFilterMatchMode - where a search string may sit inside the text.
 *
 * A GEnum bound as a native PHP enum: Exact, Substring or Prefix. GtkDropDown's
 * popup search uses it (`search-match-mode`); the page cycles the mode on a real
 * drop-down with search enabled - open it and type to filter - and previews what
 * each mode would keep for a fixed query, so the difference shows without typing.
 *
 *   bin/php-gtk4 examples/demo.php GtkStringFilterMatchMode
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkStringFilterMatchMode',
    'where a search string may sit inside the text',
    function (GtkWindow $win): GtkWidget {
        $names = ['Ada', 'Adam', 'Amanda', 'Brenda', 'Dada', 'Nadia'];
        $query = 'ada';

        $dropdown = GtkDropDown::new_from_strings($names);
        $dropdown->set_enable_search(true);     // the popup grows a search entry
        $dropdown->set_halign(GtkAlign::Center);

        // The same rule GtkStringFilter applies, case-insensitively.
        $matches = static fn(GtkStringFilterMatchMode $mode, string $text): bool => match ($mode) {
            GtkStringFilterMatchMode::Exact => strcasecmp($text, $query) === 0,
            GtkStringFilterMatchMode::Prefix => stripos($text, $query) === 0,
            GtkStringFilterMatchMode::Substring => stripos($text, $query) !== false,
        };

        $face = Demo::label();
        $cases = GtkStringFilterMatchMode::cases();
        $step = 0;
        $show = function () use ($dropdown, $face, $cases, $names, $query, $matches, &$step): void {
            $mode = $cases[$step % count($cases)];
            $dropdown->set_search_match_mode($mode);        // typed setter takes the enum
            $current = $dropdown->search_match_mode ?? $mode;   // and the property hands it back
            $kept = array_values(array_filter($names, static fn(string $n): bool => $matches($mode, $n)));
            $face->set_markup(sprintf(
                "search-match-mode <b>%s</b> <small>(%d)</small>\n\n"
                . "<tt>typing \"%s\" keeps  %s</tt>\n\n<small>%s</small>",
                $current->name,
                $mode->value,
                $query,
                $kept === [] ? '(nothing)' : implode(', ', $kept),
                implode(' · ', array_map(
                    static fn(GtkStringFilterMatchMode $m): string => $m === $mode ? "<b>$m->name</b>" : $m->name,
                    $cases,
                )),
            ));
            Demo::status(sprintf('%s = GtkStringFilterMatchMode::from(%d)', $mode->name, $mode->value));
        };

        $show();
        GLib::timeout_add(1600, function () use ($show, &$step): bool {
            $step++;
            $show();
            return true;
        });

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->set_valign(GtkAlign::Center);
        $page->append($dropdown);
        $page->append($face);
        return $page;
    },
    520,
    340,
);
