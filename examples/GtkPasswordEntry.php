<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkPasswordEntry;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkPasswordEntry - the entry for secrets.
 *
 * Text is hidden behind dots, the keyboard layout is not switched, and a
 * peek icon (show_peek_icon) lets the user reveal what they typed. It
 * implements GtkEditable like GtkEntry does, so get_text() still hands the
 * real string to PHP - the readout counts characters and scores the secret
 * on every `changed`, and Enter (`activate`) accepts it. The button toggles
 * the peek icon.
 *
 *   bin/php-gtk4 examples/demo.php GtkPasswordEntry
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkPasswordEntry',
    'the entry for secrets - hidden text, a peek icon, GtkEditable underneath',
    function (GtkWindow $win): GtkWidget {
        $entry = new GtkPasswordEntry();
        $entry->set_show_peek_icon(true);
        $entry->placeholder_text = 'a secret, then Enter';
        $entry->set_text('hunter2');
        $entry->set_hexpand(true);

        $readout = Demo::label();
        $readout->set_halign(GtkAlign::Start);
        $describe = function () use ($entry, $readout): void {
            $text = $entry->get_text();
            $classes = 0;
            foreach (['/[a-z]/', '/[A-Z]/', '/\d/', '/[^a-zA-Z\d]/'] as $re) {
                $classes += preg_match($re, $text) === 1 ? 1 : 0;
            }
            $score = min(4, intdiv(strlen($text), 4) + $classes - 1);
            $readout->set_markup(sprintf(
                "<tt>get_text()          \"%s\"\nlength              %d\nshow_peek_icon      %s\n"
                . "placeholder_text    %s\nstrength            %s</tt>",
                htmlspecialchars($text),
                mb_strlen($text),
                $entry->get_show_peek_icon() ? 'true' : 'false',
                htmlspecialchars((string) $entry->placeholder_text),
                str_repeat('●', max(0, $score)) . str_repeat('○', 4 - max(0, $score)),
            ));
        };
        $entry->connect('changed', $describe);
        $entry->connect('activate', function (GtkPasswordEntry $self): void {
            Demo::status(sprintf('activate: %d characters accepted', mb_strlen($self->get_text())));
        });

        $peek = GtkButton::new_with_label('toggle peek icon');
        $peek->connect('clicked', function () use ($entry, $describe): void {
            $entry->set_show_peek_icon(!$entry->get_show_peek_icon());
            $describe();
        });
        $peek->set_halign(GtkAlign::Center);

        $describe();

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($entry);
        $page->append($peek);
        $page->append($readout);
        return $page;
    },
    520,
    340,
);
