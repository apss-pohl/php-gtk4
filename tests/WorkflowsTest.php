<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use PHPUnit\Framework\TestCase;

/**
 * The Windows CI and the Windows release job download the same prebuilt
 * gvsbuild GTK, pinned by GVSBUILD_VERSION in each workflow. Nothing bumps
 * that pin automatically (docs/BUILD.md "The pinned GTK version"); this only
 * guarantees the two never drift apart, so CI tests the GTK the release ships.
 */
final class WorkflowsTest extends TestCase
{
    private const string WORKFLOWS = __DIR__ . '/../.github/workflows';

    public function testGvsbuildPinIsTheSameInCiAndRelease(): void
    {
        $pins = [];
        foreach (['windows.yml', 'release.yml'] as $file) {
            $yml = (string) file_get_contents(self::WORKFLOWS . '/' . $file);
            $hit = preg_match("/^\s*GVSBUILD_VERSION:\s*'(\d{4}\.\d+\.\d+)'\s*$/m", $yml, $m);
            self::assertSame(1, $hit, "$file: no GVSBUILD_VERSION: 'YYYY.M.N' pin");
            $pins[$file] = $m[1];
        }
        self::assertSame(
            $pins['windows.yml'],
            $pins['release.yml'],
            'GVSBUILD_VERSION differs between windows.yml and release.yml - bump both together',
        );
    }
}
