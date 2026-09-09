<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\Gtk;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
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
     * Allocate $widget the way its parent would: measure it first and never below the minimum
     * it reports. A GTK built with consistency checks (gvsbuild's, which the Windows jobs use)
     * warns on either - "Allocating size to GtkBox ... without calling gtk_widget_measure()",
     * "Allocation height too small" - and GtkTestCase fails the test on a GTK warning.
     */
    protected function allocate(GtkWidget $widget, int $width, int $height): void
    {
        [$minWidth] = $widget->measure(GtkOrientation::Horizontal, -1);
        $width = max($width, $minWidth);
        [$minHeight] = $widget->measure(GtkOrientation::Vertical, $width);
        $widget->allocate($width, max($height, $minHeight));
    }

    /**
     * Whether this class is allowed to trip GLib preconditions.
     *
     * A GTK CRITICAL is how GTK says "PHP handed me something I refuse", so on an ordinary test
     * it means a missing guard at the boundary - the binding should have raised a PHP error
     * before reaching GTK. Only the suites whose job is to hand GTK bad values on purpose
     * (RobustnessTest's sweep, ArgumentGuardTest's pinned cases) and the ones that exercise the
     * uncaught-handler report itself (ErrorTest) may answer true.
     */
    protected function toleratesGtkCriticals(): bool
    {
        return false;
    }

    /** @var list<string> substrings passed to expectsGtkCritical() by the running test */
    private array $expectedCriticals = [];

    /** @var list<string> GLib CRITICAL/WARNING text, collected by the setUp() handler */
    private array $diagnostics = [];

    /** @var list<string> GLib MESSAGE text (E_NOTICE): advice, absorbed but never fatal */
    private array $notices = [];

    /**
     * Declare that *this* test deliberately provokes the GLib precondition whose message contains
     * $substring - the complaint is the behaviour under test, not a missing guard. Prefer fixing
     * the boundary; use this only where the point is what GTK does when its own rule is broken.
     *
     * The substring is asserted both ways in tearDown(): a critical that matches no declaration
     * fails the test, and a declaration that nothing matched fails it too, so neither a *new*
     * complaint nor a stale expectation can hide here. Call it once per distinct message.
     */
    protected function expectsGtkCritical(string $substring): void
    {
        $this->expectedCriticals[] = $substring;
    }

    /**
     * `gtk4.diagnostics` defaults to `warning`, so GLib's own messages arrive as E_WARNINGs we
     * collect here - the suite gates on exactly what a shipped build does. GTK's advice
     * (`G_LOG_LEVEL_MESSAGE` -> E_NOTICE, "GtkDialog mapped without a transient parent") is
     * collected too so it stays out of the run's output, but it never fails a test:
     * {@see takeGtkNotices()} is how a test that cares asserts one.
     */
    protected function setUp(): void
    {
        $this->diagnostics = [];
        $this->notices = [];
        set_error_handler(function (int $severity, string $message): bool {
            if ($severity === E_NOTICE) {
                $this->notices[] = $message;
            } else {
                $this->diagnostics[] = $message;
            }
            return true;
        }, E_WARNING | E_NOTICE);
    }

    /**
     * The GTK advice collected so far, and clear it.
     *
     * @return list<string>
     */
    protected function takeGtkNotices(): array
    {
        $taken = $this->notices;
        $this->notices = [];
        return $taken;
    }

    /**
     * The GLib CRITICAL/WARNING text collected so far, without clearing it - for a suite that
     * answers `toleratesGtkCriticals()` and gates on its own terms ({@see RobustnessTest}).
     *
     * @return list<string>
     */
    protected function gtkCriticals(): array
    {
        return array_values(array_unique($this->diagnostics));
    }

    protected function tearDown(): void
    {
        restore_error_handler();
        foreach ($this->windows as $w) {
            $w->destroy();
        }
        $this->windows = [];
        Gtk::set_exception_handler(null);

        $logs = array_values(array_unique($this->diagnostics));
        $expected = $this->expectedCriticals;
        $this->diagnostics = [];
        $this->notices = [];
        $this->expectedCriticals = [];
        if ($this->toleratesGtkCriticals()) {
            return;
        }

        $unexpected = array_values(array_filter($logs, static function (string $log) use ($expected): bool {
            foreach ($expected as $substring) {
                if (str_contains($log, $substring)) {
                    return false;
                }
            }
            return true;
        }));
        if ($unexpected !== []) {
            self::fail(
                'GTK complained during this test - the binding should have refused the value '
                . "before GTK saw it:\n  " . implode("\n  ", $unexpected),
            );
        }

        $unmatched = array_values(array_filter($expected, static function (string $substring) use ($logs): bool {
            foreach ($logs as $log) {
                if (str_contains($log, $substring)) {
                    return false;
                }
            }
            return true;
        }));
        if ($unmatched !== []) {
            self::fail(
                'expectsGtkCritical() declared a complaint GTK never made - drop it, or fix the '
                . "substring:\n  " . implode("\n  ", $unmatched),
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
