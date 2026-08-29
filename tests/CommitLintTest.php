<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Conventional Commits are mandatory in this repository (docs/CONTRIBUTING.md) and
 * bin/commit-lint is what enforces them - in .githooks/commit-msg, in
 * `./ci.sh --only=commits` and over the PR title in .github/workflows/php-qa.yml.
 * A rule nobody can bypass has to be right, so every accepted and rejected shape is
 * pinned here; bin/release-notes then groups the same messages into a release body.
 */
final class CommitLintTest extends TestCase
{
    private const string ROOT = __DIR__ . '/..';

    private string $repoDir = '';
    private string $previousDir = '';

    protected function tearDown(): void
    {
        if ($this->repoDir !== '') {
            chdir($this->previousDir);
            exec('rm -rf ' . escapeshellarg($this->repoDir));
            $this->repoDir = '';
        }
    }

    /** @return array{int, string} exit code and combined output */
    private static function lint(string ...$args): array
    {
        $cmd = escapeshellarg(self::ROOT . '/bin/commit-lint')
            . ' ' . implode(' ', array_map('escapeshellarg', $args)) . ' 2>&1';
        $out = [];
        exec($cmd, $out, $code);
        return [$code, implode("\n", $out)];
    }

    /** @return iterable<string, array{string}> */
    public static function acceptedSubjects(): iterable
    {
        $ok = [
            'feat: CSS',
            'fix: GdkTexture keeps a private constructor (no PHP subtypes)',
            'feat(css): GtkCssProvider and the display it attaches to',
            'refactor(core)!: wrap() returns the nearest registered class',
            'build(deps): bump phpunit/phpunit from 12.5 to 12.6',
            'chore(release): 0.2.0',
            'docs: install guide for Linux and Windows',
            'ci(windows-build): share the gvsbuild recipe',
            'test: exercise every vfunc thunk',
            'perf: skip the marshaller for scalars',
            'style: clang-format the generated files',
            'revert: the toggle-ref experiment',
            // git and GitHub write these themselves.
            'Merge branch \'main\' into feat/css',
            'Revert "feat: CSS"',
        ];
        foreach ($ok as $s) {
            yield $s => [$s];
        }
    }

    /** @return iterable<string, array{string, string}> subject and the words the error must contain */
    public static function rejectedSubjects(): iterable
    {
        $bad = [
            'fixed stuff' => 'not a Conventional Commit',
            'review: close the leftovers' => 'not a Conventional Commit',      // a type we do not use
            'Feat: capital type' => 'not a Conventional Commit',
            'feat:no space after the colon' => 'not a Conventional Commit',
            'feat' => 'not a Conventional Commit',
            'feat: ' => 'not a Conventional Commit',
            'feat(CSS): upper-case scope' => 'not a Conventional Commit',
            'feat: a subject that ends in a period.' => 'must not end in a period',
            'feat: ' . str_repeat('x', 120) => 'the limit is 100',
        ];
        foreach ($bad as $subject => $expected) {
            yield $subject => [(string) $subject, $expected];
        }
    }

    #[DataProvider('acceptedSubjects')]
    public function testAcceptedSubject(string $subject): void
    {
        [$code, $out] = self::lint('--text', $subject);
        self::assertSame(0, $code, $out);
    }

    #[DataProvider('rejectedSubjects')]
    public function testRejectedSubject(string $subject, string $expected): void
    {
        [$code, $out] = self::lint('--text', $subject);
        self::assertSame(1, $code, "should have been rejected: $subject");
        self::assertStringContainsString($expected, $out);
        self::assertStringContainsString('docs/CONTRIBUTING.md', $out, 'the error points at the rules');
    }

    public function testBodyMustBeSeparatedByABlankLine(): void
    {
        [$code, $out] = self::lint('--message', $this->messageFile("feat: a subject\nthe body\n"));
        self::assertSame(1, $code);
        self::assertStringContainsString('must be blank', $out);

        [$code] = self::lint('--message', $this->messageFile("feat: a subject\n\nthe body\n"));
        self::assertSame(0, $code);
    }

    /** git appends its own commented help to the buffer; it is not part of the message. */
    public function testCommentLinesAreIgnored(): void
    {
        $message = "feat: a subject\n\nthe body\n# Please enter the commit message...\n#\n# On branch main\n";
        [$code, $out] = self::lint('--message', $this->messageFile($message));
        self::assertSame(0, $code, $out);
    }

    /**
     * `git commit --fixup` is how you answer review comments; the hook lets those
     * through and the range check refuses them, so they cannot reach a PR unsquashed.
     */
    public function testFixupIsLocalOnly(): void
    {
        [$code] = self::lint('--message', $this->messageFile("fixup! feat: a subject\n"));
        self::assertSame(0, $code, 'the hook accepts a fixup');

        $this->repository(['feat: base', 'fixup! feat: base']);
        [$code, $out] = self::lint('--range', 'HEAD~1..HEAD');
        self::assertSame(1, $code, $out);
        self::assertStringContainsString('autosquash', $out);
    }

    public function testRangeChecksEveryCommit(): void
    {
        $this->repository(['feat: one', 'not conventional', 'fix: three']);
        [$code, $out] = self::lint('--range', 'HEAD~3..HEAD');
        self::assertSame(1, $code);
        self::assertStringContainsString('not conventional', $out);

        [$code, $out] = self::lint('--range', 'HEAD~1..HEAD');
        self::assertSame(0, $code, $out);
        self::assertStringContainsString('1 commit(s)', $out);
    }

    /**
     * Dependabot opens its own PRs, and CI checks their titles like any other. Without a
     * commit-message prefix it writes "Bump x from a to b", which the gate rejects - so
     * every ecosystem in .github/dependabot.yml has to carry one.
     */
    public function testDependabotWritesConventionalCommits(): void
    {
        $yml = (string) file_get_contents(self::ROOT . '/.github/dependabot.yml');
        $ecosystems = substr_count($yml, '- package-ecosystem:');
        self::assertGreaterThan(0, $ecosystems);
        self::assertSame(
            $ecosystems,
            substr_count($yml, 'commit-message:'),
            'every Dependabot ecosystem needs a commit-message prefix, or its PRs cannot merge',
        );
        foreach (['build', 'ci'] as $prefix) {
            [$code] = self::lint('--text', "$prefix(deps): bump something from 1.0 to 1.1");
            self::assertSame(0, $code, "the prefix '$prefix' Dependabot is configured with must pass");
        }
    }

    /** The hook is nothing but a call into the linter, so enabling hooks is enough. */
    public function testCommitMsgHookDelegatesToTheLinter(): void
    {
        $hook = self::ROOT . '/.githooks/commit-msg';
        self::assertFileExists($hook);
        self::assertTrue(is_executable($hook), '.githooks/commit-msg must be executable');
        self::assertStringContainsString('bin/commit-lint', (string) file_get_contents($hook));
    }

    private function messageFile(string $contents): string
    {
        $file = tempnam(sys_get_temp_dir(), 'phpgtk-msg-');
        self::assertIsString($file);
        file_put_contents($file, $contents);
        return $file;
    }

    /**
     * A throwaway git repository with one empty commit per subject, made the current
     * directory for the rest of the test - bin/commit-lint reads the repository it runs
     * in. tearDown() puts the directory back and deletes it.
     *
     * @param list<string> $subjects
     */
    private function repository(array $subjects): void
    {
        $dir = (string) tempnam(sys_get_temp_dir(), 'phpgtk-repo-');
        unlink($dir);
        mkdir($dir);
        $run = static function (string $cmd) use ($dir): void {
            exec(sprintf('cd %s && %s 2>&1', escapeshellarg($dir), $cmd), $out, $code);
            if ($code !== 0) {
                self::fail("$cmd failed: " . implode("\n", $out));
            }
        };
        $run('git init -q -b main');
        $run('git config user.email test@example.com && git config user.name test');
        $run('git commit -q --allow-empty -m ' . escapeshellarg('chore: root'));
        foreach ($subjects as $subject) {
            $run('git commit -q --allow-empty -m ' . escapeshellarg($subject));
        }
        $this->previousDir = (string) getcwd();
        $this->repoDir = $dir;
        chdir($dir);
    }
}
