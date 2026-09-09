<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GMemoryOutputStream;
use Gtk4\GOutputStream;
use Gtk4\GtkButton;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GOutputStream - the write side of GIO, one chunk at a time.
 *
 * The base class every writable stream is, and the type the writing code here is written
 * against: write_bytes() takes a PHP string, flush() pushes it on, close() ends the stream, and
 * is_closed() / has_pending() say what state it is in. The page writes a sentence into a memory
 * stream a word at a time and shows how much has arrived.
 *
 *   bin/php-gtk4 examples/demo.php GOutputStream
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GOutputStream',
    'the write side of GIO, one chunk at a time',
    function (GtkWindow $win): GtkWidget {
        $words = ['GOutputStream ', 'takes ', 'what ', 'is ', 'written ', 'into ', 'it.'];

        $label = Demo::label();
        $stream = GMemoryOutputStream::new_resizable();
        $at = 0;

        // Written against the base class: nothing here knows where the bytes end up.
        $render = static function (GOutputStream $out, int $written) use ($label, $words): void {
            $done = $out->is_closed();
            $label->set_markup(sprintf(
                "<span size=\"x-large\"><b>%d</b> bytes written</span>\n\n<small>%s</small>",
                $written,
                $done
                    ? 'the stream is closed - click to start over'
                    : sprintf('%d of %d chunks · click for the next', $written === 0 ? 0 : 1, count($words)),
            ));
        };

        $button = new GtkButton();
        $button->set_child($label);
        $button->connect('clicked', function () use (&$stream, &$at, $words, $render): void {
            if ($stream->is_closed()) {
                $stream = GMemoryOutputStream::new_resizable();
                $at = 0;
            } elseif ($at >= count($words)) {
                $stream->close(null);
                Demo::status('closed - steal_as_bytes(): "' . $stream->steal_as_bytes() . '"');
                $render($stream, (int) $stream->get_data_size());
                return;
            } else {
                $stream->write_bytes($words[$at], null);
                $at++;
                Demo::status(sprintf('wrote "%s" · %d bytes so far', $words[$at - 1], $stream->get_data_size()));
            }
            $render($stream, (int) $stream->get_data_size());
        });
        $render($stream, 0);
        Demo::status('click to write the next chunk');
        return $button;
    },
    460,
    220,
);
