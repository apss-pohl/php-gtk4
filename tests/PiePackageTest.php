<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use PHPUnit\Framework\TestCase;

/**
 * The extension ships as a PIE package (`pie install php-gtk4/php-gtk4`) and the stubs as a
 * Composer one (`composer require --dev php-gtk4/stubs`), both out of this repository:
 * docs/RELEASING.md "Shipping".
 *
 * PIE reads the root composer.json and, on Windows, looks for a release asset whose name it
 * builds itself from `php-ext.extension-name` and the package version. Nothing in a normal
 * build fails when those drift apart - the first sign is `pie install` finding no asset, months
 * later - so the pieces are pinned to each other here: the declared extension name against the
 * extension this suite is running on, the declared configure options against config.m4, and the
 * release workflow's asset name against the shape php/pie's WindowsExtensionAssetName expects.
 */
final class PiePackageTest extends TestCase
{
    private const string ROOT = __DIR__ . '/..';

    /** @return array<string, mixed> */
    private static function json(string $file): array
    {
        $data = json_decode((string) file_get_contents(self::ROOT . '/' . $file), true);
        self::assertIsArray($data, "$file is not valid JSON");
        /** @var array<string, mixed> $data */
        return $data;
    }

    /** @return array<string, mixed> */
    private static function phpExt(): array
    {
        $block = self::json('composer.json')['php-ext'] ?? null;
        self::assertIsArray($block, 'composer.json has no php-ext block, so PIE cannot install it');
        /** @var array<string, mixed> $block */
        return $block;
    }

    public function testTheRootPackageIsAPhpExtension(): void
    {
        $composer = self::json('composer.json');
        self::assertSame('php-ext', $composer['type'] ?? null, 'PIE installs type php-ext only');
        self::assertSame('php-gtk4/php-gtk4', $composer['name'] ?? null);
        self::assertSame('MIT', $composer['license'] ?? null);
    }

    /**
     * PIE derives the extension name from the package name when php-ext.extension-name is
     * absent, which would give "php-gtk4" - the module is `gtk4`, so it has to be spelled out.
     */
    public function testTheDeclaredExtensionNameIsTheOneThatLoads(): void
    {
        $name = self::phpExt()['extension-name'] ?? null;
        self::assertSame('gtk4', $name);
        self::assertTrue(extension_loaded('gtk4'), 'the name PIE installs under must be this extension');
    }

    /** Both thread models build from source, so neither may be declared unsupported. */
    public function testBothThreadModelsAreDeclared(): void
    {
        $ext = self::phpExt();
        self::assertTrue($ext['support-nts'] ?? null, 'NTS is what the release binaries are');
        self::assertTrue($ext['support-zts'] ?? null, 'ZTS is built and tested in CI');
    }

    /** php/pie's OperatingSystemFamily; macOS is not a target, so it is not claimed. */
    public function testTheOperatingSystemsAreOnesPieKnows(): void
    {
        $families = self::phpExt()['os-families'] ?? null;
        self::assertIsArray($families);
        self::assertSame(['linux', 'windows'], $families);
    }

    /**
     * A configure option PIE offers that ./configure does not have fails the install of anyone
     * who passes it, and only them.
     */
    public function testEveryConfigureOptionExists(): void
    {
        $options = self::phpExt()['configure-options'] ?? [];
        self::assertIsArray($options);
        self::assertNotSame([], $options, 'at least --enable-gtk4-webkit is worth offering');

        $configure = (string) file_get_contents(self::ROOT . '/config.m4');
        foreach ($options as $option) {
            self::assertIsArray($option);
            $name = $option['name'] ?? null;
            self::assertIsString($name);
            // PIE spells it without the leading --; config.m4 declares it without --enable-.
            $switch = (string) preg_replace('/^(enable|with)-/', '', $name);
            self::assertStringContainsString(
                "PHP_ARG_ENABLE([$switch]",
                $configure,
                "php-ext.configure-options offers --$name, which config.m4 does not declare",
            );
            self::assertIsString($option['description'] ?? null, "$name needs a description");
        }
    }

    /**
     * On Windows PIE downloads a zip and never builds, so the release asset has to be named the
     * way it looks for it: php_<extension>-<version>-<php>-<ts|nts>-<compiler>-<arch>, lower
     * case, holding a dll of the same name (php/pie, Platform/WindowsExtensionAssetName).
     */
    public function testTheReleaseWorkflowNamesTheWindowsAssetTheWayPieLooksForIt(): void
    {
        $release = (string) file_get_contents(self::ROOT . '/.github/workflows/release.yml');
        if (preg_match('/^\s*dll-name:\s*(\S.*)$/m', $release, $m) !== 1) {
            self::fail('release.yml no longer passes a dll-name to windows-build.yml');
        }

        $name = trim($m[1]);
        $extension = self::phpExt()['extension-name'];
        self::assertIsString($extension);
        // The workflow expressions fill in version, PHP version and thread model.
        $expected = 'php_' . $extension . '-${{ needs.plan.outputs.relver }}'
            . '-${{ matrix.php }}-${{ matrix.ts }}-vs17-x86_64.dll';
        self::assertSame($expected, $name, 'the Windows asset name is no longer what PIE looks for');
        self::assertSame(strtolower($name), $name, 'PIE compares lower-cased names');

        // ...and the zip around it, which is the asset PIE actually downloads.
        self::assertStringContainsString('zip -qj "${dll%.dll}.zip" "$dll"', $release);
    }

    /** Both thread models are published, or a Windows ZTS `pie install` finds nothing. */
    public function testTheWindowsMatrixCoversBothThreadModels(): void
    {
        $release = (string) file_get_contents(self::ROOT . '/.github/workflows/release.yml');
        self::assertMatchesRegularExpression('/ts:\s*\[nts,\s*ts\]/', $release);
    }

    /** The stubs are their own package; the two names must not collide on Packagist. */
    public function testTheStubsAreASeparatePackage(): void
    {
        $stubs = self::json('stubs/composer.json');
        self::assertSame('php-gtk4/stubs', $stubs['name'] ?? null);
        self::assertNotSame(self::json('composer.json')['name'] ?? null, $stubs['name']);
    }
}
