<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkButton;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\SoupMessageHeaders;
use Gtk4\SoupMessageHeadersType;

/*
 * Gtk4\SoupMessageHeaders - the headers of an HTTP request or response.
 *
 * What WebKitURIRequest::get_http_headers() hands back and what a URI scheme response can be
 * given. A name may repeat, so there are two readers - get_one() takes the last value,
 * get_list() joins them - and foreach() walks the lot. The page builds a request's headers a
 * step at a time; the block below is rendered from foreach(), so it is the headers themselves
 * talking, not a copy kept alongside.
 *
 *   bin/php-gtk4 examples/demo.php SoupMessageHeaders
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'SoupMessageHeaders',
    'the headers of an HTTP request, read back with foreach()',
    function (GtkWindow $win): GtkWidget {
        /** @var list<array{string, callable(SoupMessageHeaders): void}> $steps */
        $steps = [
            ['append("Accept", "text/html")', static function (SoupMessageHeaders $h): void {
                $h->append('Accept', 'text/html');
            }],
            ['append("Accept-Encoding", "gzip") twice over', static function (SoupMessageHeaders $h): void {
                $h->append('Accept-Encoding', 'gzip');
                $h->append('Accept-Encoding', 'br');
            }],
            ['replace("Accept", "*/*") - one value, whatever was there', static function (SoupMessageHeaders $h): void {
                $h->replace('Accept', '*/*');
            }],
            ['remove("Accept-Encoding")', static function (SoupMessageHeaders $h): void {
                $h->remove('Accept-Encoding');
            }],
            ['clear()', static function (SoupMessageHeaders $h): void {
                $h->clear();
            }],
        ];

        $headers = new SoupMessageHeaders(SoupMessageHeadersType::Request);
        $label = Demo::label();
        $step = 0;

        $render = function (string $did) use ($headers, $label): void {
            $lines = [];
            $headers->foreach(static function (string $name, string $value) use (&$lines): void {
                $lines[] = sprintf('%s: %s', $name, $value);
            });
            $label->set_markup(sprintf(
                "<span size=\"x-large\"><b>GET / HTTP/1.1</b></span>\n<tt>%s</tt>\n\n<small>%s</small>",
                $lines === []
                    ? '<span foreground="' . Demo::MUTED . '">(no headers)</span>'
                    : htmlspecialchars(implode("\n", $lines)),
                htmlspecialchars($did),
            ));
            Demo::status(sprintf(
                'get_one("Accept") = %s · get_list("Accept-Encoding") = %s',
                var_export($headers->get_one('Accept'), true),
                var_export($headers->get_list('Accept-Encoding'), true),
            ));
        };

        $button = new GtkButton();
        $button->set_child($label);
        $button->connect('clicked', function () use (&$step, $steps, $headers, $render): void {
            [$name, $apply] = $steps[$step % count($steps)];
            $apply($headers);
            $step++;
            $render($name . ' - click for the next step');
        });
        $render('click to build the headers up, one call at a time');
        return $button;
    },
    460,
    300,
);
