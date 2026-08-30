<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GIOCondition;
use Gtk4\GLib;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GIOCondition - what a socket watch waits for, and what it reports.
 *
 * GLib::io_add_watch() puts a socket on the GTK main loop: no polling between
 * manual iterations, no select() slices - the callback runs when the socket is
 * readable (IN), writable (OUT), hung up (HUP) and so on, with the condition bits
 * that actually happened. This page talks to itself over a socketpair: the button
 * writes on one end, the watch on the other end reads and reports the condition.
 *
 *   bin/php-gtk4 examples/demo.php GIOCondition
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GIOCondition',
    'the bits a socket watch waits for - a socket on the main loop',
    function (GtkWindow $win): GtkWidget {
        $names = ['IN' => GIOCondition::IN, 'PRI' => GIOCondition::PRI, 'OUT' => GIOCondition::OUT,
            'ERR' => GIOCondition::ERR, 'HUP' => GIOCondition::HUP, 'NVAL' => GIOCondition::NVAL];
        $decode = static function (int $bits) use ($names): string {
            $set = array_keys(array_filter($names, static fn(int $bit): bool => ($bits & $bit) !== 0));
            return $set === [] ? '(none)' : implode(' | ', $set);
        };

        $log = Demo::label();
        $lines = ['<b>GLib::io_add_watch($socket, GIOCondition::IN | GIOCondition::HUP, ...)</b>'];
        foreach ($names as $name => $bit) {
            $lines[] = sprintf('<tt>%-5s = %2d</tt>', $name, $bit);
        }
        $show = function (string $line) use (&$lines, $log): void {
            $lines[] = htmlspecialchars($line);
            if (count($lines) > 14) {
                array_splice($lines, 7, 1);
            }
            $log->set_markup(implode("\n", $lines));
        };
        $show('waiting - nothing on the socket yet');

        // A loopback connection rather than socketpair(): that one is AF_UNIX-only on Linux
        // and missing on Windows.
        $server = stream_socket_server('tcp://127.0.0.1:0');
        $name = $server === false ? false : stream_socket_get_name($server, false);
        $writer = $name === false ? false : stream_socket_client("tcp://$name");
        $reader = $server === false ? false : stream_socket_accept($server, 5);
        if ($server === false || $writer === false || $reader === false) {
            $show('could not open a loopback connection');
            return $log;
        }
        fclose($server);
        stream_set_blocking($reader, false);
        $count = 0;
        $onEvent = function (mixed $stream, int $condition) use (&$count, $decode, $show): bool {
            $count++;
            $data = is_resource($stream) ? (string) fread($stream, 64) : '';
            $show(sprintf(
                '#%d  condition %s  read %s',
                $count,
                $decode($condition),
                $data === '' ? '(nothing: hang-up)' : json_encode($data),
            ));
            Demo::status("$count callback(s), last: " . $decode($condition));
            return ($condition & GIOCondition::HUP) === 0;  // false after the peer closed
        };
        GLib::io_add_watch($reader, GIOCondition::IN | GIOCondition::HUP, $onEvent);

        $send = GtkButton::new_with_label('write on the other end');
        $send->connect('clicked', function () use ($writer, &$count): void {
            if (is_resource($writer)) {
                fwrite($writer, 'hello #' . ($count + 1));
            }
        });
        $close = GtkButton::new_with_label('close the other end (HUP)');
        $close->connect('clicked', function () use (&$writer, $close): void {
            if (is_resource($writer)) {
                fclose($writer);
                $close->set_sensitive(false);
            }
        });

        $buttons = new GtkBox(GtkOrientation::Horizontal, 8);
        $buttons->append($send);
        $buttons->append($close);
        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($log);
        $page->append($buttons);
        return $page;
    },
    560,
    400,
);
