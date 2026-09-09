<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * The namespaces that compile only with a configure feature (`CONDITIONAL_NAMESPACES` in
 * gen/gir/config.php - WebKit and JavaScriptCore behind `--enable-gtk4-webkit`) are named in
 * four other places that cannot read that table: the source globs of config.m4 and config.w32,
 * the clang-tidy file list in ci.sh, and {@see Features}, which tells the suite which classes
 * a build may lack. This pins them to each other, so adding a namespace to the table without
 * the rest is a failing test rather than a build that compiles a WebKit file without WebKit.
 */
final class FeatureGateTest extends TestCase
{
    private const ROOT = __DIR__ . '/..';

    /** @return array<string, array{feature: string, macro: string, include: string}> */
    private static function conditional(): array
    {
        require_once self::ROOT . '/gen/gir/config.php';
        /** @var array<string, array{feature: string, macro: string, include: string}> $table */
        $table = \PhpGtk4\Gen\CONDITIONAL_NAMESPACES;
        self::assertNotEmpty($table);
        return $table;
    }

    /** @return iterable<string, array{string, string}> */
    public static function namespaces(): iterable
    {
        foreach (self::conditional() as $ns => $gate) {
            yield $ns => [$ns, $gate['feature']];
        }
    }

    #[DataProvider('namespaces')]
    public function testTheBuildFilesLeaveTheNamespaceOutWithoutItsFeature(string $ns, string $feature): void
    {
        foreach (['config.m4', 'config.w32', 'ci.sh'] as $file) {
            $src = (string) file_get_contents(self::ROOT . '/' . $file);
            self::assertMatchesRegularExpression(
                '/\b' . preg_quote($ns, '/') . '\b/',
                $src,
                "$file must name src/$ns, the namespace gen/gir/config.php gates on $feature",
            );
        }
        // the feature has to be one the module reports on
        self::assertMatchesRegularExpression('/\b' . preg_quote($feature, '/') . '=(yes|no)\b/', \Gtk4\FEATURES);
    }

    /**
     * Every class the generator emitted for a gated namespace is one {@see Features} attributes
     * to that feature - the sweeps and the stub comparison rely on the prefix table there.
     */
    #[DataProvider('namespaces')]
    public function testEveryClassOfTheNamespaceIsGatedByFeatures(string $ns, string $feature): void
    {
        $stub = (string) file_get_contents(self::ROOT . "/src/$ns/$ns.stub.php");
        preg_match_all('/^(?:final |abstract )?(?:class|interface|enum) (\w+)/m', $stub, $m);
        self::assertNotEmpty($m[1], "src/$ns/$ns.stub.php declares no class");
        foreach ($m[1] as $class) {
            self::assertSame(
                $feature,
                Features::gate($class),
                "tests/Features.php must attribute $class (src/$ns) to the $feature feature",
            );
        }
    }

    public function testAnUngatedClassNeedsNoFeature(): void
    {
        self::assertNull(Features::gate('GtkButton'));
        self::assertNull(Features::gate(\Gtk4\GtkButton::class));
        self::assertTrue(Features::available(\Gtk4\GtkButton::class));
    }

    public function testAvailabilityFollowsTheBuild(): void
    {
        $webkit = Features::enabled('webkit');
        self::assertSame($webkit, class_exists(\Gtk4\WebKitWebView::class));
        self::assertSame($webkit, Features::available('WebKitWebView'));
        self::assertSame($webkit, Features::available(\Gtk4\JSCValue::class));
        self::assertFalse(Features::enabled('no-such-feature'));
    }
}
