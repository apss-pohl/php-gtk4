<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\Gtk;
use Gtk4\GtkWindow;
use PHPUnit\Framework\TestCase;

abstract class GtkTestCase extends TestCase
{
    /** @var list<GtkWindow> */
    private array $windows = [];

    private bool $latched = false;

    /**
     * Opaque flag for callbacks: PHPStan cannot see that a by-reference
     * closure variable changes when GTK invokes the handler later.
     */
    protected function latch(): \Closure
    {
        $this->latched = false;
        return function (): void {
            $this->latched = true;
        };
    }

    protected function latched(): bool
    {
        return $this->latched;
    }

    /**
     * Hide a callable's identity from static analysis so tests can pass
     * deliberately wrong arguments through it.
     */
    protected static function opaque(callable $c): callable
    {
        return $c;
    }

    protected function window(): GtkWindow
    {
        $w = new GtkWindow();
        $this->windows[] = $w;
        return $w;
    }

    /**
     * Whether this class is allowed to trip GLib preconditions.
     *
     * A GTK CRITICAL is how GTK says "PHP handed me something I refuse", so on an ordinary test
     * it means a missing guard at the boundary - the binding should have raised a PHP error
     * before reaching GTK. Only the suites whose job is to hand GTK bad values on purpose
     * (RobustnessTest's sweep, ArgumentGuardTest's pinned cases) and the ones that exercise the
     * g_critical fallback itself (ErrorTest) may answer true.
     */
    protected function toleratesGtkCriticals(): bool
    {
        return false;
    }

    private bool $criticalExpectedHere = false;

    /**
     * Mark *this* test as one that deliberately provokes a GLib precondition - the warning is
     * the behaviour under test, not a missing guard. Prefer fixing the boundary; use this only
     * where the point is what GTK does when its own rule is broken.
     */
    protected function expectsGtkCritical(): void
    {
        $this->criticalExpectedHere = true;
    }

    /** Test builds record GLib CRITICAL/WARNING lines so an unexpected one fails the test. */
    private static function capturesLogs(): bool
    {
        return str_contains((string) ini_get('gtk4.features'), 'testing=yes');
    }

    protected function setUp(): void
    {
        if (self::capturesLogs()) {
            Gtk::testing_capture_logs(true);
        }
    }

    protected function tearDown(): void
    {
        foreach ($this->windows as $w) {
            $w->destroy();
        }
        $this->windows = [];
        Gtk::set_exception_handler(null);

        if (!self::capturesLogs()) {
            return;
        }
        $logs = Gtk::testing_taken_logs();
        Gtk::testing_capture_logs(false);
        $tolerated = $this->criticalExpectedHere || $this->toleratesGtkCriticals();
        $this->criticalExpectedHere = false;
        if ($logs !== [] && !$tolerated) {
            self::fail(
                'GTK complained during this test - the binding should have refused the value '
                . "before GTK saw it:\n  " . implode("\n  ", array_unique($logs)),
            );
        }
    }

    /**
     * Run a callback that throws inside a signal handler and return what the
     * exception handler received: [message, origin, code] or null.
     *
     * @return array{0:string,1:string,2:int}|null
     */
    protected function captureHandlerException(callable $trigger): ?array
    {
        $captured = null;
        Gtk::set_exception_handler(function (\Throwable $e, string $origin) use (&$captured): void {
            $captured = [$e->getMessage(), $origin, $e->getCode()];
        });
        $trigger();
        Gtk::set_exception_handler(null);
        return $captured;
    }
}
