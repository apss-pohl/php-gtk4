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

    /**
     * dl() registers every class at runtime, where OPcache leaves the enum case names
     * un-interned; the engine freed them with the wrong allocator when it unloaded the
     * module ("zend_mm_heap corrupted", src/core/enums.h). The Windows PHP bundles load
     * the extension this way.
     */
    public function testDlUnderOpcacheExitsCleanly(): void
    {
        $so = (string) realpath(getenv('GTK4_SO') ?: (getenv('GTK4_DLL') ?: './gtk4.so'));
        self::assertFileExists($so);
        $opcache = '';
        if (PHP_VERSION_ID < 80500) { // 8.5 builds OPcache in
            $file = PHP_OS_FAMILY === 'Windows' ? 'php_opcache.dll' : 'opcache.so';
            foreach ([ini_get('extension_dir') ?: '', dirname(PHP_BINARY) . '/ext'] as $dir) {
                if ($opcache === '' && is_file("$dir/$file")) {
                    $opcache = '-d ' . escapeshellarg("zend_extension=$dir/$file");
                }
            }
            self::assertNotSame('', $opcache, "no shared $file next to this PHP");
        }
        // dl() takes a bare file name from extension_dir. No ! " or % in the code: Windows'
        // escapeshellarg() blanks them.
        $code = 'dl(' . var_export(basename($so), true) . ');'
            . " echo Gtk4\\GtkAlign::from(Gtk4\\GtkAlign::Center->value)->name, ' ',"
            . " count(Gtk4\\ExceptionMode::cases()), ' ', is_array(opcache_get_status()) ? 'opcache' : '-';";
        $cmd = sprintf(
            '%s -n %s -d opcache.enable_cli=1 -d enable_dl=1 -d %s -r %s 2>&1',
            escapeshellarg(PHP_BINARY),
            $opcache,
            escapeshellarg('extension_dir=' . dirname($so)),
            escapeshellarg($code),
        );
        $out = [];
        exec($cmd, $out, $rc);
        $output = implode("\n", $out);
        self::assertSame(0, $rc, "exit code $rc:\n$output");
        self::assertSame('Center 2 opcache', $output);
    }
}
