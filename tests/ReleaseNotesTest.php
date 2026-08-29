<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use PHPUnit\Framework\TestCase;

/**
 * bin/release-notes turns the commit messages into the "Changes since <tag>" section
 * that .github/workflows/release.yml appends to every release body - which is the
 * reason Conventional Commits are mandatory here (see {@see CommitLintTest}).
 *
 * The grouping is asserted on a throwaway repository rather than on this one, so the
 * expectations do not move with the project's own history.
 */
final class ReleaseNotesTest extends TestCase
{
    private const string SCRIPT = __DIR__ . '/../bin/release-notes';

    private string $dir = '';

    protected function tearDown(): void
    {
        if ($this->dir !== '') {
            exec('rm -rf ' . escapeshellarg($this->dir));
            $this->dir = '';
        }
    }

    /** @param list<string> $commits `subject` or `subject|body` */
    private function repository(array $commits): void
    {
        $dir = (string) tempnam(sys_get_temp_dir(), 'phpgtk-notes-');
        unlink($dir);
        mkdir($dir);
        $this->dir = $dir;
        $this->git('init -q -b main');
        $this->git('config user.email test@example.com');
        $this->git('config user.name test');
        foreach ($commits as $commit) {
            $parts = explode('|', $commit, 2);
            $this->git('commit -q --allow-empty -m ' . escapeshellarg($parts[0])
                . (isset($parts[1]) ? ' -m ' . escapeshellarg($parts[1]) : ''));
        }
    }

    private function git(string $args): void
    {
        exec(sprintf('cd %s && git %s 2>&1', escapeshellarg($this->dir), $args), $out, $code);
        self::assertSame(0, $code, "git $args: " . implode("\n", $out));
    }

    private function notes(string ...$args): string
    {
        $cmd = sprintf(
            'cd %s && %s %s 2>&1',
            escapeshellarg($this->dir),
            escapeshellarg(self::SCRIPT),
            implode(' ', array_map('escapeshellarg', $args)),
        );
        exec($cmd, $out, $code);
        $text = implode("\n", $out);
        self::assertSame(0, $code, $text);
        return $text;
    }

    public function testCommitsAreGroupedByTypeInSectionOrder(): void
    {
        $this->repository([
            'chore: root',
            'feat(css): a scoped feature',
            'fix: a fix',
            'perf: faster wrap',
            'docs: rewrite the guide',
            'test: one more case',
            'ci: pin the runner',
            'build(deps): bump phpunit',
            'style: reformat',
        ]);

        $notes = $this->notes();
        foreach (
            ['**Features**', '**Fixes**', '**Performance**', '**Documentation**',
                '**Tests**', '**Build & CI**', '**Chores**'] as $section
        ) {
            self::assertStringContainsString($section, $notes);
        }
        // Order matters: what changed for a user comes before the housekeeping.
        self::assertLessThan(strpos($notes, '**Fixes**'), strpos($notes, '**Features**'));
        self::assertLessThan(strpos($notes, '**Chores**'), strpos($notes, '**Tests**'));
        // build and ci share a section; the scope becomes the entry's prefix.
        self::assertStringContainsString('- **deps**: bump phpunit', $notes);
        self::assertStringContainsString('- pin the runner', $notes);
        self::assertStringContainsString('- **css**: a scoped feature', $notes);
        self::assertStringContainsString('_9 commit(s)._', $notes);
    }

    public function testBreakingChangesComeFirstAndOnlyOnce(): void
    {
        $this->repository([
            'chore: root',
            'refactor(core)!: wrap() returns the nearest registered class',
            'feat: with a footer|body text' . "\n\n" . 'BREAKING CHANGE: the handle is gone',
            'feat: an ordinary feature',
        ]);

        $notes = $this->notes();
        self::assertStringContainsString('**⚠ Breaking changes**', $notes);
        self::assertLessThan(strpos($notes, '**Features**'), strpos($notes, 'Breaking changes'));
        self::assertStringContainsString('- **core**: wrap() returns the nearest registered class', $notes);
        // A breaking commit is listed in the breaking section instead of its own type,
        // never in both.
        self::assertSame(1, substr_count($notes, 'with a footer'));
        self::assertSame(1, substr_count($notes, 'wrap() returns'));
        self::assertStringNotContainsString('**Refactoring**', $notes);
    }

    /** Nothing is dropped: history from before the rule still shows up. */
    public function testUnconventionalSubjectsLandUnderOther(): void
    {
        $this->repository(['chore: root', 'review: close the leftovers', 'feat: a feature']);
        $notes = $this->notes();
        self::assertStringContainsString('**Other**', $notes);
        self::assertStringContainsString('- review: close the leftovers', $notes);
    }

    public function testSinceATagAndTheStableTagFilter(): void
    {
        $this->repository(['chore: root', 'feat: before the release']);
        $this->git('tag v0.1.0');
        $this->repository_add('fix: after the release');
        $this->git('tag v0.2.0-dev.3');
        $this->repository_add('fix: after the dev tag');

        // Default: the newest tag of any kind, which for a dev build is the last dev tag.
        $latest = $this->notes();
        self::assertStringContainsString('### Changes since `v0.2.0-dev.3`', $latest);
        self::assertStringContainsString('- after the dev tag', $latest);
        self::assertStringNotContainsString('before the release', $latest);

        // --stable: a real release measures itself from the previous real release.
        $stable = $this->notes('--stable');
        self::assertStringContainsString('### Changes since `v0.1.0`', $stable);
        self::assertStringContainsString('- after the release', $stable);
        self::assertStringContainsString('- after the dev tag', $stable);
        self::assertStringNotContainsString('before the release', $stable);

        // An explicit tag wins over both.
        self::assertStringContainsString('### Changes since `v0.1.0`', $this->notes('v0.1.0'));
    }

    public function testNoCommitsSinceTheTagSaysSo(): void
    {
        $this->repository(['chore: root', 'feat: shipped']);
        $this->git('tag v0.1.0');
        self::assertStringContainsString('_No commits', $this->notes('v0.1.0'));
    }

    /** In Actions the hashes link to the commit; locally they are plain. */
    public function testCommitsLinkToGitHubWhenTheEnvironmentSaysSo(): void
    {
        $this->repository(['chore: root', 'feat: linked']);
        $env = 'GITHUB_SERVER_URL=https://github.com GITHUB_REPOSITORY=owner/repo';
        exec(sprintf(
            'cd %s && %s %s 2>&1',
            escapeshellarg($this->dir),
            $env,
            escapeshellarg(self::SCRIPT),
        ), $out, $code);
        self::assertSame(0, $code, implode("\n", $out));
        self::assertStringContainsString('https://github.com/owner/repo/commit/', implode("\n", $out));
    }

    private function repository_add(string $subject): void
    {
        $this->git('commit -q --allow-empty -m ' . escapeshellarg($subject));
    }
}
