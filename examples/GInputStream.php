<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GInputStream;
use Gtk4\GMemoryInputStream;
use Gtk4\GtkButton;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GInputStream - the read side of GIO, one chunk at a time.
 *
 * The base class every readable stream is, and the type the reading code here is written
 * against: read_bytes() takes up to as much as is asked for and answers with a PHP string,
 * close() ends the stream, and is_closed() / has_pending() are how a program asks what state it
 * is in. The page walks a sentence through a stream on every click, so the label fills up as the
 * stream empties; the click after the last byte closes it, and the one after that starts over.
 *
 *   bin/php-gtk4 examples/demo.php GInputStream
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GInputStream',
    'the read side of GIO, one chunk at a time',
    function (GtkWindow $win): GtkWidget {
        $text = 'GInputStream reads bytes; GMemoryInputStream holds them.';

        $label = Demo::label();
        $read = '';

        // Written against the base class: nothing here knows where the bytes come from.
        $render = static function (GInputStream $stream, string $read) use ($label, $text): void {
            $done = $stream->is_closed();
            $label->set_markup(sprintf(
                "<span size=\"x-large\"><b>%s</b></span>\n<span foreground=\"%s\">%s</span>\n\n<small>%s</small>",
                htmlspecialchars($read === '' ? '…' : $read),
                Demo::MUTED,
                htmlspecialchars(substr($text, strlen($read))),
                $done ? 'the stream is closed - click to start over' : 'click to read the next 12 bytes',
            ));
            Demo::status(sprintf(
                '%d of %d bytes read · is_closed(): %s · has_pending(): %s',
                strlen($read),
                strlen($text),
                $done ? 'yes' : 'no',
                $stream->has_pending() ? 'yes' : 'no',
            ));
        };

        $stream = GMemoryInputStream::new_from_bytes($text);
        $advance = function () use (&$stream, &$read, $text, $render): void {
            if ($stream->is_closed()) {
                $stream = GMemoryInputStream::new_from_bytes($text);
                $read = '';
            } elseif (($chunk = $stream->read_bytes(12, null)) === '') {
                $stream->close(null);   // nothing left to read
            } else {
                $read .= $chunk;
            }
            $render($stream, $read);
        };

        $button = new GtkButton();
        $button->set_child($label);
        $button->connect('clicked', $advance);
        $render($stream, $read);
        return $button;
    },
);
