<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Callables attached to C objects that outlive the request (display, armed
 * sources) must be released in RSHUTDOWN, not after Zend is gone. Runs
 * tests/scripts/shutdown.php in a fresh process and checks it exits cleanly.
 */
final class ShutdownTest extends TestCase
{
    public function testProcessExitsCleanlyWithLiveCallbacks(): void
    {
        // GTK4_SO (tests/run.sh) or GTK4_DLL (tests/run.cmd) name the module under test.
        $so = getenv('GTK4_SO') ?: (getenv('GTK4_DLL') ?: './gtk4.so');
        $cmd = sprintf(
            '%s -n -dextension=%s %s 2>&1',
            escapeshellarg(PHP_BINARY),
            escapeshellarg($so),
            escapeshellarg(__DIR__ . '/scripts/shutdown.php'),
        );
        $out = [];
        exec($cmd, $out, $rc);
        $output = implode("\n", $out);
        self::assertSame(0, $rc, "exit code $rc:\n$output");
        self::assertStringContainsString('shutdown ok', $output);
        self::assertStringNotContainsString('never', $output);
        self::assertStringNotContainsString('Segmentation', $output);
        self::assertStringNotContainsString('CRITICAL', $output);
    }
}
