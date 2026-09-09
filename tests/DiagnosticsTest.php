<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GtkEntry;
use Gtk4\GtkWindow;

/**
 * GLib's own diagnostics reported as PHP errors (src/core/diagnostics.cpp).
 *
 * The reporting itself cannot happen where GLib logs - the writer runs inside GTK frames -
 * so what is worth pinning here is that it happens at all, that it names the PHP line that
 * caused it rather than wherever the message was recorded, and that `gtk4.diagnostics`
 * decides. `fatal`, which ends the process, is asserted in tests/phpt instead.
 */
final class DiagnosticsTest extends GtkTestCase
{
    /** Every test here provokes a GLib precondition on purpose; that is the subject. */
    protected function toleratesGtkCriticals(): bool
    {
        return true;
    }

    /** @var list<array{string,string,int}> */
    private array $seen = [];

    /**
     * Collect diagnostics as [message, file, line] for the duration of $body. GtkTestCase's
     * own handler stays underneath, so anything not collected here still fails the test.
     */
    private function collect(callable $body): void
    {
        $this->seen = [];
        set_error_handler(function (int $severity, string $message, string $file, int $line): bool {
            $this->seen[] = [$message, $file, $line];
            return true;
        }, E_WARNING);
        try {
            $body();
        } finally {
            restore_error_handler();
        }
    }

    public function testGlibCriticalBecomesAWarningAtTheCallingLine(): void
    {
        $entry = new GtkEntry();
        $entry->set_text('hello');
        $this->collect(function () use ($entry): void {
            $entry->get_chars(1, 0);
            $expectedLine = __LINE__ - 1;
            self::assertCount(1, $this->seen);
            [$message, $file, $line] = $this->seen[0];
            self::assertStringContainsString('gtk_editable_get_chars', $message);
            self::assertStringContainsString("assertion 'end_pos", $message);
            self::assertSame(__FILE__, $file);
            self::assertSame($expectedLine, $line);
        });
    }

    /** The domain GLib logged under is kept, so the message says who complained. */
    public function testMessageCarriesTheGlibDomain(): void
    {
        $entry = new GtkEntry();
        $this->collect(function () use ($entry): void {
            $entry->get_chars(5, 1);
        });
        self::assertNotSame([], $this->seen);
        self::assertMatchesRegularExpression('/^[A-Za-z0-9-]+: /', $this->seen[0][0]);
    }

    /** GTK keeps running: `warning` reports and returns, it does not abort the call. */
    public function testTheCallStillReturnsItsDeclaredType(): void
    {
        $entry = new GtkEntry();
        $entry->set_text('hello');
        $this->collect(function () use ($entry): void {
            self::assertSame('', $entry->get_chars(1, 0));
            self::assertSame('hello', $entry->get_chars(0, -1));
        });
    }

    /** A message raised while a PHP signal handler runs belongs to the handler's line. */
    public function testAttributionInsideASignalHandler(): void
    {
        $entry = new GtkEntry();
        $entry->set_text('hello');
        $win = $this->window();
        $line = 0;
        $win->connect('notify::title', function () use ($entry, &$line): void {
            $entry->get_chars(1, 0);
            $line = __LINE__ - 1;
        });
        $this->collect(function () use ($win): void {
            $win->set_title('x');
        });
        self::assertCount(1, $this->seen);
        self::assertSame(__FILE__, $this->seen[0][1]);
        self::assertSame($line, $this->seen[0][2]);
    }

    /** php-gtk4's own report of an uncaught handler exception is an E_WARNING, never fatal. */
    public function testUncaughtHandlerExceptionIsReportedAsAWarning(): void
    {
        $win = new GtkWindow();
        $win->connect('notify::title', function (): void {
            throw new \RuntimeException('boom');
        });
        $this->collect(function () use ($win): void {
            $win->set_title('x');
        });
        $win->destroy();
        self::assertCount(1, $this->seen);
        self::assertStringContainsString('uncaught RuntimeException', $this->seen[0][0]);
        self::assertStringContainsString('boom', $this->seen[0][0]);
    }

    public function testOffDropsTheMessageEntirely(): void
    {
        $entry = new GtkEntry();
        $entry->set_text('hello');
        $previous = (string) ini_get('gtk4.diagnostics');
        self::assertSame('warning', $previous, 'warning is the default the suite runs at');
        ini_set('gtk4.diagnostics', 'off');
        try {
            $this->collect(function () use ($entry): void {
                self::assertSame('', $entry->get_chars(1, 0));
            });
            self::assertSame([], $this->seen);
        } finally {
            ini_set('gtk4.diagnostics', $previous);
        }
    }

    /** An unknown mode is refused, so a typo cannot silently pick a policy. */
    public function testUnknownModeIsRefused(): void
    {
        self::assertFalse(@ini_set('gtk4.diagnostics', 'louder'));
        self::assertSame('warning', ini_get('gtk4.diagnostics'));
    }

    /** The report reaches a handler that only asked for E_WARNING, i.e. it is one. */
    public function testSeverityIsEWarning(): void
    {
        $entry = new GtkEntry();
        $entry->set_text('hello');
        $severity = 0;
        set_error_handler(function (int $s) use (&$severity): bool {
            $severity = $s;
            return true;
        });
        try {
            $entry->get_chars(1, 0);
        } finally {
            restore_error_handler();
        }
        self::assertSame(E_WARNING, $severity);
    }
}
