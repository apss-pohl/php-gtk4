<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkEntry;
use Gtk4\GtkInputPurpose;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkInputPurpose - what kind of text an entry is for.
 *
 * A GEnum bound as a native PHP enum. GtkEntry::set_input_purpose() tells the
 * input method (and an on-screen keyboard) whether to offer digits, an email
 * layout, a phone pad - the entry itself looks the same. The timer cycles every
 * case on one entry, fills it with a matching sample so the change is visible,
 * and the readout shows get_input_purpose() and the $input_purpose property
 * agreeing.
 *
 *   bin/php-gtk4 examples/demo.php GtkInputPurpose
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkInputPurpose',
    'what kind of text an entry is for - digits, email, phone, password …',
    function (GtkWindow $win): GtkWidget {
        $entry = new GtkEntry();
        $entry->set_hexpand(true);

        // A sample per case, and whether the text stays visible - the purpose
        // alone never hides anything, secrets need set_visibility(false) on top.
        $samples = [
            GtkInputPurpose::FreeForm->name => ['anything at all', true],
            GtkInputPurpose::Alpha->name => ['letters', true],
            GtkInputPurpose::Digits->name => ['0123456789', true],
            GtkInputPurpose::Number->name => ['-12.5e3', true],
            GtkInputPurpose::Phone->name => ['+49 30 123456', true],
            GtkInputPurpose::Url->name => ['https://gtk.org', true],
            GtkInputPurpose::Email->name => ['someone@example.org', true],
            GtkInputPurpose::Name->name => ['Ada Lovelace', true],
            GtkInputPurpose::Password->name => ['hunter2', false],
            GtkInputPurpose::Pin->name => ['1234', false],
            GtkInputPurpose::Terminal->name => ['ls -la', true],
        ];
        $cases = GtkInputPurpose::cases();
        $step = 0;

        $readout = Demo::label();
        $readout->set_halign(GtkAlign::Start);
        $show = function () use ($entry, $cases, $samples, $readout, &$step): void {
            $purpose = $cases[$step % count($cases)];
            $entry->set_input_purpose($purpose);
            [$text, $visible] = $samples[$purpose->name];
            $entry->set_visibility($visible);
            $entry->set_text($text);
            $rows = array_map(
                static fn(GtkInputPurpose $c): string => sprintf(
                    '%s %-9s = %2d',
                    $c === $purpose ? '●' : '○',
                    $c->name,
                    $c->value,
                ),
                $cases,
            );
            $readout->set_markup(sprintf(
                "get_input_purpose() = <b>%s</b>, \$input_purpose = <b>%s</b>, visibility %s\n\n<tt>%s</tt>",
                $entry->get_input_purpose()->name,
                ($entry->input_purpose ?? $purpose)->name,
                $entry->get_visibility() ? 'true' : 'false',
                implode("\n", $rows),
            ));
        };

        $show();
        GLib::timeout_add(1000, function () use ($show, &$step): bool {
            $step++;
            $show();
            return true;
        });
        Demo::status(GtkInputPurpose::from(6)->name . ' = GtkInputPurpose::from(6)');

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($entry);
        $page->append($readout);
        return $page;
    },
    520,
    400,
);
