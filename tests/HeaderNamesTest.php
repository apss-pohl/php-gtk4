<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use PHPUnit\Framework\TestCase;

/**
 * No header under src/ may share its path, compared case-insensitively, with a
 * GLib/GTK header. Windows resolves includes on a case-insensitive filesystem
 * and GTK's include directories precede ours: `#include "Gio/GListModel.h"`
 * silently picked up GLib's gio/glistmodel.h there (MSVC: 'ce_GListModel' is
 * not a member of 'phpgtk'), and putting src/ first would break GLib's own
 * <gio/glistmodel.h> the same way. So our per-namespace directories must not
 * mirror GTK's header names - src/Gio/listmodel.h, not src/Gio/GListModel.h.
 */
final class HeaderNamesTest extends TestCase
{
    private const string ROOT = __DIR__ . '/..';

    public function testNoHeaderShadowsAGtkHeaderOnCaseInsensitiveFilesystems(): void
    {
        $dirs = [];
        foreach (['gtk4', 'glib-2.0', 'gobject-2.0', 'gio-2.0', 'cairo-gobject'] as $module) {
            $null = PHP_OS_FAMILY === 'Windows' ? 'NUL' : '/dev/null';
            $out = @shell_exec('pkg-config --cflags-only-I ' . $module . ' 2>' . $null);
            if (!is_string($out)) {
                continue;
            }
            preg_match_all('/-I(\S+)/', $out, $m);
            foreach ($m[1] as $dir) {
                $dirs[$dir] = true;
            }
        }
        if ($dirs === []) {
            self::markTestSkipped('pkg-config / GTK headers not available');
        }

        /** @var array<string, string> $theirs */
        $theirs = [];
        foreach (array_keys($dirs) as $dir) {
            if (!is_dir($dir)) {
                continue;
            }
            foreach (self::headers($dir) as $rel => $path) {
                $theirs[strtolower($rel)] = $path;
            }
        }
        self::assertNotEmpty($theirs, 'no GTK headers found under ' . implode(', ', array_keys($dirs)));

        $clashes = [];
        $src = realpath(self::ROOT . '/src');
        self::assertIsString($src);
        foreach (self::headers($src) as $rel => $path) {
            $key = strtolower($rel);
            if (isset($theirs[$key])) {
                $clashes[] = "src/$rel shadows {$theirs[$key]}";
            }
        }
        self::assertSame([], $clashes, 'rename these headers (see the class docblock)');
    }

    /**
     * Every *.h below $dir: relative path => absolute path.
     *
     * @return array<string, string>
     */
    private static function headers(string $dir): array
    {
        $out = [];
        $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS));
        foreach ($it as $file) {
            if (!$file instanceof \SplFileInfo || $file->getExtension() !== 'h') {
                continue;
            }
            $out[substr($file->getPathname(), strlen($dir) + 1)] = $file->getPathname();
        }

        return $out;
    }
}
