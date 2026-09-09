<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkEntry;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\WebKitFindController;
use Gtk4\WebKitFindOptions;
use Gtk4\WebKitWebView;

/*
 * Gtk4\WebKitFindController - find-in-page (needs --enable-gtk4-webkit).
 *
 * Type into the entry: every match is highlighted in the page, the window title counts them
 * (counted-matches), Next/Previous walk through them (search_next()/search_previous()) and the
 * found-text / failed-to-find-text signals say whether the walk landed on one.
 *
 *   bin/php-gtk4 examples/demo.php WebKitFindController
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitFindController',
    'Find in page: highlighted matches, a count, next and previous.',
    function (GtkWindow $win): GtkWidget {
        $view = new WebKitWebView();
        $view->set_vexpand(true);
        $view->set_hexpand(true);
        $view->load_html(<<<'HTML'
            <!DOCTYPE html><html><head><title>find</title><style>
              body { font: 15px sans-serif; margin: 24px; color: #241f31; background: #fdfdfd; line-height: 1.6 }
            </style></head><body>
              <h2>Grace Hopper</h2>
              <p>Grace Brewster Hopper was an American computer scientist, mathematician, and United
              States Navy rear admiral. She was a pioneer of computer programming. Hopper was the
              first to devise the theory of machine-independent programming languages, and used this
              theory to develop the FLOW-MATIC programming language and COBOL, an early
              high-level programming language still in use today.</p>
              <p>Hopper had attempted to enlist in the Navy during World War II but was rejected
              because she was 34 years old. She instead joined the Navy Reserves. Hopper began her
              computing career in 1944 when she worked on the Harvard Mark I team.</p>
            </body></html>
            HTML, 'file:///php-gtk4/');

        $controller = $view->get_find_controller();
        $controller->connect('counted-matches', static function (WebKitFindController $c, int $count): void {
            Demo::status(sprintf('"%s": %d match(es)', $c->get_search_text(), $count));
        });
        $controller->connect('found-text', static function (WebKitFindController $c, int $count): void {
            Demo::status(sprintf('"%s": on a match (%d in the page)', $c->get_search_text(), $count));
        });
        $controller->connect('failed-to-find-text', static function (WebKitFindController $c): void {
            Demo::status(sprintf('"%s": not found', $c->get_search_text()));
        });

        $entry = new GtkEntry();
        $entry->set_placeholder_text('find…');
        $entry->set_hexpand(true);
        $find = static function () use ($entry, $controller): void {
            $text = $entry->get_text();
            if ($text === '') {
                $controller->search_finish();   // drops the highlights
                Demo::status();
                return;
            }
            $options = WebKitFindOptions::CASE_INSENSITIVE | WebKitFindOptions::WRAP_AROUND;
            $controller->count_matches($text, $options, 1000);
            $controller->search($text, $options, 1000);
        };
        $entry->connect('changed', static function () use ($find): void {
            $find();
        });

        $previous = GtkButton::new_with_label('◀');
        $previous->connect('clicked', static function () use ($controller): void {
            $controller->search_previous();
        });
        $next = GtkButton::new_with_label('▶');
        $next->connect('clicked', static function () use ($controller): void {
            $controller->search_next();
        });

        $bar = new GtkBox(GtkOrientation::Horizontal, 6);
        $bar->append($entry);
        $bar->append($previous);
        $bar->append($next);
        $box = new GtkBox(GtkOrientation::Vertical, 6);
        $box->append($bar);
        $box->append($view);
        $entry->set_text('Hopper');
        return $box;
    },
    640,
    460,
);
