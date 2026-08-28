<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use PHPUnit\Framework\TestCase;

/**
 * The Windows CI and the Windows release job build with one recipe, windows-build.yml
 * (workflow_call), which is where the gvsbuild GTK pin GVSBUILD_VERSION lives. Nothing bumps
 * that pin automatically (docs/BUILD.md "The pinned GTK version"); this guarantees the pin
 * exists exactly once and both callers really use the shared recipe, so CI tests the GTK the
 * release ships.
 */
final class WorkflowsTest extends TestCase
{
    private const string WORKFLOWS = __DIR__ . '/../.github/workflows';

    public function testGvsbuildPinLivesInTheSharedWindowsRecipeOnly(): void
    {
        $shared = (string) file_get_contents(self::WORKFLOWS . '/windows-build.yml');
        $hit = preg_match("/^\s*GVSBUILD_VERSION:\s*'(\d{4}\.\d+\.\d+)'\s*$/m", $shared, $m);
        self::assertSame(1, $hit, "windows-build.yml: no GVSBUILD_VERSION: 'YYYY.M.N' pin");
        self::assertStringContainsString('workflow_call', $shared);

        foreach (['windows.yml', 'release.yml'] as $file) {
            $yml = (string) file_get_contents(self::WORKFLOWS . '/' . $file);
            self::assertDoesNotMatchRegularExpression(
                '/^\\s*GVSBUILD_VERSION:/m',
                $yml,
                "$file: the pin is defined in windows-build.yml only",
            );
            self::assertStringContainsString(
                'uses: ./.github/workflows/windows-build.yml',
                $yml,
                "$file: must call the shared Windows recipe",
            );
        }
    }
}
