<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * The issue forms in .github/ISSUE_TEMPLATE exist to make a report reproducible: which PHP,
 * which GTK, which display server, and a script that shows the problem. GitHub only
 * enforces that if the fields are marked required and blank issues stay disabled - both
 * are one edit away from being lost, and nobody notices until the reports get useless.
 */
final class IssueTemplateTest extends TestCase
{
    private const string DIR = __DIR__ . '/../.github/ISSUE_TEMPLATE';

    /** @return iterable<string, array{string}> */
    public static function forms(): iterable
    {
        foreach (['bug_report.yml', 'feature_request.yml'] as $file) {
            yield $file => [$file];
        }
    }

    #[DataProvider('forms')]
    public function testFormHasNameDescriptionAndLabels(string $file): void
    {
        $yml = $this->read($file);
        foreach (['name:', 'description:', 'labels:', 'body:'] as $key) {
            self::assertStringContainsString("\n$key", "\n$yml", "$file: no $key");
        }
    }

    /**
     * The context that cannot be guessed afterwards. Names match the field ids in the form;
     * dropping one means the next reporter does not have to say which GTK or which session
     * they were on, and the issue turns into a conversation instead of a reproduction.
     */
    public function testBugReportRequiresTheContextThatMakesAReportUsable(): void
    {
        $yml = $this->read('bug_report.yml');
        foreach (['versions', 'install', 'os', 'session', 'reproduction', 'expected', 'output'] as $id) {
            self::assertMatchesRegularExpression(
                "/id: $id\\n.*?validations:\\n\\s+required: true/s",
                $yml,
                "bug_report.yml: the '$id' field is gone or no longer required",
            );
        }
        // php-gtk3 is a different project and web SAPIs are not a target; both come up often
        // enough that the form asks before the report is filed.
        self::assertStringContainsString('php-gtk3', $yml);
        self::assertStringContainsString('CLI-only', $yml);
    }

    public function testFeatureRequestAsksWhatForNotJustWhat(): void
    {
        $yml = $this->read('feature_request.yml');
        foreach (['symbol', 'usecase', 'docs'] as $id) {
            self::assertMatchesRegularExpression(
                "/id: $id\\n.*?validations:\\n\\s+required: true/s",
                $yml,
                "feature_request.yml: the '$id' field is gone or no longer required",
            );
        }
    }

    /** A blank issue is a bug report with none of the above; the forms are the only door. */
    public function testBlankIssuesStayDisabled(): void
    {
        self::assertMatchesRegularExpression(
            '/^blank_issues_enabled:\s*false$/m',
            $this->read('config.yml'),
        );
    }

    /**
     * Links in an issue form are rendered on the issue page, where a repository-relative
     * path resolves to the wrong place - they have to be absolute.
     */
    #[DataProvider('forms')]
    public function testLinksAreAbsolute(string $file): void
    {
        $yml = $this->read($file);
        preg_match_all('/\]\(([^)]+)\)/', $yml, $m);
        foreach ($m[1] as $url) {
            self::assertStringStartsWith('http', $url, "$file: '$url' must be an absolute URL");
        }
    }

    private function read(string $file): string
    {
        $path = self::DIR . '/' . $file;
        self::assertFileExists($path);
        return (string) file_get_contents($path);
    }
}
