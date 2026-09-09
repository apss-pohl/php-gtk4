<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\SoupMessageHeaders;
use Gtk4\SoupMessageHeadersType;

/**
 * The HTTP headers of a request or a response: what `WebKitURIRequest::get_http_headers()` hands
 * back and what a URI scheme response can be given.
 *
 * Skipped as a whole in a build without WebKit ({@see Features}).
 */
final class SoupMessageHeadersTest extends GtkTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Features::requires('webkit');
    }

    public function testHeadersAppendAndReadBackByName(): void
    {
        $headers = new SoupMessageHeaders(SoupMessageHeadersType::Request);
        $headers->append('Accept', 'text/html');
        $headers->append('X-Php-Gtk4', 'yes');

        self::assertSame('text/html', $headers->get_one('Accept'));
        self::assertSame('yes', $headers->get_one('X-Php-Gtk4'));
        self::assertNull($headers->get_one('Absent'), 'a header nothing set has no value');
        self::assertSame(SoupMessageHeadersType::Request, $headers->get_headers_type());
    }

    /** A name may appear more than once; get_list() joins them, get_one() takes the last. */
    public function testARepeatedHeaderIsAList(): void
    {
        $headers = new SoupMessageHeaders(SoupMessageHeadersType::Request);
        $headers->append('Accept-Encoding', 'gzip');
        $headers->append('Accept-Encoding', 'br');

        self::assertSame('gzip, br', $headers->get_list('Accept-Encoding'));
    }

    public function testReplaceAndRemove(): void
    {
        $headers = new SoupMessageHeaders(SoupMessageHeadersType::Response);
        $headers->append('Server', 'first');
        $headers->replace('Server', 'php-gtk4');
        self::assertSame('php-gtk4', $headers->get_one('Server'));

        $headers->remove('Server');
        self::assertNull($headers->get_one('Server'));
    }

    public function testClearEmptiesEverything(): void
    {
        $headers = new SoupMessageHeaders(SoupMessageHeadersType::Response);
        $headers->append('A', '1');
        $headers->append('B', '2');
        $headers->clear();

        self::assertNull($headers->get_one('A'));
        self::assertNull($headers->get_one('B'));
    }

    /** Header names are case-insensitive in HTTP, and libsoup keeps them that way. */
    public function testNamesAreCaseInsensitive(): void
    {
        $headers = new SoupMessageHeaders(SoupMessageHeadersType::Request);
        $headers->append('Content-Type', 'text/plain');

        self::assertSame('text/plain', $headers->get_one('content-type'));
        self::assertTrue($headers->header_equals('CONTENT-TYPE', 'text/plain'));
        self::assertFalse($headers->header_equals('Content-Type', 'text/html'));
    }

    public function testHeaderContainsLooksInsideAList(): void
    {
        $headers = new SoupMessageHeaders(SoupMessageHeadersType::Request);
        $headers->append('Cache-Control', 'no-cache, no-store');

        self::assertTrue($headers->header_contains('Cache-Control', 'no-store'));
        self::assertFalse($headers->header_contains('Cache-Control', 'immutable'));
    }

    public function testContentLengthIsANumber(): void
    {
        $headers = new SoupMessageHeaders(SoupMessageHeadersType::Response);
        $headers->set_content_length(2048);

        self::assertSame(2048, $headers->get_content_length());
        self::assertSame('2048', $headers->get_one('Content-Length'));
    }

    /** foreach() is a call-scope callback: every name/value pair, in order. */
    public function testForeachVisitsEveryHeader(): void
    {
        $headers = new SoupMessageHeaders(SoupMessageHeadersType::Request);
        $headers->append('Accept', 'text/html');
        $headers->append('Accept-Encoding', 'gzip');
        $headers->append('Accept-Encoding', 'br');

        $seen = [];
        $headers->foreach(static function (string $name, string $value) use (&$seen): void {
            $seen[] = "$name: $value";
        });

        self::assertSame(
            ['Accept: text/html', 'Accept-Encoding: gzip', 'Accept-Encoding: br'],
            $seen,
        );
    }

    /** The callable is released with the call: a throw inside it reaches PHP, not GLib. */
    public function testAThrowInsideForeachReachesTheHandler(): void
    {
        $headers = new SoupMessageHeaders(SoupMessageHeadersType::Request);
        $headers->append('Accept', 'text/html');

        $captured = $this->captureHandlerException(static function () use ($headers): void {
            $headers->foreach(static function (): void {
                throw new \RuntimeException('from foreach');
            });
        });

        self::assertNotNull($captured, 'the exception reached the handler');
        self::assertSame('from foreach', $captured[0]);
        self::assertSame('SoupMessageHeaders::foreach', $captured[1], 'the origin names the caller');
    }
}
