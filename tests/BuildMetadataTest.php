<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use PHPUnit\Framework\TestCase;

/**
 * The commit hash in Gtk4\BUILD_INFO has two sources: the checkout's git, and - for a source
 * archive, which has no .git (the release tarball, the GitHub zipball PIE builds from) -
 * ./.git-commit, where git archive's export-subst replaces the placeholder with the hash.
 * Every piece of that has to stay in place for a `pie install` to identify its commit.
 */
final class BuildMetadataTest extends TestCase
{
    private const ROOT = __DIR__ . '/..';

    public function testTheCheckoutCarriesThePlaceholderNotAHash(): void
    {
        // A committed hash would be stale the moment it lands; only git archive fills it in.
        self::assertSame("\$Format:%h\$\n", file_get_contents(self::ROOT . '/.git-commit'));
    }

    public function testGitAttributesMarkTheFileForSubstitution(): void
    {
        $attrs = (string) file_get_contents(self::ROOT . '/.gitattributes');
        self::assertMatchesRegularExpression('/^\.git-commit\s+export-subst$/m', $attrs);
    }

    public function testBothConfigureScriptsReadTheFile(): void
    {
        foreach (['config.m4', 'config.w32'] as $script) {
            $text = (string) file_get_contents(self::ROOT . "/$script");
            self::assertStringContainsString('.git-commit', $text, $script);
        }
    }

    public function testAnArchiveOfHeadCarriesTheHash(): void
    {
        $root = escapeshellarg(self::ROOT);
        exec("git -C $root rev-parse --short HEAD 2>/dev/null", $head, $rc);
        if ($rc !== 0) {
            self::markTestSkipped('not a git checkout');
        }
        exec("git -C $root archive --format=tar HEAD -- .git-commit 2>/dev/null | tar -xO", $out, $rc);
        self::assertSame(0, $rc, 'git archive | tar failed');
        self::assertSame(trim($head[0]), trim(implode("\n", $out)), 'export-subst did not fill in HEAD');
    }
}
