<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Guards the project documentation against silent rot (CLAUDE.md was once
 * truncated by a bad edit for hours): required sections exist and every
 * repository path mentioned in the docs actually exists.
 */
final class DocsTest extends TestCase
{
    private const ROOT = __DIR__ . '/..';

    public function testClaudeMdHasTheRequiredSections(): void
    {
        $md = (string) file_get_contents(self::ROOT . '/CLAUDE.md');
        $sections = [
            '## What this is', '## Build', '## Lint, QA, build, test', '## PHP QA',
            '## Definition of done', '## Tests', '## CI', '## Architecture',
        ];
        foreach ($sections as $h) {
            self::assertStringContainsString($h, $md, "CLAUDE.md lost its '$h' section");
        }
        self::assertGreaterThan(120, substr_count($md, "\n"), 'CLAUDE.md looks truncated');
    }

    /** @return iterable<string, array{string}> */
    public static function docs(): iterable
    {
        // CHANGELOG.md is deliberately absent: it records history, so it names files that were
        // renamed or deleted on purpose (`tests/TestPng.php` -> `tests/PngFixture.php`).
        $docs = ['CLAUDE.md', 'README.md', 'THIRD-PARTY-NOTICES.md', 'SECURITY.md', 'docs/TODO.md',
            'docs/RELEASING.md', 'docs/BUILD.md', 'docs/GTK3-MAP.md', 'docs/PORTING.md',
            'docs/INSTALL.md', 'docs/CONTRIBUTING.md', 'gen/README.md',
            'examples/README.md', 'stubs/README.md', 'tests/phpt/README.md'];
        foreach ($docs as $f) {
            yield $f => [$f];
        }
    }

    #[DataProvider('docs')]
    public function testMentionedRepositoryPathsExist(string $doc): void
    {
        $md = (string) file_get_contents(self::ROOT . '/' . $doc);
        preg_match_all('#`((?:src|tests|stubs|gen|docs|bin|examples|\.github)/[\w./-]+(?:\.\w+|/))`#', $md, $m);
        $missing = [];
        foreach (array_unique($m[1]) as $path) {
            $path = rtrim($path, '.');
            if (str_contains($path, '*') || str_contains($path, '<')) {
                continue;  // globs / placeholders
            }
            if (self::isGitIgnored($path)) {
                continue;  // generated on demand, absent in a fresh checkout
            }
            if (!file_exists(self::ROOT . '/' . $path)) {
                $missing[] = $path;
            }
        }
        self::assertSame([], $missing, "$doc mentions paths that do not exist");
    }

    /**
     * The index in `tests/phpt/README.md` is the only record of why each process-level test
     * cannot live in PHPUnit, so it names every one of them and nothing else. Three tests had
     * been added without a row before this assertion existed.
     */
    public function testPhptReadmeListsEveryTest(): void
    {
        $md = (string) file_get_contents(self::ROOT . '/tests/phpt/README.md');
        $table = substr($md, (int) strpos($md, 'What is here'));

        $paths = glob(self::ROOT . '/tests/phpt/*.phpt');
        self::assertNotFalse($paths, 'tests/phpt is unreadable');
        $files = array_map(static fn(string $p): string => basename($p, '.phpt'), $paths);
        sort($files);

        // Test names are the only all-lowercase backticked words in the table; anything else it
        // quotes carries a dot, an equals sign, a dash pair or a capital (`--INI--`, `E_ERROR`).
        preg_match_all('/`([a-z0-9][a-z0-9-]*)`/', $table, $m);
        $listed = array_values(array_unique($m[1]));
        sort($listed);

        self::assertSame($files, $listed, 'tests/phpt/README.md must have a row for every .phpt and no other');
    }

    /**
     * The generated docblocks are GTK's, GLib's, Pango's and WebKitGTK's own documentation, lifted
     * out of their GIR data (gen/gir/loader.php), so every namespace the generator writes a stub for
     * has to be named in the notices - adding one and forgetting the row is how an attribution goes
     * missing. The namespace directory name is what the table lists.
     */
    public function testThirdPartyNoticesNameEveryGeneratedNamespace(): void
    {
        $notices = (string) file_get_contents(self::ROOT . '/THIRD-PARTY-NOTICES.md');
        $missing = [];
        foreach (glob(self::ROOT . '/src/*/*.stub.php') ?: [] as $stub) {
            $ns = basename(dirname($stub));
            if (!str_contains($notices, "`$ns`")) {
                $missing[] = $ns;
            }
        }
        self::assertSame([], $missing, 'THIRD-PARTY-NOTICES.md does not name every generated namespace');
    }

    /**
     * gen/gen_stub.php is redistributed, so the PHP License 3.01 travels with it in full (clause 1)
     * and its acknowledgment is retained (clause 6) - verbatim, because that clause quotes it.
     */
    public function testThePhpLicenceIsShippedWhole(): void
    {
        $licence = (string) file_get_contents(self::ROOT . '/LICENSES/PHP-3.01.txt');
        self::assertStringContainsString('The PHP License, version 3.01', $licence);
        self::assertSame(6, preg_match_all('/^ *[1-6]\. /m', $licence), 'a clause went missing');
        self::assertStringContainsString('may not be called "PHP"', $licence);

        $acknowledgment = 'This product includes PHP software, freely available from '
            . '<http://www.php.net/software/>';
        foreach (['THIRD-PARTY-NOTICES.md', 'README.md'] as $doc) {
            self::assertStringContainsString(
                $acknowledgment,
                (string) file_get_contents(self::ROOT . '/' . $doc),
                "$doc must carry the PHP License clause 6 acknowledgment verbatim",
            );
        }
    }

    /**
     * stubs/gtk4.php is mostly GTK's documentation, and it ships on its own as php-gtk4/stubs, so the
     * notices and the licence text have to be copied into that package too. Nothing else fails when
     * the publish step forgets a file (docs/RELEASING.md "The stubs package").
     */
    public function testTheStubsPackageShipsTheThirdPartyNotices(): void
    {
        $release = (string) file_get_contents(self::ROOT . '/.github/workflows/release.yml');
        $publish = substr($release, (int) strpos($release, 'publish-stubs'));
        foreach (['../LICENSE', '../THIRD-PARTY-NOTICES.md', '../LICENSES/PHP-3.01.txt'] as $file) {
            self::assertStringContainsString(
                "cp $file",
                $publish,
                "release.yml's publish-stubs job must copy $file into the stubs package",
            );
        }
        self::assertStringContainsString(
            'THIRD-PARTY-NOTICES.md',
            (string) file_get_contents(self::ROOT . '/stubs/README.md'),
            'stubs/README.md must point at the notices that ship beside it',
        );
    }

    /**
     * Whether git ignores $path: paths the build downloads or generates on demand
     * (gen/PHP-Parser-*, gtk4.so, ...) exist locally but never in a fresh CI
     * checkout, so documenting them is right and asserting they exist is not.
     */
    private static function isGitIgnored(string $path): bool
    {
        /** @var array<string, bool> $cache */
        static $cache = [];

        if (!isset($cache[$path])) {
            $null = PHP_OS_FAMILY === 'Windows' ? 'NUL' : '/dev/null';
            $cmd = 'git -C ' . escapeshellarg(self::ROOT)
                . ' check-ignore -q ' . escapeshellarg($path) . ' 2>' . $null;
            exec($cmd, $ignoredOutput, $status);
            $cache[$path] = $status === 0;
        }

        return $cache[$path];
    }
}
