<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GMemoryInputStream;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\WebKitURISchemeRequest;
use Gtk4\WebKitWebContext;
use Gtk4\WebKitWebView;

/*
 * Gtk4\WebKitURISchemeRequest - PHP as the server behind a web view.
 *
 * register_uri_scheme() puts a PHP callable behind a URI scheme of your own, and every request
 * for it arrives here as a request object: which URI was asked for, along what path, with what
 * method. The handler answers with finish() - a stream, its length and a content type - so the
 * page below is generated per request, in PHP, with no network and no file on disk. The links
 * navigate within the scheme, and each one re-enters the handler.
 *
 *   bin/php-gtk4 examples/demo.php WebKitURISchemeRequest
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitURISchemeRequest',
    'PHP as the server behind a web view: a URI scheme answered from a callable',
    function (GtkWindow $win): GtkWidget {
        $served = 0;
        $handler = function (WebKitURISchemeRequest $request) use (&$served): void {
            $served++;
            $path = $request->get_path();
            $body = sprintf(
                '<!DOCTYPE html><html><head><title>%s</title><style>
                   body { font: 15px sans-serif; margin: 24px; color: %s; background: %s; }
                   dt { color: %s; font-size: 12px; text-transform: uppercase; }
                   dd { margin: 0 0 12px; font-family: monospace; font-size: 15px; }
                   a { color: %s; }
                 </style></head><body>
                   <h2>Served by PHP, request #%d</h2>
                   <dl>
                     <dt>uri</dt><dd>%s</dd>
                     <dt>scheme</dt><dd>%s</dd>
                     <dt>path</dt><dd>%s</dd>
                     <dt>method</dt><dd>%s</dd>
                   </dl>
                   <p>Go to <a href="demo:/one">/one</a>, <a href="demo:/two">/two</a>
                      or <a href="demo:/deeper/still">/deeper/still</a>.</p>
                 </body></html>',
                htmlspecialchars($path),
                Demo::INK,
                Demo::PAPER,
                Demo::MUTED,
                Demo::ACCENT,
                $served,
                htmlspecialchars($request->get_uri()),
                htmlspecialchars($request->get_scheme()),
                htmlspecialchars($path),
                htmlspecialchars($request->get_http_method()),
            );
            $request->finish(GMemoryInputStream::new_from_bytes($body), strlen($body), 'text/html');
            Demo::status(sprintf('%s → %d bytes of text/html', $request->get_uri(), strlen($body)));
        };

        // A scheme belongs to its context for good - WebKit has no unregister call and refuses a
        // second registration, which the binding reports as a ValueError. Mounting this page
        // again therefore finds the scheme already there, and the handler already installed.
        try {
            WebKitWebContext::get_default()->register_uri_scheme('demo', $handler);
        } catch (\ValueError) {
            // already registered by an earlier visit to this page
        }

        $view = new WebKitWebView();
        $view->set_vexpand(true);
        $view->set_hexpand(true);
        $view->load_uri('demo:/one');
        return $view;
    },
    640,
    420,
);
