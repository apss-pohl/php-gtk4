<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkColorspace;
use Gtk4\GdkPixbuf;
use Gtk4\GMemoryInputStream;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\WebKitURISchemeRequest;
use Gtk4\WebKitURISchemeResponse;
use Gtk4\WebKitWebContext;
use Gtk4\WebKitWebView;

/*
 * Gtk4\WebKitURISchemeResponse - the fuller answer to a scheme request.
 *
 * finish() carries a stream, a length and a content type; a response object carries an HTTP
 * status as well, which is how a handler says "not found" and still returns a page. This one
 * serves a document at /, a PNG it draws with GdkPixbuf at /logo.png, and a 404 for everything
 * else - three content types and two statuses through one callable.
 *
 *   bin/php-gtk4 examples/demo.php WebKitURISchemeResponse
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitURISchemeResponse',
    'a scheme answered with a content type and an HTTP status',
    function (GtkWindow $win): GtkWidget {
        // Answer $request with $body, saying what it is and how it went.
        $respond = static function (
            WebKitURISchemeRequest $request,
            string $body,
            string $type,
            int $status,
            string $reason,
        ): void {
            $response = new WebKitURISchemeResponse(
                GMemoryInputStream::new_from_bytes($body),
                strlen($body),
            );
            $response->set_content_type($type);
            $response->set_status($status, $reason);
            $request->finish_with_response($response);
            Demo::status(sprintf('%s → %d %s, %s', $request->get_path(), $status, $reason, $type));
        };

        $handler = static function (WebKitURISchemeRequest $request) use ($respond): void {
            $path = $request->get_path();
            if ($path === '/logo.png') {
                $logo = new GdkPixbuf(GdkColorspace::Rgb, true, 8, 160, 90);
                $logo->fill(0x3584e4ff);
                $badge = new GdkPixbuf(GdkColorspace::Rgb, true, 8, 50, 50);
                $badge->fill(0xe01b24ff);
                $badge->composite($logo, 100, 30, 50, 50, 100.0, 30.0, 1.0, 1.0, \Gtk4\GdkInterpType::Nearest, 255);
                $respond($request, $logo->save_to_bufferv('png'), 'image/png', 200, 'OK');
                return;
            }
            if ($path !== '/' && $path !== '') {
                $respond(
                    $request,
                    '<!DOCTYPE html><title>404</title><h2>No such page</h2>'
                    . '<p><a href="site:/">back to the front page</a></p>',
                    'text/html',
                    404,
                    'Not Found',
                );
                return;
            }
            $respond(
                $request,
                sprintf(
                    '<!DOCTYPE html><html><head><title>site</title><style>
                       body { font: 15px sans-serif; margin: 24px; color: %s; background: %s; }
                       a { color: %s; }
                     </style></head><body>
                       <h2>A site out of one PHP callable</h2>
                       <p><img src="site:/logo.png" alt="drawn by GdkPixbuf"></p>
                       <p>The image above is a PNG this handler drew and served as
                          <code>image/png</code>. <a href="site:/missing">Ask for a page that
                          does not exist</a> to get a 404 that is still a document.</p>
                     </body></html>',
                    Demo::INK,
                    Demo::PAPER,
                    Demo::ACCENT,
                ),
                'text/html',
                200,
                'OK',
            );
        };

        // One registration per context; see WebKitURISchemeRequest.php.
        try {
            WebKitWebContext::get_default()->register_uri_scheme('site', $handler);
        } catch (\ValueError) {
            // already registered by an earlier visit to this page
        }

        $view = new WebKitWebView();
        $view->set_vexpand(true);
        $view->set_hexpand(true);
        $view->load_uri('site:/');
        return $view;
    },
    640,
    420,
);
