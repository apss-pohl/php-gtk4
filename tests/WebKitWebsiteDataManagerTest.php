<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GAsyncResult;
use Gtk4\GLib;
use Gtk4\WebKitWebsiteDataManager;
use Gtk4\WebKitWebsiteDataTypes;
use Gtk4\WebKitWebView;

/**
 * The stored-data side of a network session: what a web view has left behind, and clearing it.
 *
 * `clear()` is here because it is the one member of this class whose parameter is a `GTimeSpan` -
 * a GIR alias for `gint64` that the generator used to skip the method over. Everything else on
 * the class round-trips and is covered by the generated smoke test.
 *
 * Skipped as a whole in a build without WebKit ({@see Features}).
 */
final class WebKitWebsiteDataManagerTest extends GtkTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Features::requires('webkit');
    }

    /** The manager of the default session; a view is what brings the session into being. */
    private function manager(): WebKitWebsiteDataManager
    {
        return new WebKitWebView()->get_network_session()->get_website_data_manager();
    }

    /** Pump the main loop until $until() holds, at most $seconds (a network process has to start). */
    private static function pump(callable $until, float $seconds = 20.0): void
    {
        $deadline = microtime(true) + $seconds;
        while (!$until() && microtime(true) < $deadline) {
            GLib::main_context_iteration(false);
            usleep(1000);
        }
    }

    /**
     * A `GTimeSpan` is microseconds, and 0 means "everything, however old". The call is
     * asynchronous, so the answer is the finish() inside the callback.
     */
    public function testClearingDataOfATimespanAnswersThroughTheCallback(): void
    {
        $manager = $this->manager();
        $cleared = null;

        $manager->clear(
            WebKitWebsiteDataTypes::MEMORY_CACHE | WebKitWebsiteDataTypes::SESSION_STORAGE,
            0,
            null,
            static function (WebKitWebsiteDataManager $m, GAsyncResult $result) use (&$cleared): void {
                $cleared = $m->clear_finish($result);
            },
        );

        // By reference: an arrow function would capture $cleared as it is now (null) and the
        // pump would never see the callback land.
        self::pump(static function () use (&$cleared): bool {
            return $cleared !== null;
        });
        self::assertTrue($cleared, 'clear() never reported back');
    }

    /** An hour of history is as valid a span as all of it, and the last hour of a fresh profile is empty. */
    public function testATimespanIsMicroseconds(): void
    {
        $manager = $this->manager();
        $cleared = null;

        $manager->clear(
            WebKitWebsiteDataTypes::MEMORY_CACHE,
            3600 * 1000 * 1000,
            null,
            static function (WebKitWebsiteDataManager $m, GAsyncResult $result) use (&$cleared): void {
                $cleared = $m->clear_finish($result);
            },
        );

        self::pump(static function () use (&$cleared): bool {
            return $cleared !== null;
        });
        self::assertTrue($cleared);
    }

    /**
     * No callback is a legal fire-and-forget - WebKit is handed a null callback rather than a
     * trampoline into a callable nothing holds. The manager still works afterwards, which is
     * what the second, answered clear proves.
     */
    public function testClearingWithoutACallbackIsAllowed(): void
    {
        $manager = $this->manager();
        $manager->clear(WebKitWebsiteDataTypes::MEMORY_CACHE, 0, null, null);

        $cleared = null;
        $manager->clear(
            WebKitWebsiteDataTypes::MEMORY_CACHE,
            0,
            null,
            static function (WebKitWebsiteDataManager $m, GAsyncResult $result) use (&$cleared): void {
                $cleared = $m->clear_finish($result);
            },
        );
        self::pump(static function () use (&$cleared): bool {
            return $cleared !== null;
        });
        self::assertTrue($cleared);
    }
}
