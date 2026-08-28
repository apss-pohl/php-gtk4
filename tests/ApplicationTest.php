<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GApplication;
use Gtk4\GObject;
use Gtk4\GtkApplication;
use Gtk4\GtkWindow;

/** Gtk4\GtkApplication - the preferred main loop. */
final class ApplicationTest extends GtkTestCase
{
    private static int $seq = 0;

    /** Unique, non-registering (NON_UNIQUE = 1 << 5) so parallel test runs never collide on D-Bus. */
    private static function app(): GtkApplication
    {
        self::$seq++;
        return new GtkApplication('org.phpgtk4.tests.app' . self::$seq . '.p' . getmypid(), 1 << 5);
    }

    public function testIsAGObjectWithAnId(): void
    {
        $app = self::app();
        self::assertInstanceOf(GObject::class, $app);
        self::assertStringStartsWith('org.phpgtk4.tests.app', (string) $app->get_application_id());
        self::assertSame($app->get_application_id(), $app->application_id, '@property access');
    }

    public function testAnonymousApplication(): void
    {
        $app = new GtkApplication(null, 1 << 5);
        self::assertNull($app->get_application_id());
    }


    public function testRunEmitsActivateAndReturnsStatus(): void
    {
        $app = self::app();
        $order = [];
        $app->connect('startup', function () use (&$order): void {
            $order[] = 'startup';
        });
        $app->connect('activate', function (GtkApplication $a) use (&$order): void {
            $order[] = 'activate';
            $a->quit();
        });
        $app->connect('shutdown', function () use (&$order): void {
            $order[] = 'shutdown';
        });
        $status = $app->run(['script.php']);
        self::assertSame(0, $status);
        self::assertSame(['startup', 'activate', 'shutdown'], $order);
    }

    public function testWindowKeepsApplicationRunningUntilClosed(): void
    {
        $app = self::app();
        $seen = null;
        $app->connect('activate', function (GtkApplication $a) use (&$seen): void {
            $win = new GtkWindow();
            $win->set_application($a);
            $win->set_title('app window');
            $win->present();
            $seen = $a->get_active_window();
            self::assertSame($win, $seen);
            self::assertSame($a, $win->get_application());
            $win->close();  // last window closes -> run() returns
        });
        self::assertSame(0, $app->run());
        self::assertInstanceOf(GtkWindow::class, $seen);
    }

    public function testAddWindowAndSetApplication(): void
    {
        $app = self::app();
        $app->connect('activate', function (GtkApplication $a): void {
            $win = new GtkWindow();
            self::assertNull($win->get_application());
            $a->add_window($win);
            self::assertSame($a, $win->get_application());
            $win->set_application(null);
            self::assertNull($win->get_application());
            $win->destroy();
            $a->quit();
        });
        $app->run();
    }

    public function testExceptionInActivateIsReportedAndRunReturns(): void
    {
        $app = self::app();
        $app->connect('activate', function (GtkApplication $a): void {
            $a->quit();
            throw new \RuntimeException('activate failed');
        });
        $got = $this->captureHandlerException(fn() => $app->run());
        self::assertSame(['activate failed', 'activate', 0], $got);
    }

    public function testGApplicationBaseApi(): void
    {
        $app = new GtkApplication(null, 0);
        self::assertInstanceOf(GApplication::class, $app);
        self::assertSame(0, $app->get_flags());
        $app->set_flags(1 << 5);
        self::assertSame(1 << 5, $app->get_flags());
        $app->set_inactivity_timeout(250);
        self::assertSame(250, $app->get_inactivity_timeout());
        self::assertFalse($app->get_is_registered(), 'registration happens in run()');
        $app->hold();
        $app->release();                        // balanced: no use count left behind
        self::assertNull($app->get_window_by_id(0));
    }

    public function testAcceleratorsForActions(): void
    {
        $app = self::app();
        self::assertSame([], $app->list_action_descriptions());
        $app->set_accels_for_action('app.quit', ['<Control>q', '<Control>w']);
        self::assertSame(['<Control>q', '<Control>w'], $app->get_accels_for_action('app.quit'));
        self::assertSame(['app.quit'], $app->get_actions_for_accel('<Control>q'));
        self::assertSame(['app.quit'], $app->list_action_descriptions());
        $app->set_accels_for_action('app.quit', []);
        self::assertSame([], $app->get_accels_for_action('app.quit'));
    }
}
