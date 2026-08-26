<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

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
        foreach (['CLAUDE.md', 'README.md', 'docs/PLAN.md', 'docs/TODO.md', 'gen/README.md', 'stubs/README.md'] as $f) {
            yield $f => [$f];
        }
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('docs')]
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
            if (!file_exists(self::ROOT . '/' . $path)) {
                $missing[] = $path;
            }
        }
        self::assertSame([], $missing, "$doc mentions paths that do not exist");
    }
}
