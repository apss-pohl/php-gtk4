<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GAsyncResult;
use Gtk4\GError;
use Gtk4\GLib;
use Gtk4\GtkUriLauncher;

/**
 * Opening a URI in whatever the desktop uses for it - GTK 4.10's replacement for the deprecated
 * gtk_show_uri().
 *
 * Nothing here launches anything: a test that actually opened a URI would start a browser on
 * the machine running the suite. What is exercised is the URI the launcher carries and the
 * failure it reports when there is nothing to open, which is answered before any handler is
 * looked up.
 */
final class UriLauncherTest extends GtkTestCase
{
    public function testTheUriIsWhatItWasGiven(): void
    {
        $launcher = new GtkUriLauncher('https://php-gtk4.test/');
        self::assertSame('https://php-gtk4.test/', $launcher->get_uri());

        $launcher->set_uri('https://example.test/other');
        self::assertSame('https://example.test/other', $launcher->get_uri());
    }

    public function testALauncherCanStartWithoutOne(): void
    {
        $launcher = new GtkUriLauncher();
        self::assertNull($launcher->get_uri());

        $launcher->set_uri('https://php-gtk4.test/');
        $launcher->set_uri(null);
        self::assertNull($launcher->get_uri(), 'the URI can be taken back off');
    }

    /**
     * With no URI there is nothing to hand to a handler, and GTK says so through the finish
     * call rather than by opening something arbitrary.
     */
    public function testLaunchingWithoutAUriIsAnErrorFromTheFinish(): void
    {
        $launcher = new GtkUriLauncher();
        $outcome = null;

        $launcher->launch(null, null, static function (
            GtkUriLauncher $source,
            GAsyncResult $result,
        ) use (&$outcome): void {
            try {
                $outcome = $source->launch_finish($result);
            } catch (GError $e) {
                $outcome = $e;
            }
        });

        $deadline = microtime(true) + 5.0;
        while ($outcome === null && microtime(true) < $deadline) {
            GLib::main_context_iteration(false);
            usleep(1000);
        }

        self::assertInstanceOf(GError::class, $outcome, 'no URI is an error, not a launch');
    }

    /** The launcher is a plain object: no window is needed to hold one. */
    public function testTheParentWindowIsOptional(): void
    {
        $launcher = new GtkUriLauncher('https://php-gtk4.test/');

        // Reading it back is all this asserts - launch() itself is deliberately not called.
        self::assertSame('https://php-gtk4.test/', $launcher->get_uri());
    }
}
