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

    protected function tearDown(): void
    {
        foreach ($this->windows as $w) {
            $w->destroy();
        }
        $this->windows = [];
        Gtk::set_exception_handler(null);
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
        Gtk::set_exception_handler(function (string $m, string $o, int $c) use (&$captured): void {
            $captured = [$m, $o, $c];
        });
        $trigger();
        Gtk::set_exception_handler(null);
        return $captured;
    }
}
