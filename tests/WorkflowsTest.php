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

        // Every published php_gtk4.dll is linked against that archive, and a GitHub release
        // asset can be replaced by its owner - so the pin is by content, not by version, and
        // the workflow has to check it on the download *and* on a restored cache.
        self::assertSame(
            1,
            preg_match("/^\s*GVSBUILD_SHA256: '([0-9a-f]{64})'\s*$/m", $shared),
            'windows-build.yml: no GVSBUILD_SHA256 pin (64 hex characters)',
        );
        self::assertStringContainsString('Get-FileHash $zip -Algorithm SHA256', $shared);
        self::assertStringContainsString('hash mismatch', $shared, 'the download is not verified');
        self::assertStringContainsString('.gvsbuild-sha256', $shared, 'the cached tree is not stamped');
        self::assertStringContainsString(
            'refusing it',
            $shared,
            'a restored cache without the stamp must be refused',
        );

        foreach (['windows.yml', 'release.yml'] as $file) {
            $yml = (string) file_get_contents(self::WORKFLOWS . '/' . $file);
            self::assertDoesNotMatchRegularExpression(
                '/^\\s*GVSBUILD_(VERSION|SHA256):/m',
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

    /**
     * setup-php installs the *production* php.ini, which has `register_argc_argv = Off`. PHPStan
     * then reports "Variable $argv might not be defined" in every CLI entry script (gen/gir.php,
     * gen/ide-stub.php, gen/map-status.php, gen/method-comments.php, examples/demo.php) and
     * php-qa fails on the runner while passing locally - which is exactly what kept the first
     * release of the public repository from being published. Every workflow that runs the PHP
     * tooling says so explicitly.
     */
    public function testTheWorkflowsThatRunPhpToolingKeepArgvDefined(): void
    {
        foreach (['php-qa.yml', 'cpp-lint.yml', 'release.yml'] as $file) {
            $yml = (string) file_get_contents(self::WORKFLOWS . '/' . $file);
            self::assertStringContainsString(
                'ini-values: register_argc_argv=On',
                $yml,
                "$file runs PHPStan or the generator over PHP that uses \$argv; without"
                . ' register_argc_argv the analysis fails on the runner only',
            );
        }
    }

    /**
     * A required status check has to come from a workflow that always starts. A `paths:` filter
     * on one means it does not run at all for a change outside those paths - and a check that
     * never runs never reports, so the pull request waits for it forever.
     */
    public function testNoRequiredCheckComesFromAPathFilteredWorkflow(): void
    {
        $required = $this->requiredContexts();
        foreach (glob(self::WORKFLOWS . '/*.yml') ?: [] as $file) {
            $yml = (string) file_get_contents($file);
            // The `on:` block only - a `paths:` under a job step is something else entirely.
            $on = (string) preg_replace('/^(jobs|permissions|concurrency|env):.*/ms', '', $yml);
            if (!str_contains($on, 'paths:')) {
                continue;
            }
            foreach ($required as $context) {
                self::assertStringNotContainsString(
                    "name: $context",
                    $yml,
                    basename($file) . " filters on paths but reports the required check '$context'",
                );
            }
        }
    }

    /**
     * The same commit used to be validated as the pull request head and again on push to main,
     * where release.yml's own gate validates it a third time. Only the workflow whose cache the
     * pull requests read still runs on main (docs/RELEASING.md "Gating").
     */
    public function testOnlyTheLintRunsAgainOnMain(): void
    {
        $onMain = [];
        foreach (glob(self::WORKFLOWS . '/*.yml') ?: [] as $file) {
            $yml = (string) file_get_contents($file);
            $on = (string) preg_replace('/^jobs:.*/ms', '', $yml);
            if (preg_match('/push:\s*\n\s*branches:\s*\[main\]/', $on) === 1) {
                $onMain[] = basename($file);
            }
        }
        sort($onMain);
        self::assertSame(['cpp-lint.yml', 'release.yml'], $onMain);
    }

    /**
     * `PHP 8.4` / `PHP 8.5` are required status checks and they are the NTS cells, so whatever
     * the `changes` job decides, NTS has to be in it - a matrix that leaves a required cell out
     * is the same dead end as a workflow that never starts, just harder to see.
     */
    public function testTheRequiredThreadModelIsNeverConditional(): void
    {
        $yml = (string) file_get_contents(self::WORKFLOWS . '/tests.yml');
        self::assertSame(
            1,
            preg_match_all('/thread-models=(\[[^\]]*\])/', $yml, $m, PREG_PATTERN_ORDER) > 0 ? 1 : 0,
            'tests.yml no longer decides its thread models where this test can read them',
        );
        foreach ($m[1] as $value) {
            $models = json_decode($value, true);
            self::assertIsArray($models, "not a JSON list: $value");
            self::assertContains('nts', $models, "the required NTS cells would not run: $value");
        }
    }

    /**
     * A dev build is validated like a pull request; a real release is also run against ZTS, the
     * sanitizers and valgrind, and its Windows binaries run the suite. docs/RELEASING.md
     * "Cutting a release" asks for the first two by hand - this is what makes forgetting harmless.
     */
    public function testARealReleaseIsGatedOnMoreThanADevBuild(): void
    {
        $yml = (string) file_get_contents(self::WORKFLOWS . '/release.yml');

        self::assertMatchesRegularExpression(
            "/ts: \\\$\{\{ needs\.plan\.outputs\.kind == 'release'/",
            $yml,
            'the verify matrix no longer adds ZTS for a real release',
        );
        self::assertMatchesRegularExpression(
            "/release-gate:.*?if: needs\.plan\.outputs\.kind == 'release'/s",
            $yml,
            'nothing runs the sanitizers and valgrind before a release',
        );
        self::assertStringContainsString('run-tests: true', $yml, 'the shipped dll never runs the suite');
        self::assertMatchesRegularExpression(
            '/needs\.coverage\.result == .success./',
            $yml,
            'publish no longer waits for the coverage floor',
        );
    }

    /** Windows minutes bill at nearly twice Linux's, so the four-job matrix is not on every push. */
    public function testWindowsIsNotBuiltForEveryDevBuild(): void
    {
        $release = (string) file_get_contents(self::WORKFLOWS . '/release.yml');
        self::assertMatchesRegularExpression(
            "/build-windows:.*?if: needs\.plan\.outputs\.kind == 'release'/s",
            $release,
            'release.yml builds the Windows assets for every dev build again',
        );

        $windows = (string) file_get_contents(self::WORKFLOWS . '/windows.yml');
        $on = (string) preg_replace('/^jobs:.*/ms', '', $windows);
        self::assertStringContainsString('paths:', $on, 'windows.yml runs for changes that cannot break it');
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
