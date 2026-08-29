<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use PHPUnit\Framework\TestCase;

/**
 * What the workflows must keep agreeing on with the rest of the repository.
 *
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

    /**
     * Conventional Commits are mandatory (docs/CONTRIBUTING.md), and main accepts squash
     * *and* rebase merges - so the gate has to look at both the PR title (which becomes
     * the squashed commit) and every commit on the branch (which a rebase keeps).
     */
    public function testCommitMessagesAreGatedOnTheTitleAndOnEveryCommit(): void
    {
        $yml = (string) file_get_contents(self::WORKFLOWS . '/php-qa.yml');
        self::assertStringContainsString('bin/commit-lint --text "$TITLE"', $yml, 'the PR title is unchecked');
        self::assertStringContainsString('./ci.sh --only=commits', $yml, 'the branch commits are unchecked');
        self::assertStringContainsString('COMMIT_LINT_RANGE', $yml);
    }

    /** The commit messages are the release notes; the release body must actually use them. */
    public function testReleaseNotesAreBuiltFromTheCommitMessages(): void
    {
        $yml = (string) file_get_contents(self::WORKFLOWS . '/release.yml');
        self::assertStringContainsString('./bin/release-notes', $yml);
        self::assertStringContainsString('./bin/release-notes --stable', $yml, 'a real release skips the dev tags');
    }

    /**
     * A required status check is matched by name: rename a job and the check it stands
     * for is never reported again, which leaves every PR waiting for something that can
     * no longer arrive. So every context the ruleset requires has to be a name some job
     * really reports - a literal `name:`, or one of the names the tests matrix expands to.
     */
    public function testEveryRequiredStatusCheckMatchesARealJobName(): void
    {
        $reported = $this->literalJobNames();
        foreach ($this->matrixJobNames() as $name) {
            $reported[] = $name;
        }

        foreach ($this->requiredContexts() as $context) {
            self::assertContains(
                $context,
                $reported,
                "ruleset-main.json requires '$context', which no workflow job reports"
                . ' (a renamed job blocks every PR on a check that never runs)',
            );
        }
    }

    /** @return list<string> job names without a `${{ }}` expression in them */
    private function literalJobNames(): array
    {
        $names = [];
        foreach (glob(self::WORKFLOWS . '/*.yml') ?: [] as $file) {
            preg_match_all("/^    name: '?(.+?)'?$/m", (string) file_get_contents($file), $m);
            foreach ($m[1] as $name) {
                if (!str_contains($name, '${{')) {
                    $names[] = $name;
                }
            }
        }
        return $names;
    }

    /**
     * The one templated name a required check may stand for: tests.yml runs PHP x
     * threading, and GitHub reports one check per cell. The PHP versions come from the
     * matrix itself, so bumping it here is enough; the release and Windows jobs are never
     * required checks (they are not part of a PR) and are deliberately not expanded.
     *
     * @return list<string>
     */
    private function matrixJobNames(): array
    {
        $yml = (string) file_get_contents(self::WORKFLOWS . '/tests.yml');
        $template = "PHP \${{ matrix.php }}\${{ matrix.ts == 'ts' && ' ZTS' || '' }}";
        self::assertStringContainsString(
            "name: $template",
            $yml,
            'tests.yml job name changed shape - update this expansion and ruleset-main.json with it',
        );
        if (preg_match("/^        php: \[(.+)\]$/m", $yml, $m) !== 1) {
            self::fail('tests.yml: no php matrix to expand the job name with');
        }

        $names = [];
        foreach (explode(',', $m[1]) as $version) {
            $version = trim($version, " '");
            $names[] = "PHP $version";
            $names[] = "PHP $version ZTS";
        }
        return $names;
    }

    /** @return list<string> every status check ruleset-main.json makes required */
    private function requiredContexts(): array
    {
        $ruleset = json_decode(
            (string) file_get_contents(__DIR__ . '/../.github/ruleset-main.json'),
            true,
            flags: JSON_THROW_ON_ERROR,
        );
        self::assertIsArray($ruleset);

        $contexts = [];
        /** @var list<array{type: string, parameters?: array{required_status_checks?: list<array{context: string}>}}> $rules */
        $rules = $ruleset['rules'] ?? [];
        foreach ($rules as $rule) {
            foreach ($rule['parameters']['required_status_checks'] ?? [] as $check) {
                $contexts[] = $check['context'];
            }
        }
        self::assertNotSame([], $contexts, 'the ruleset requires no status checks at all');
        return $contexts;
    }
}
