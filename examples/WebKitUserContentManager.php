<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkBox;
use Gtk4\GtkCheckButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\WebKitUserContentInjectedFrames;
use Gtk4\WebKitUserContentManager;
use Gtk4\WebKitUserScript;
use Gtk4\WebKitUserScriptInjectionTime;
use Gtk4\WebKitUserStyleLevel;
use Gtk4\WebKitUserStyleSheet;
use Gtk4\WebKitWebView;

/*
 * Gtk4\WebKitUserContentManager - what the application injects into every page it shows
 * (needs --enable-gtk4-webkit).
 *
 * A user style sheet restyles the page without touching its HTML, a user script runs in it
 * before or after the document is parsed. Both are removed again with remove_all_*(), and the
 * page shows the difference on its next load. (Script messages from the page to PHP, the
 * manager's other job, are in WebKitWebView.php.)
 *
 *   bin/php-gtk4 examples/demo.php WebKitUserContentManager
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitUserContentManager',
    'Style sheets and scripts the application injects into every page.',
    function (GtkWindow $win): GtkWidget {
        // A view is built with its manager (a construct-only property), so the one to add
        // content to is the view's own; a fresh `new WebKitUserContentManager()` would be for a
        // view built with it.
        $view = new WebKitWebView();
        $view->set_vexpand(true);
        $view->set_hexpand(true);
        $manager = $view->get_user_content_manager();

        $html = <<<'HTML'
            <!DOCTYPE html><html><head><title>user content</title><style>
              body { font: 15px sans-serif; margin: 24px; color: #241f31; background: #fdfdfd; }
            </style></head><body>
              <h2>A plain page</h2>
              <p>Its own style is light; its own script writes nothing.</p>
              <p id="mark">(no user script ran)</p>
            </body></html>
            HTML;
        $reload = static function () use ($view, $html): void {
            $view->load_html($html, 'file:///php-gtk4/');
        };

        $dark = new WebKitUserStyleSheet(
            'body { background: #241f31 !important; color: #fdfdfd !important; }',
            WebKitUserContentInjectedFrames::AllFrames,
            WebKitUserStyleLevel::User,
        );
        $stamp = new WebKitUserScript(
            'document.addEventListener("DOMContentLoaded", () => {'
            . ' document.getElementById("mark").textContent ='
            . ' "a user script ran at " + new Date().toLocaleTimeString(); });',
            WebKitUserContentInjectedFrames::AllFrames,
            WebKitUserScriptInjectionTime::Start,
        );

        $switches = new GtkBox(GtkOrientation::Vertical, 4);
        $styleCheck = GtkCheckButton::new_with_label('Dark user style sheet');
        $styleCheck->connect('toggled', static function (GtkCheckButton $c) use ($manager, $dark, $reload): void {
            if ($c->get_active()) {
                $manager->add_style_sheet($dark);
            } else {
                $manager->remove_style_sheet($dark);
            }
            Demo::status($c->get_active() ? 'style sheet added' : 'style sheet removed');
            $reload();
        });
        $scriptCheck = GtkCheckButton::new_with_label('User script at document start');
        $scriptCheck->connect('toggled', static function (GtkCheckButton $c) use ($manager, $stamp, $reload): void {
            if ($c->get_active()) {
                $manager->add_script($stamp);
            } else {
                $manager->remove_script($stamp);
            }
            Demo::status($c->get_active() ? 'script added' : 'script removed');
            $reload();
        });
        $switches->append($styleCheck);
        $switches->append($scriptCheck);

        $box = new GtkBox(GtkOrientation::Horizontal, 12);
        $box->append($switches);
        $box->append($view);
        $reload();
        return $box;
    },
    760,
    460,
);
