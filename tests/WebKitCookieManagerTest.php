<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GAsyncResult;
use Gtk4\GCancellable;
use Gtk4\GError;
use Gtk4\GLib;
use Gtk4\SoupCookie;
use Gtk4\WebKitCookieManager;
use Gtk4\WebKitNetworkSession;

/**
 * Cookies in and out of a network session, which is what binding `SoupCookie` was for: the
 * manager takes a cookie value, stores it, hands it back and deletes it, all asynchronously.
 *
 * An ephemeral session, so nothing here touches the cookie store of a real profile.
 *
 * Skipped as a whole in a build without WebKit ({@see Features}).
 */
final class WebKitCookieManagerTest extends GtkTestCase
{
    private const URI = 'http://php-gtk4.test/';

    protected function setUp(): void
    {
        parent::setUp();
        Features::requires('webkit');
    }

    /** Pump the main loop until $until() holds, at most $seconds. */
    private static function pump(callable $until, float $seconds = 20.0): void
    {
        $deadline = microtime(true) + $seconds;
        while (!$until() && microtime(true) < $deadline) {
            GLib::main_context_iteration(false);
            usleep(1000);
        }
    }

    /** The session has to outlive the manager, so both are held for the length of a test. */
    private ?WebKitNetworkSession $session = null;

    private function manager(): WebKitCookieManager
    {
        $this->session = WebKitNetworkSession::new_ephemeral();
        return $this->session->get_cookie_manager();
    }

    private static function cookie(string $name = 'session', string $value = 'abc123'): SoupCookie
    {
        return new SoupCookie($name, $value, 'php-gtk4.test', '/', 3600);
    }

    /** @return list<SoupCookie> */
    private static function cookiesOf(WebKitCookieManager $manager, string $uri = self::URI): array
    {
        $cookies = null;
        $manager->get_cookies($uri, null, static function (
            WebKitCookieManager $m,
            GAsyncResult $result,
        ) use (&$cookies): void {
            $cookies = $m->get_cookies_finish($result);
        });
        self::pump(static function () use (&$cookies): bool {
            return $cookies !== null;
        });
        self::assertIsArray($cookies, 'get_cookies() never answered');
        return $cookies;
    }

    private static function add(WebKitCookieManager $manager, SoupCookie $cookie): void
    {
        $added = null;
        $manager->add_cookie($cookie, null, static function (
            WebKitCookieManager $m,
            GAsyncResult $result,
        ) use (&$added): void {
            $added = $m->add_cookie_finish($result);
        });
        self::pump(static function () use (&$added): bool {
            return $added !== null;
        });
        self::assertTrue($added, 'add_cookie() never reported back');
    }

    public function testACookieGoesInAndComesBackOut(): void
    {
        $manager = $this->manager();
        self::add($manager, self::cookie());

        $cookies = self::cookiesOf($manager);
        self::assertCount(1, $cookies);
        self::assertInstanceOf(SoupCookie::class, $cookies[0]);
        self::assertSame('session', $cookies[0]->get_name());
        self::assertSame('abc123', $cookies[0]->get_value());
        self::assertSame('php-gtk4.test', $cookies[0]->get_domain());
    }

    public function testDeletingTakesItBackOut(): void
    {
        $manager = $this->manager();
        self::add($manager, self::cookie());
        self::assertCount(1, self::cookiesOf($manager));

        $deleted = null;
        $manager->delete_cookie(self::cookie(), null, static function (
            WebKitCookieManager $m,
            GAsyncResult $result,
        ) use (&$deleted): void {
            $deleted = $m->delete_cookie_finish($result);
        });
        self::pump(static function () use (&$deleted): bool {
            return $deleted !== null;
        });

        self::assertTrue($deleted);
        self::assertSame([], self::cookiesOf($manager));
    }

    /** get_all_cookies() answers with every cookie of the session, whatever the host. */
    public function testAllCookiesSeesEveryHost(): void
    {
        $manager = $this->manager();
        self::add($manager, self::cookie('one'));
        self::add($manager, new SoupCookie('two', 'v', 'other.test', '/', 3600));

        $all = null;
        $manager->get_all_cookies(null, static function (
            WebKitCookieManager $m,
            GAsyncResult $result,
        ) use (&$all): void {
            $all = $m->get_all_cookies_finish($result);
        });
        self::pump(static function () use (&$all): bool {
            return $all !== null;
        });

        self::assertIsArray($all);
        $names = array_map(static fn(SoupCookie $c): string => $c->get_name(), $all);
        sort($names);
        self::assertSame(['one', 'two'], $names);
    }

    /** A cookie for another host is not handed out for this one. */
    public function testCookiesAreFetchedPerUri(): void
    {
        $manager = $this->manager();
        self::add($manager, self::cookie());

        self::assertSame([], self::cookiesOf($manager, 'http://elsewhere.test/'));
    }

    /**
     * A failed list-returning finish() has to throw rather than answer with an empty list: an
     * empty list is a legitimate result too, so only the GError tells them apart. An already
     * cancelled GCancellable makes the failure deterministic.
     */
    public function testACancelledFetchThrowsInsteadOfAnsweringEmpty(): void
    {
        $manager = $this->manager();
        self::add($manager, self::cookie());

        $cancellable = new GCancellable();
        $cancellable->cancel();
        $outcome = null;
        $manager->get_cookies(self::URI, $cancellable, static function (
            WebKitCookieManager $m,
            GAsyncResult $result,
        ) use (&$outcome): void {
            try {
                $outcome = $m->get_cookies_finish($result);
            } catch (GError $e) {
                $outcome = $e;
            }
        });
        self::pump(static function () use (&$outcome): bool {
            return $outcome !== null;
        });

        self::assertInstanceOf(GError::class, $outcome, 'a cancelled fetch is an error, not []');
    }
}
