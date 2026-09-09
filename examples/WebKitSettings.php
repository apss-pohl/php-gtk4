<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkBox;
use Gtk4\GtkCheckButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkScale;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\WebKitSettings;
use Gtk4\WebKitWebView;

/*
 * Gtk4\WebKitSettings - what a web view is allowed to do (needs --enable-gtk4-webkit).
 *
 * Every switch is a GObject property of the settings object (58 of them), so the check buttons
 * write `$settings->enable_javascript = …` and the page reacts on its next load. The zoom is
 * the view's own (set_zoom_level()), shown next to them because it is what one reaches for first.
 *
 *   bin/php-gtk4 examples/demo.php WebKitSettings
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitSettings',
    'The switches of a web view: JavaScript, images, the user agent, zoom.',
    function (GtkWindow $win): GtkWidget {
        $view = new WebKitWebView();
        $view->set_vexpand(true);
        $view->set_hexpand(true);
        $settings = $view->get_settings();
        $settings->set_user_agent('php-gtk4 example');

        $html = <<<'HTML'
            <!DOCTYPE html><html><head><title>settings</title><style>
              body { font: 15px sans-serif; margin: 24px; color: #241f31; background: #fdfdfd; }
              #js { color: #e01b24; } #js.on { color: #2ec27e; }
            </style></head><body>
              <h2>What this page can do</h2>
              <p id="js">JavaScript is <b>off</b>: this line never changed.</p>
              <p>An image: <img alt="a red square" width="40" height="40"
                 src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='40'%3E
                 %3Crect width='40' height='40' fill='%23e01b24'/%3E%3C/svg%3E"></p>
              <p>User agent: <code id="ua"></code></p>
              <script>
                document.getElementById('js').className = 'on';
                document.getElementById('js').innerHTML =
                  'JavaScript is <b>on</b>: this line was rewritten by a script.';
                document.getElementById('ua').textContent = navigator.userAgent;
              </script>
            </body></html>
            HTML;
        $reload = static function () use ($view, $html): void {
            $view->load_html($html, 'file:///php-gtk4/');
        };

        $switches = new GtkBox(GtkOrientation::Vertical, 4);
        foreach (
            [
                'enable_javascript' => 'JavaScript',
                'auto_load_images' => 'Load images',
                'enable_developer_extras' => 'Developer extras (right-click → Inspect)',
            ] as $property => $label
        ) {
            $check = GtkCheckButton::new_with_label($label);
            $check->set_active((bool) $settings->$property);
            $check->connect('toggled', static function (GtkCheckButton $c) use (
                $settings,
                $property,
                $reload
            ): void {
                $settings->$property = $c->get_active();   // a GObject property, written like a field
                Demo::status(sprintf('%s = %s', $property, $c->get_active() ? 'true' : 'false'));
                $reload();
            });
            $switches->append($check);
        }

        $zoom = GtkScale::new_with_range(GtkOrientation::Horizontal, 0.5, 3.0, 0.1);
        $zoom->set_value(1.0);
        $zoom->set_draw_value(true);
        $zoom->set_digits(1);
        $zoom->set_size_request(220, -1);
        $zoom->connect('value-changed', static function (GtkScale $s) use ($view): void {
            $view->set_zoom_level($s->get_value());
            Demo::status(sprintf('zoom %.1f', $view->get_zoom_level()));
        });
        $switches->append($zoom);

        $box = new GtkBox(GtkOrientation::Horizontal, 12);
        $box->append($switches);
        $box->append($view);
        $reload();
        // The settings are one object per view; a fresh one shows the defaults.
        $defaults = new WebKitSettings();
        Demo::status(sprintf(
            'user agent "%s" (default: "%s")',
            $settings->get_user_agent(),
            substr($defaults->get_user_agent(), 0, 40) . '…',
        ));
        return $box;
    },
    760,
    480,
);
