<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GAsyncResult;
use Gtk4\GError;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkEntry;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\JSCValue;
use Gtk4\WebKitLoadEvent;
use Gtk4\WebKitUserContentManager;
use Gtk4\WebKitWebView;

/*
 * Gtk4\WebKitWebView - a web page inside a GTK window (needs a build with --enable-gtk4-webkit).
 *
 * The page comes from a PHP string (load_html()), so nothing here touches the network; the
 * address bar loads whatever you type instead. Two directions of talking to the page: PHP runs
 * JavaScript in it (evaluate_javascript(), answered with a JSCValue), and the page posts messages
 * back (window.webkit.messageHandlers.php.postMessage(), a JSCValue in a signal handler).
 *
 *   bin/php-gtk4 examples/demo.php WebKitWebView
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitWebView',
    'A web page in the window: HTML from PHP, JavaScript run from PHP, messages posted back to PHP.',
    function (GtkWindow $win): GtkWidget {
        $view = new WebKitWebView();
        $view->set_vexpand(true);
        $view->set_hexpand(true);

        // The page: a counter the page itself keeps, a button that tells PHP about each click.
        $html = <<<'HTML'
            <!DOCTYPE html><html><head><title>php-gtk4</title><style>
              body { font: 15px sans-serif; margin: 24px; color: #241f31; background: #fdfdfd; }
              button { font: inherit; padding: 6px 14px; } #n { font-size: 40px; color: #3584e4; }
            </style></head><body>
              <h2>Hello from WebKitGTK</h2>
              <p>This document was handed to <code>load_html()</code> by PHP.</p>
              <p><span id="n">0</span> clicks</p>
              <button onclick="bump()">Click me</button>
              <script>
                let n = 0;
                function bump() {
                  document.getElementById('n').textContent = ++n;
                  window.webkit.messageHandlers.php.postMessage({ clicks: n });
                }
              </script>
            </body></html>
            HTML;

        // Messages from the page arrive as JSCValue on the user content manager.
        $manager = $view->get_user_content_manager();
        $manager->register_script_message_handler('php', null);
        $manager->connect('script-message-received::php', static function (
            WebKitUserContentManager $m,
            JSCValue $message,
        ): void {
            Demo::status('the page says ' . $message->to_json(0));
        });

        $view->connect('load-changed', static function (WebKitWebView $v, WebKitLoadEvent $event): void {
            if ($event === WebKitLoadEvent::Finished) {
                Demo::status(sprintf('loaded "%s" (%s)', $v->get_title(), $v->get_uri()));
            }
        });
        $view->connect('load-failed', static function (
            WebKitWebView $v,
            WebKitLoadEvent $e,
            string $uri,
            GError $error,
        ): bool {
            Demo::status(sprintf('load of %s failed: %s', $uri, $error->getMessage()));
            return true;
        });

        $address = new GtkEntry();
        $address->set_placeholder_text('https://… (or leave it and use the buttons)');
        $address->set_hexpand(true);
        $address->connect('activate', static function (GtkEntry $entry) use ($view): void {
            $view->load_uri($entry->get_text());
        });

        $home = GtkButton::new_with_label('Home');
        $home->connect('clicked', static function () use ($view, $html): void {
            $view->load_html($html, 'file:///php-gtk4/');
        });

        // PHP asks the page a question; the answer comes back asynchronously as a JSCValue.
        $ask = GtkButton::new_with_label('Ask the page');
        $ask->connect('clicked', static function () use ($view): void {
            $script = 'document.title + ": " + n + " click(s)"';
            $view->evaluate_javascript($script, -1, null, null, null, static function (
                WebKitWebView $v,
                GAsyncResult $result,
            ): void {
                try {
                    Demo::status('JavaScript answered: ' . $v->evaluate_javascript_finish($result)->to_string());
                } catch (GError $e) {
                    Demo::status('JavaScript failed: ' . $e->getMessage());
                }
            });
        });

        $bar = new GtkBox(GtkOrientation::Horizontal, 6);
        $bar->append($address);
        $bar->append($home);
        $bar->append($ask);

        $box = new GtkBox(GtkOrientation::Vertical, 6);
        $box->append($bar);
        $box->append($view);
        $view->load_html($html, 'file:///php-gtk4/');
        return $box;
    },
    720,
    520,
);
