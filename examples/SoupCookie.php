<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkButton;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\SoupCookie;
use Gtk4\SoupSameSitePolicy;

/*
 * Gtk4\SoupCookie - an HTTP cookie as a value (a build with --enable-gtk4-webkit).
 *
 * libsoup is WebKitGTK's HTTP library, and this is the type its cookie store speaks:
 * WebKitCookieManager::add_cookie() takes one, get_cookies() hands them back. A cookie is a
 * boxed value here - assignment shares the handle, `clone` makes a copy of its own - and the
 * page shows what each flag does to the Set-Cookie header a server would send. Click to walk
 * through the flag combinations.
 *
 *   bin/php-gtk4 examples/demo.php SoupCookie
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'SoupCookie',
    'an HTTP cookie as a value: the fields, and the header they add up to',
    function (GtkWindow $win): GtkWidget {
        // What is switched on at each step, applied to a cookie the constructor just made.
        /** @var list<array{string, list<string>}> $steps */
        $steps = [
            ['as constructed', []],
            ['set_secure(true) - HTTPS only', ['secure']],
            ['+ set_http_only(true) - hidden from JavaScript', ['secure', 'http-only']],
            ['+ SameSite::Strict - never sent cross-site', ['secure', 'http-only', 'strict']],
        ];

        $label = Demo::label();
        $step = 0;
        $render = function () use (&$step, $steps, $label): void {
            [$name, $on] = $steps[$step % count($steps)];
            // A fresh cookie each time: max_age is seconds from now, -1 for a session cookie.
            $cookie = new SoupCookie('session', 'abc123', 'php-gtk4.test', '/', 3600);
            $cookie->set_secure(in_array('secure', $on, true));
            $cookie->set_http_only(in_array('http-only', $on, true));
            if (in_array('strict', $on, true)) {
                $cookie->set_same_site_policy(SoupSameSitePolicy::Strict);
            }
            $label->set_markup(sprintf(
                "<span size=\"x-large\"><b>%s</b>=<span foreground=\"%s\">%s</span></span>\n"
                . "<small>%s</small>\n\n"
                . "<tt>Set-Cookie: %s</tt>\n\n"
                . "<small>the client would send <tt>%s</tt>\nsame-site policy: <b>%s</b></small>",
                htmlspecialchars($cookie->get_name()),
                Demo::ACCENT,
                htmlspecialchars($cookie->get_value()),
                htmlspecialchars($name),
                htmlspecialchars($cookie->to_set_cookie_header()),
                htmlspecialchars($cookie->to_cookie_header()),
                $cookie->get_same_site_policy()->name,
            ));
            Demo::status(sprintf(
                'domain %s, path %s · secure: %s · http-only: %s',
                $cookie->get_domain(),
                $cookie->get_path(),
                $cookie->get_secure() ? 'yes' : 'no',
                $cookie->get_http_only() ? 'yes' : 'no',
            ));
        };

        $button = new GtkButton();
        $button->set_child($label);
        $button->connect('clicked', function () use (&$step, $render): void {
            $step++;
            $render();
        });
        $render();
        return $button;
    },
    460,
    300,
);
