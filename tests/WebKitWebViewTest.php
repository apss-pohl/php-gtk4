<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GAsyncResult;
use Gtk4\GdkTexture;
use Gtk4\GError;
use Gtk4\GLib;
use Gtk4\GTlsCertificate;
use Gtk4\JSCValue;
use Gtk4\WebKitCredential;
use Gtk4\WebKitCredentialPersistence;
use Gtk4\WebKitFindController;
use Gtk4\WebKitFindOptions;
use Gtk4\WebKitLoadEvent;
use Gtk4\WebKitNetworkSession;
use Gtk4\WebKitSettings;
use Gtk4\WebKitSnapshotOptions;
use Gtk4\WebKitSnapshotRegion;
use Gtk4\WebKitUserContentInjectedFrames;
use Gtk4\WebKitUserContentManager;
use Gtk4\WebKitUserScript;
use Gtk4\WebKitUserScriptInjectionTime;
use Gtk4\WebKitUserStyleLevel;
use Gtk4\WebKitUserStyleSheet;
use Gtk4\WebKitWebView;

/**
 * WebKitGTK from PHP (`--enable-gtk4-webkit`): a page loaded from a string, JavaScript
 * evaluated in it and answered with a {@see JSCValue}, script messages posted back to PHP,
 * user scripts and style sheets injected before the page runs, the find controller, a
 * snapshot, and the page source through its main resource. Everything here is offline: the
 * pages come from `load_html()`, nothing reaches the network.
 *
 * Skipped as a whole in a build without WebKit ({@see Features}).
 */
final class WebKitWebViewTest extends GtkTestCase
{
    private const PAGE = '<!DOCTYPE html><html><head><title>Hello</title></head>'
        . '<body><p id="p">needle and needle</p><script>window.answer = 42;</script></body></html>';

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

    /**
     * A web view, in a presented window, with $html loaded and finished.
     *
     * @param callable(WebKitWebView): void|null $prepare Runs before the load (user content, settings).
     */
    private function loaded(string $html = self::PAGE, ?callable $prepare = null): WebKitWebView
    {
        $view = new WebKitWebView();
        $win = $this->window();
        $win->set_default_size(320, 240);
        $win->set_child($view);
        $win->present();
        if ($prepare !== null) {
            $prepare($view);
        }
        $finished = false;
        $view->connect('load-changed', static function (
            WebKitWebView $v,
            WebKitLoadEvent $event,
        ) use (&$finished): void {
            if ($event === WebKitLoadEvent::Finished) {
                $finished = true;
            }
        });
        $view->load_html($html, 'file:///php-gtk4/');
        self::pump(static function () use (&$finished): bool {
            return $finished;
        });
        self::assertTrue($finished, 'the page never finished loading');
        return $view;
    }

    /** evaluate_javascript() driven to its answer: the JSCValue, or the GError it failed with. */
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

    public function testLoadsHtmlAndReportsWhatThePageSays(): void
    {
        $view = $this->loaded();
        // The title is a property notification of its own, a moment after the load finished.
        self::pump(static fn(): bool => $view->get_title() !== '');
        self::assertSame('Hello', $view->get_title());
        self::assertSame('file:///php-gtk4/', $view->get_uri());
        self::assertFalse($view->is_loading());
        self::assertEqualsWithDelta(1.0, $view->get_estimated_load_progress(), 1e-9);
        self::assertSame('Hello', $view->title, 'the GObject property reads the same');
    }

    public function testEvaluateJavascriptAnswersWithAJscValue(): void
    {
        $view = $this->loaded();

        $sum = self::evaluate($view, '1 + 2');
        self::assertInstanceOf(JSCValue::class, $sum);
        self::assertTrue($sum->is_number());
        self::assertSame(3, $sum->to_int32());

        $answer = self::evaluate($view, 'window.answer');
        self::assertInstanceOf(JSCValue::class, $answer);
        self::assertSame(42, $answer->to_int32());

        $title = self::evaluate($view, 'document.title + "!"');
        self::assertInstanceOf(JSCValue::class, $title);
        self::assertTrue($title->is_string());
        self::assertSame('Hello!', $title->to_string());

        $object = self::evaluate($view, '({ a: 1, b: [true, null] })');
        self::assertInstanceOf(JSCValue::class, $object);
        self::assertTrue($object->is_object());
        // WebKit's serializer orders the keys its own way; the content is what matters.
        self::assertEquals(['a' => 1, 'b' => [true, null]], json_decode($object->to_json(0), true));
        self::assertSame(1, $object->object_get_property('a')->to_int32());
    }

    public function testAJavascriptExceptionIsTheGErrorOfTheFinishCall(): void
    {
        $view = $this->loaded();
        $failure = self::evaluate($view, 'throw new Error("boom")');
        self::assertInstanceOf(GError::class, $failure);
        self::assertStringContainsString('boom', $failure->getMessage());
    }

    public function testScriptMessagesReachPhpAsJscValues(): void
    {
        /** @var list<string> $received */
        $received = [];
        $view = $this->loaded(self::PAGE, static function (WebKitWebView $view) use (&$received): void {
            $manager = $view->get_user_content_manager();
            self::assertTrue($manager->register_script_message_handler('php', null));
            $manager->connect('script-message-received::php', static function (
                WebKitUserContentManager $m,
                JSCValue $value,
            ) use (&$received): void {
                $received[] = $value->to_json(0);
            });
        });
        $posted = self::evaluate($view, 'window.webkit.messageHandlers.php.postMessage({hello: "php"}); "sent"');
        self::assertInstanceOf(JSCValue::class, $posted);
        self::pump(static function () use (&$received): bool {
            return $received !== [];
        });
        self::assertSame(['{"hello":"php"}'], $received);
    }

    public function testUserScriptsAndStyleSheetsRunBeforeThePage(): void
    {
        $view = $this->loaded(self::PAGE, static function (WebKitWebView $view): void {
            $manager = $view->get_user_content_manager();
            $manager->add_script(new WebKitUserScript(
                'window.injected = "at start";',
                WebKitUserContentInjectedFrames::AllFrames,
                WebKitUserScriptInjectionTime::Start,
            ));
            $manager->add_style_sheet(new WebKitUserStyleSheet(
                'p { color: rgb(255, 0, 0); }',
                WebKitUserContentInjectedFrames::AllFrames,
                WebKitUserStyleLevel::User,
            ));
        });
        $injected = self::evaluate($view, 'window.injected');
        self::assertInstanceOf(JSCValue::class, $injected);
        self::assertSame('at start', $injected->to_string());

        $color = self::evaluate($view, 'getComputedStyle(document.getElementById("p")).color');
        self::assertInstanceOf(JSCValue::class, $color);
        self::assertSame('rgb(255, 0, 0)', $color->to_string());
    }

    public function testTheSettingsAreTheViewsOwnObject(): void
    {
        $view = new WebKitWebView();
        $settings = $view->get_settings();
        self::assertInstanceOf(WebKitSettings::class, $settings);
        self::assertSame($settings, $view->get_settings(), 'one handle per object');

        $settings->set_enable_javascript(false);
        self::assertFalse($settings->get_enable_javascript());
        $settings->enable_javascript = true;
        self::assertTrue($settings->enable_javascript);

        $own = new WebKitSettings();
        $own->set_user_agent('php-gtk4/test');
        $view->set_settings($own);
        self::assertSame($own, $view->get_settings());
        self::assertSame('php-gtk4/test', $view->get_settings()->get_user_agent());
    }

    public function testJavascriptCanBeSwitchedOffBeforeALoad(): void
    {
        $view = $this->loaded(self::PAGE, static function (WebKitWebView $view): void {
            $view->get_settings()->set_enable_javascript(false);
        });
        // The page's own script did not run, and evaluate_javascript() is refused too.
        $failure = self::evaluate($view, 'window.answer');
        self::assertInstanceOf(GError::class, $failure);
    }

    public function testTheFindControllerCountsMatches(): void
    {
        $view = $this->loaded();
        $controller = $view->get_find_controller();
        self::assertInstanceOf(WebKitFindController::class, $controller);
        self::assertSame($view, $controller->get_web_view());

        $counted = null;
        $controller->connect('counted-matches', static function (
            WebKitFindController $c,
            int $count,
        ) use (&$counted): void {
            $counted = $count;
        });
        $controller->count_matches('needle', WebKitFindOptions::CASE_INSENSITIVE, 100);
        self::pump(static function () use (&$counted): bool {
            return $counted !== null;
        });
        self::assertSame(2, $counted);
        self::assertSame('needle', $controller->get_search_text());
        $controller->search_finish();
    }

    public function testASnapshotIsATexture(): void
    {
        $view = $this->loaded();
        $texture = null;
        $view->get_snapshot(
            WebKitSnapshotRegion::Visible,
            WebKitSnapshotOptions::NONE,
            null,
            static function (WebKitWebView $v, GAsyncResult $result) use (&$texture): void {
                try {
                    $texture = $v->get_snapshot_finish($result);
                } catch (GError $e) {
                    $texture = $e;   // "There was an error creating the snapshot": WebKit could not render here
                }
            },
        );
        self::pump(static function () use (&$texture): bool {
            return $texture !== null;
        });
        if ($texture instanceof GError) {
            // Rendering under Xvfb without GL is at WebKit's mercy: the answer is a GError
            // rather than a texture when its compositor has nothing to draw with.
            self::markTestSkipped('WebKit could not render a snapshot here: ' . $texture->getMessage());
        }
        self::assertInstanceOf(GdkTexture::class, $texture);
        self::assertGreaterThan(0, $texture->get_width());
        self::assertGreaterThan(0, $texture->get_height());
    }

    public function testTheMainResourceHandsBackThePageSource(): void
    {
        $view = $this->loaded();
        $resource = $view->get_main_resource();
        self::assertNotNull($resource);
        self::assertSame('file:///php-gtk4/', $resource->get_uri());

        $source = null;
        $resource->get_data(null, static function (mixed $r, GAsyncResult $result) use (&$source, $resource): void {
            $source = $resource->get_data_finish($result);
        });
        self::pump(static function () use (&$source): bool {
            return $source !== null;
        });
        self::assertIsString($source);
        self::assertStringContainsString('<title>Hello</title>', $source);
    }

    public function testALengthPastTheScriptIsRefused(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('Argument #2 ($length) must be -1 or at most the length of the script');
        new WebKitWebView()->evaluate_javascript('1', 2, null, null, null, null);
    }

    public function testThereIsNoMainResourceBeforeALoad(): void
    {
        self::assertNull(new WebKitWebView()->get_main_resource());
    }

    public function testViewsShareTheDefaultNetworkSession(): void
    {
        $session = new WebKitWebView()->get_network_session();
        self::assertInstanceOf(WebKitNetworkSession::class, $session);
        self::assertSame(WebKitNetworkSession::get_default(), $session);
        self::assertSame($session, new WebKitWebView()->get_network_session());
        self::assertSame($session->get_cookie_manager(), $session->get_cookie_manager());
    }

    /**
     * A page that did not come over TLS has no certificate to report - the whole point of the
     * out parameters being an "or null" answer rather than two values.
     */
    public function testTlsInfoIsNullForAPageThatIsNotOverTls(): void
    {
        self::assertNull($this->loaded()->get_tls_info());
    }

    /** A credential made for a certificate hands the same certificate back. */
    public function testACredentialCarriesTheCertificateItWasMadeFor(): void
    {
        $cert = GTlsCertificate::new_from_pem(GTlsCertificateTest::pem(), -1);
        $credential = WebKitCredential::new_for_certificate($cert, WebKitCredentialPersistence::None);

        self::assertTrue($credential->get_certificate()?->is_same($cert));
        // ...and one made from a password carries none, which is why the return is nullable.
        self::assertNull(new WebKitCredential('user', 'secret', WebKitCredentialPersistence::None)
            ->get_certificate());
    }

    public function testAPhpSubclassIsAWebView(): void
    {
        $view = new class extends WebKitWebView {
            public string $seen = '';
        };
        $loads = 0;
        $view->connect('load-changed', static function (
            WebKitWebView $v,
            WebKitLoadEvent $event,
        ) use (
            &$loads,
            $view
        ): void {
            if ($event === WebKitLoadEvent::Finished) {
                self::assertSame($view, $v, 'the handler gets the PHP object back');
                $view->seen = $v->get_uri();
                $loads++;
            }
        });
        $win = $this->window();
        $win->set_child($view);
        $view->load_html('<title>sub</title>', 'file:///sub/');
        self::pump(static function () use (&$loads): bool {
            return $loads > 0;
        });
        self::assertSame(1, $loads);
        self::assertSame('file:///sub/', $view->seen);
    }
}
