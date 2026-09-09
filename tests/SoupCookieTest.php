<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\SoupCookie;
use Gtk4\SoupSameSitePolicy;

/**
 * An HTTP cookie as a value: what `WebKitCookieManager` stores, deletes and hands back.
 *
 * A boxed record, so a handle owns its copy - assignment shares it and `clone` does not
 * ({@see BoxedTest} for the machinery); the expiry (a `GDateTime`) and `applies_to_uri()` (a
 * `GUri`) are skipped until those types are bound (`gen/report.md`).
 *
 * Skipped as a whole in a build without WebKit ({@see Features}).
 */
final class SoupCookieTest extends GtkTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Features::requires('webkit');
    }

    /** max_age is seconds from now; -1 makes it a session cookie. */
    private static function cookie(): SoupCookie
    {
        return new SoupCookie('session', 'abc123', 'php-gtk4.test', '/', 3600);
    }

    public function testTheConstructorArgumentsAreWhatItCarries(): void
    {
        $cookie = self::cookie();

        self::assertSame('session', $cookie->get_name());
        self::assertSame('abc123', $cookie->get_value());
        self::assertSame('php-gtk4.test', $cookie->get_domain());
        self::assertSame('/', $cookie->get_path());
        self::assertFalse($cookie->get_secure(), 'a plain cookie is not HTTPS-only');
        self::assertFalse($cookie->get_http_only());
    }

    public function testEveryFieldWithASetterRoundTrips(): void
    {
        $cookie = self::cookie();
        $cookie->set_name('token');
        $cookie->set_value('xyz');
        $cookie->set_domain('example.test');
        $cookie->set_path('/api');
        $cookie->set_secure(true);
        $cookie->set_http_only(true);
        $cookie->set_same_site_policy(SoupSameSitePolicy::Strict);

        self::assertSame('token', $cookie->get_name());
        self::assertSame('xyz', $cookie->get_value());
        self::assertSame('example.test', $cookie->get_domain());
        self::assertSame('/api', $cookie->get_path());
        self::assertTrue($cookie->get_secure());
        self::assertTrue($cookie->get_http_only());
        self::assertSame(SoupSameSitePolicy::Strict, $cookie->get_same_site_policy());
    }

    /** The two header forms: what a client sends, and what a server sets. */
    public function testTheHeaderFormsAreWhatHttpWouldCarry(): void
    {
        $cookie = self::cookie();

        self::assertSame('session=abc123', $cookie->to_cookie_header());
        $set = $cookie->to_set_cookie_header();
        self::assertStringContainsString('session=abc123', $set);
        self::assertStringContainsString('domain=php-gtk4.test', $set);
        self::assertStringContainsString('path=/', $set);
    }

    public function testDomainMatchingFollowsTheCookieDomain(): void
    {
        $cookie = self::cookie();

        self::assertTrue($cookie->domain_matches('php-gtk4.test'));
        self::assertFalse($cookie->domain_matches('elsewhere.test'));
    }

    /** equal() compares name, value, domain and path - not the object identity. */
    public function testTwoCookiesOfTheSameShapeAreEqual(): void
    {
        $cookie = self::cookie();
        $same = self::cookie();
        $other = new SoupCookie('session', 'different', 'php-gtk4.test', '/', 3600);

        self::assertTrue($cookie->equal($same));
        self::assertFalse($cookie->equal($other));
    }

    /** A boxed handle is a value: the clone carries its own copy. */
    public function testCloningCopiesTheValue(): void
    {
        $cookie = self::cookie();
        $copy = clone $cookie;
        $copy->set_value('changed');

        self::assertSame('abc123', $cookie->get_value(), 'the original is untouched');
        self::assertSame('changed', $copy->get_value());
    }
}
