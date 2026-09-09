<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkEntry;
use Gtk4\GtkEntryIconPosition;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkEntryIconPosition - which of a GtkEntry's two icon slots you mean.
 *
 * Every icon method on GtkEntry takes this enum first: Primary is the slot at
 * the start of the text, Secondary the one at the end. A timer moves an icon
 * between the two slots and swaps their tooltips and sensitivity, and clicking
 * either icon fires `icon-press` with the case that was hit.
 *
 *   bin/php-gtk4 examples/demo.php GtkEntryIconPosition
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkEntryIconPosition',
    'which of an entry\'s two icon slots you mean - Primary or Secondary',
    function (GtkWindow $win): GtkWidget {
        $entry = new GtkEntry();
        $entry->set_text('click an icon');
        $entry->set_hexpand(true);

        $cases = GtkEntryIconPosition::cases();
        $step = 0;
        $readout = Demo::label();
        $readout->set_halign(GtkAlign::Start);

        $show = function () use ($entry, $cases, $readout, &$step): void {
            $lit = $cases[$step % count($cases)];
            foreach ($cases as $pos) {
                $on = $pos === $lit;
                $entry->set_icon_from_icon_name($pos, $on ? 'starred-symbolic' : 'non-starred-symbolic');
                $entry->set_icon_sensitive($pos, $on);
                $entry->set_icon_tooltip_text($pos, $pos->name . ($on ? ' (lit)' : ''));
            }
            $rows = array_map(
                static fn(GtkEntryIconPosition $pos): string => sprintf(
                    '%-9s = %d  icon %-21s sensitive %-5s tooltip "%s"',
                    $pos->name,
                    $pos->value,
                    $entry->get_icon_name($pos) ?? '-',
                    $entry->get_icon_sensitive($pos) ? 'true' : 'false',
                    $entry->get_icon_tooltip_text($pos) ?? '',
                ),
                $cases,
            );
            $readout->set_markup('<tt>' . htmlspecialchars(implode("\n", $rows)) . '</tt>');
        };

        // The signal hands the case back as the same enum.
        $entry->connect('icon-press', function (GtkEntry $self, GtkEntryIconPosition $pos) use (&$step, $show): void {
            $step = $pos->value;
            $show();
            Demo::status('icon-press: GtkEntryIconPosition::' . $pos->name);
        });

        $show();
        GLib::timeout_add(1200, function () use ($show, &$step): bool {
            $step++;
            $show();
            return true;
        });

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($entry);
        $page->append($readout);
        return $page;
    },
    600,
    260,
);
