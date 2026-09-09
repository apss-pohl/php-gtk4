<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkEntry;
use Gtk4\GtkInputHints;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkInputHints - the bitfield that tells input methods how to treat an entry.
 *
 * A GFlags type: PHP sees a final class of int constants (verified against
 * GTK's GFlagsClass when the extension loads) and GtkEntry::set_input_hints()
 * takes their OR. A timer walks through every flag, then a combination; the
 * readout decodes get_input_hints() back into names. Input methods and
 * on-screen keyboards act on these, so under a plain X display the entry
 * looks the same - what changes is what it *tells* the IM.
 *
 *   bin/php-gtk4 examples/demo.php GtkInputHints
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkInputHints',
    'the bitfield an entry hands to input methods - spellcheck, casing, emoji, OSK',
    function (GtkWindow $win): GtkWidget {
        $entry = new GtkEntry();
        $entry->set_placeholder_text('hints go to the input method');
        $entry->set_hexpand(true);

        $constants = array_filter(new \ReflectionClass(GtkInputHints::class)->getConstants(), 'is_int');
        // Every single flag, then two OR-ed combinations.
        $sequence = $constants + [
            'WORD_COMPLETION | SPELLCHECK' => GtkInputHints::WORD_COMPLETION | GtkInputHints::SPELLCHECK,
            'UPPERCASE_CHARS | NO_EMOJI | INHIBIT_OSK' => GtkInputHints::UPPERCASE_CHARS
                | GtkInputHints::NO_EMOJI
                | GtkInputHints::INHIBIT_OSK,
        ];
        $names = array_keys($sequence);
        $step = 0;

        $readout = Demo::label();
        $readout->set_halign(GtkAlign::Start);
        $show = function () use ($entry, $sequence, $names, $constants, $readout, &$step): void {
            $name = $names[$step % count($names)];
            $entry->set_input_hints($sequence[$name]);
            $hints = $entry->get_input_hints();
            $set = array_keys(array_filter(
                $constants,
                static fn(int $bit): bool => $bit !== 0 && ($hints & $bit) === $bit,
            ));
            $rows = array_map(
                static fn(string $k, int $v): string => sprintf(
                    '%s %-19s = %4d',
                    ($hints & $v) === $v && $v !== 0 ? '●' : '○',
                    $k,
                    $v,
                ),
                array_keys($constants),
                $constants,
            );
            $readout->set_markup(sprintf(
                "<b>set_input_hints(%s)</b>\nget_input_hints() = %d = %s\n\n<tt>%s</tt>",
                htmlspecialchars($name),
                $hints,
                $set === [] ? 'NONE' : implode(' | ', $set),
                implode("\n", $rows),
            ));
        };

        $show();
        GLib::timeout_add(900, function () use ($show, &$step): bool {
            $step++;
            $show();
            return true;
        });
        Demo::status(sprintf('%d flags on GtkInputHints', count($constants)));

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($entry);
        $page->append($readout);
        return $page;
    },
    520,
    440,
);
