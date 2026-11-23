<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Composer\Semver\Comparator;
use Composer\Semver\VersionParser;
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
 * release workflow's asset name against the shape php/pie's WindowsExtensionAssetName expects, and
 * its dev-build tag against Composer's own version parser.
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
     *
     * That class accepts two orders - `...-<ts>-<compiler>-...` and `...-<compiler>-<ts>-...`,
     * a leftover from PIE's own development - and its unit test spells the first one out as
     * `php_foo-1.2.3-8.4-ts-vc14-x86_64.zip`. This is that one, and the version carries no `v`.
     * PIE's extension-maintainers guide describes a different, longer convention under
     * "pre-packaged-binary"; that one is not what the Windows path uses, so do not "fix" this
     * against the prose. Verified against php/pie at 2026-09-10.
     */
    public function testTheReleaseWorkflowNamesTheWindowsAssetTheWayPieLooksForIt(): void
    {
        $release = (string) file_get_contents(self::ROOT . '/.github/workflows/release.yml');
        $build = (string) file_get_contents(self::ROOT . '/.github/workflows/windows-build.yml');
        if (preg_match('/^\s*asset-stem:\s*(\S.*)$/m', $release, $m) !== 1) {
            self::fail('release.yml no longer passes an asset-stem to windows-build.yml');
        }
        $extension = self::phpExt()['extension-name'];
        self::assertIsString($extension);
        self::assertSame('php_' . $extension . '-${{ needs.plan.outputs.relver }}', trim($m[1]));

        // windows-build.yml adds PHP version, thread model and compiler: the one the PHP minor is
        // built with (vs17 for 8.4/8.5, vs18 from 8.6), from its PHP_SDK_VS table.
        self::assertStringContainsString(
            '"$stem-${{ inputs.php }}-${{ inputs.ts }}-$env:PHP_SDK_VS-x86_64.dll"',
            $build,
            'the Windows asset name is no longer what PIE looks for',
        );
        if (preg_match('/^\s*PHP_SDK_VS:\s*(\S.*)$/m', $build, $table) !== 1) {
            self::fail('windows-build.yml no longer sets PHP_SDK_VS');
        }
        preg_match_all('/"\d+\.\d+":"([^"]*)"/', $table[1], $compilers);
        self::assertNotEmpty($compilers[1], 'PHP_SDK_VS is no longer a PHP version -> compiler table');
        foreach ($compilers[1] as $compiler) {
            self::assertMatchesRegularExpression('/^vs\d+$/', $compiler, 'PIE compares lower-cased names');
        }

        // ...and the zip around it, which is the asset PIE actually downloads.
        self::assertStringContainsString('zip -qj "${dll%.dll}.zip" "$dll"', $release);
    }

    /**
     * windows-build.yml picks the compiler (PHP_SDK_VS) and the runner image that has it from two
     * tables keyed by PHP minor. A minor only in the image table builds with an empty compiler and
     * fails late, at the devel pack download, on a run that is weekly or paths-filtered; one only in
     * the compiler table has no runner. Both tables cover the same minors, every one the Windows
     * matrices build.
     */
    public function testTheWindowsCompilerAndRunnerTablesCoverEveryBuiltPhp(): void
    {
        $build = (string) file_get_contents(self::ROOT . '/.github/workflows/windows-build.yml');
        $keys = static function (string $pattern) use ($build): array {
            self::assertSame(1, preg_match($pattern, $build, $line), "windows-build.yml: no $pattern");
            preg_match_all('/"(\d+\.\d+)":"/', $line[1], $m);
            $minors = $m[1];
            sort($minors);

            return $minors;
        };
        $compilers = $keys('/^\s*PHP_SDK_VS:\s*(\S.*)$/m');
        self::assertSame($compilers, $keys('/^\s*runs-on:\s*(\S.*)$/m'), 'the two tables cover other minors');

        foreach (['windows.yml', 'release.yml'] as $workflow) {
            $text = (string) file_get_contents(self::ROOT . "/.github/workflows/$workflow");
            preg_match_all('/php: \\[([^\\]]*)\\]/', $text, $matrices);
            foreach ($matrices[1] as $matrix) {
                preg_match_all("/'(\\d+\\.\\d+)'/", $matrix, $built);
                foreach ($built[1] as $minor) {
                    self::assertContains($minor, $compilers, "$workflow builds PHP $minor, which the tables lack");
                }
            }
        }
    }

    /** Both thread models are published, or a Windows ZTS `pie install` finds nothing. */
    public function testTheWindowsMatrixCoversBothThreadModels(): void
    {
        $release = (string) file_get_contents(self::ROOT . '/.github/workflows/release.yml');
        self::assertMatchesRegularExpression('/ts:\s*\[nts,\s*ts\]/', $release);
    }

    /**
     * PIE resolves versions from Packagist, and Packagist lists only the tags Composer can parse -
     * valid SemVer is not enough. Dev builds used to be tagged `v0.2.0-dev.7`, which Composer
     * rejects ("Invalid version string": it takes `-dev` only as a bare suffix), so none of them
     * ever reached Packagist and `pie install` could not see a single one. The workflow's own tag
     * expression is replayed here against Composer's parser (docs/RELEASING.md "Dev builds").
     */
    public function testDevBuildTagsAreVersionsComposerCanResolve(): void
    {
        $release = (string) file_get_contents(self::ROOT . '/.github/workflows/release.yml');
        if (preg_match('/^\s*kind=dev; relver="([^"]+)"; tag="v\$relver"/m', $release, $m) !== 1) {
            self::fail('release.yml no longer names a dev build in the form this test replays');
        }

        $expression = $m[1];
        $tag = static fn(int $run): string => 'v' . strtr($expression, [
            '${version%-dev}' => '0.4.0',
            '$version' => '0.4.0-dev',
            '${{ github.run_number }}' => (string) $run,
        ]);
        $parser = new VersionParser();

        // normalize() throws on anything Composer (and so Packagist) would not list.
        $run7 = $parser->normalize($tag(7));
        $run11 = $parser->normalize($tag(11));
        self::assertSame('RC', VersionParser::parseStability($tag(7)), 'a dev build is tagged -rc.<run>');
        self::assertTrue(Comparator::lessThan($run7, $run11), 'run numbers must order numerically');
        self::assertTrue(
            Comparator::lessThan($run11, $parser->normalize('v0.4.0')),
            'a dev build must sort below the release it leads to',
        );
        self::assertTrue(
            Comparator::greaterThan($run7, $parser->normalize('v0.3.0')),
            'a dev build must sort above the previous release',
        );
    }

    /** The stubs are their own package; the two names must not collide on Packagist. */
    public function testTheStubsAreASeparatePackage(): void
    {
        $stubs = self::json('stubs/composer.json');
        self::assertSame('php-gtk4/stubs', $stubs['name'] ?? null);
        self::assertNotSame(self::json('composer.json')['name'] ?? null, $stubs['name']);
    }
}
