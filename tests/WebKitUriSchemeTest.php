<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GAsyncResult;
use Gtk4\GError;
use Gtk4\GLib;
use Gtk4\GMemoryInputStream;
use Gtk4\JSCValue;
use Gtk4\WebKitLoadEvent;
use Gtk4\WebKitURISchemeRequest;
use Gtk4\WebKitURISchemeResponse;
use Gtk4\WebKitWebContext;
use Gtk4\WebKitWebView;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * A URI scheme answered from PHP: `register_uri_scheme()` hands the request to a PHP callable,
 * which finishes it with a stream, a response object or an error - the way an application serves
 * its own pages to a web view without a server.
 *
 * A scheme cannot be unregistered, and the default context is process-wide, so every test here
 * registers a name of its own.
 *
 * Skipped as a whole in a build without WebKit ({@see Features}).
 */
final class WebKitUriSchemeTest extends GtkTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Features::requires('webkit');
    }

    /** Pump the main loop until $until() holds, at most $seconds (a web process has to start). */
    private static function pump(callable $until, float $seconds = 20.0): void
    {
        $deadline = microtime(true) + $seconds;
        while (!$until() && microtime(true) < $deadline) {
            GLib::main_context_iteration(false);
            usleep(1000);
        }
    }

    /** A realized web view on the default context, which is the one the schemes are on. */
    private function view(): WebKitWebView
    {
        $view = new WebKitWebView();
        $win = $this->window();
        $win->set_default_size(320, 240);
        $win->set_child($view);
        $win->present();
        return $view;
    }

    /**
     * Load $uri and wait for the load to end. On the load-changed event, not on is_loading():
     * that is still false when load_uri() returns, so a pump on it exits before anything began.
     *
     * @return bool whether the load finished rather than failed
     */
    private function load(WebKitWebView $view, string $uri): bool
    {
        $ended = null;
        $view->connect('load-changed', static function (
            WebKitWebView $v,
            WebKitLoadEvent $event,
        ) use (&$ended): void {
            if ($event === WebKitLoadEvent::Finished) {
                $ended ??= true;
            }
        });
        $view->connect('load-failed', static function () use (&$ended): bool {
            $ended = false;
            return false;   // let WebKit show its error page, and finish the load
        });
        $view->load_uri($uri);
        self::pump(static function () use (&$ended): bool {
            return $ended !== null;
        });
        self::assertNotNull($ended, 'the load neither finished nor failed');
        return $ended;
    }

    private static function evaluate(WebKitWebView $view, string $script): JSCValue|GError
    {
        $answer = null;
        $view->evaluate_javascript($script, -1, null, null, null, static function (
            WebKitWebView $v,
            GAsyncResult $result,
        ) use (&$answer): void {
            try {
                $answer = $v->evaluate_javascript_finish($result);
            } catch (GError $e) {
                $answer = $e;
            }
        });
        self::pump(static function () use (&$answer): bool {
            return $answer !== null;
        });
        self::assertNotNull($answer, 'evaluate_javascript() never answered');
        return $answer;
    }

    /** The document title, once the web process has reported it (it lands after the load ends). */
    private static function title(WebKitWebView $view): string
    {
        self::pump(static function () use ($view): bool {
            return $view->get_title() !== '';
        }, 5.0);
        return $view->get_title();
    }

    /** A handler for a registration that must be refused: being called at all is the failure. */
    private static function neverCalled(): callable
    {
        return static function (WebKitURISchemeRequest $request): void {
            self::fail('a refused registration must not leave a handler behind');
        };
    }

    /** Answer $request with $html, the way a handler normally does. */
    private static function answer(WebKitURISchemeRequest $request, string $html): void
    {
        $request->finish(GMemoryInputStream::new_from_bytes($html), strlen($html), 'text/html');
    }

    public function testAPageServedFromPhpLoadsInTheView(): void
    {
        WebKitWebContext::get_default()->register_uri_scheme(
            'phpgtk-page',
            static function (WebKitURISchemeRequest $request): void {
                self::answer($request, '<!DOCTYPE html><title>Served</title><p id="p">from PHP</p>');
            },
        );

        $view = $this->view();
        self::assertTrue($this->load($view, 'phpgtk-page://hello/'));

        $text = self::evaluate($view, 'document.getElementById("p").textContent');
        self::assertInstanceOf(JSCValue::class, $text);
        self::assertSame('from PHP', $text->to_string());
        self::assertSame('Served', self::title($view), 'the served markup is what the page is');
    }

    /** What the handler is told about the request it has to answer. */
    public function testTheRequestSaysWhatWasAsked(): void
    {
        $seen = [];
        WebKitWebContext::get_default()->register_uri_scheme(
            'phpgtk-ask',
            static function (WebKitURISchemeRequest $request) use (&$seen): void {
                $seen = [
                    'uri' => $request->get_uri(),
                    'scheme' => $request->get_scheme(),
                    'path' => $request->get_path(),
                    'method' => $request->get_http_method(),
                ];
                self::answer($request, '<title>asked</title>');
            },
        );

        $this->load($this->view(), 'phpgtk-ask://host/some/path');

        self::assertSame('phpgtk-ask://host/some/path', $seen['uri'] ?? null);
        self::assertSame('phpgtk-ask', $seen['scheme'] ?? null);
        self::assertSame('/some/path', $seen['path'] ?? null);
        self::assertSame('GET', $seen['method'] ?? null);
    }

    /** The response object carries the content type and an HTTP status of the handler's choosing. */
    public function testFinishWithAResponseObject(): void
    {
        WebKitWebContext::get_default()->register_uri_scheme(
            'phpgtk-response',
            static function (WebKitURISchemeRequest $request): void {
                $body = '<!DOCTYPE html><title>Response</title>';
                $response = new WebKitURISchemeResponse(
                    GMemoryInputStream::new_from_bytes($body),
                    strlen($body),
                );
                $response->set_content_type('text/html');
                $response->set_status(200, 'OK');
                $request->finish_with_response($response);
            },
        );

        $view = $this->view();
        self::assertTrue($this->load($view, 'phpgtk-response://page/'));

        self::assertSame('Response', self::title($view));
    }

    /** A handler that refuses fails the load instead of serving anything. */
    public function testFinishErrorFailsTheLoad(): void
    {
        WebKitWebContext::get_default()->register_uri_scheme(
            'phpgtk-refuse',
            static function (WebKitURISchemeRequest $request): void {
                $request->finish_error(new GError('nothing here'));
            },
        );

        self::assertFalse(
            $this->load($this->view(), 'phpgtk-refuse://page/'),
            'the refused request failed the load instead of serving anything',
        );
    }

    /**
     * WebKit takes a scheme once ("Cannot register URI scheme X more than once", and the second
     * handler is dropped on the floor), so the binding refuses the duplicate rather than letting
     * that warning out - and the handler that is registered keeps working.
     */
    public function testASchemeCanOnlyBeRegisteredOnce(): void
    {
        $context = WebKitWebContext::get_default();
        $calls = 0;
        $context->register_uri_scheme(
            'phpgtk-once',
            static function (WebKitURISchemeRequest $request) use (&$calls): void {
                $calls++;
                self::answer($request, '<!DOCTYPE html><title>once</title>');
            },
        );

        try {
            $context->register_uri_scheme('phpgtk-once', self::neverCalled());
            self::fail('a second registration of the same scheme must throw');
        } catch (\ValueError $e) {
            self::assertStringContainsString(
                'Argument #1 ($scheme) is already registered on this context',
                $e->getMessage(),
            );
        }

        self::assertTrue($this->load($this->view(), 'phpgtk-once://page/'));
        self::assertSame(1, $calls, 'the registered handler still answers');
    }

    /**
     * A scheme is an RFC 3986 name, and WebKit refuses anything else with a warning and no
     * registration ("Cannot register invalid URI scheme x"), so the binding refuses it first.
     *
     * @param string $scheme
     */
    #[DataProvider('malformedSchemes')]
    public function testAMalformedSchemeIsAValueError(string $scheme): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('Argument #1 ($scheme) must be a URI scheme');
        WebKitWebContext::get_default()->register_uri_scheme($scheme, self::neverCalled());
    }

    /** @return iterable<string, array{string}> */
    public static function malformedSchemes(): iterable
    {
        yield 'empty' => [''];
        yield 'leading digit' => ['1scheme'];
        yield 'a number' => ['-1'];
        yield 'a float' => ['3.5'];
        yield 'underscore' => ['php_gtk4'];
        yield 'a colon' => ['phpgtk:'];
        yield 'a space' => ['php gtk4'];
        yield 'not ASCII' => ['schemé'];
    }
}
