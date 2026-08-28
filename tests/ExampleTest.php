<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionExtension;

/**
 * examples/ is the canonical showcase and it is atomic: one file per registered
 * class, named after it, describing a page and running nothing (rule in CLAUDE.md).
 * examples/demo.php mounts them all in one application, or one on its own with
 * `demo.php <Class>`. Adding a class therefore means adding examples/<Class>.php.
 */
final class ExampleTest extends TestCase
{
    private const DIR = __DIR__ . '/../examples';

    /**
     * Short name of every class, interface and enum the extension registers.
     *
     * @return iterable<string, array{string}>
     */
    public static function registeredClasses(): iterable
    {
        foreach (new ReflectionExtension('gtk4')->getClasses() as $class) {
            $name = $class->getName();
            yield $name => [substr($name, strrpos($name, '\\') + 1)];
        }
    }

    /** @return iterable<string, array{string}> */
    public static function exampleFiles(): iterable
    {
        foreach (glob(self::DIR . '/*.php') ?: [] as $file) {
            yield basename($file) => [$file];
        }
    }

    #[DataProvider('registeredClasses')]
    public function testEveryClassHasItsOwnExample(string $short): void
    {
        $file = self::DIR . '/' . $short . '.php';
        self::assertFileExists($file, "every registered class needs its own examples/$short.php");

        // Imports do not count as a use; the file has to do something with it.
        $src = preg_replace('/^use .*$/m', '', (string) file_get_contents($file)) ?? '';
        self::assertMatchesRegularExpression(
            '/\b' . preg_quote($short, '/') . '\b/',
            $src,
            "examples/$short.php never mentions $short outside its imports",
        );
    }

    #[DataProvider('exampleFiles')]
    public function testExampleHasNoSyntaxErrors(string $file): void
    {
        $out = [];
        exec(sprintf('%s -n -l %s 2>&1', escapeshellarg(PHP_BINARY), escapeshellarg($file)), $out, $rc);
        self::assertSame(0, $rc, implode("\n", $out));
    }

    #[DataProvider('exampleFiles')]
    public function testExampleIsSelfContained(string $file): void
    {
        $src = (string) file_get_contents($file);
        if (basename($file) === 'bootstrap.php') {
            // It requires the page files from inside pages(), but nothing at load
            // time - requiring the harness must stay free of side effects.
            self::assertStringNotContainsString('require __DIR__', $src, 'bootstrap.php must not require at load time');
            self::assertStringNotContainsString(
                'require_once __DIR__',
                $src,
                'bootstrap.php must not require at load time',
            );
            return;
        }
        // require_once, not require: demo.php requires every page file and each of
        // those requires the harness again.
        self::assertStringContainsString(
            "require_once __DIR__ . '/bootstrap.php';",
            $src,
            basename($file) . ' must require the shared harness so it runs on its own',
        );
    }

    /**
     * Each class file has to hand its demo back rather than run it, so that
     * examples/demo.php can mount the same source in the combined window.
     */
    #[DataProvider('registeredClasses')]
    public function testEveryClassExampleReturnsAPageAndRunsNothing(string $short): void
    {
        $src = (string) file_get_contents(self::DIR . '/' . $short . '.php');
        self::assertStringContainsString(
            'return Demo::page(',
            $src,
            "examples/$short.php must end in return Demo::page(...) so demo.php can mount it",
        );
        // A file that ran itself would fire the moment demo.php required it.
        foreach (['Demo::run(', 'Demo::showcase(', 'Demo::single('] as $forbidden) {
            self::assertStringNotContainsString(
                $forbidden,
                $src,
                "examples/$short.php must not run itself",
            );
        }

        // $win is the window the harness handed the page - the application's own when
        // demo.php mounts it. Vetoing its close-request made the application
        // unclosable; making it modal was not much better. A page that wants a window
        // to play with creates one (see GtkWindow.php, GParamSpec.php).
        foreach (["\$win->connect('close-request'", '$win->modal', '$win->destroy(', '$win->close('] as $hijack) {
            self::assertStringNotContainsString(
                $hijack,
                $src,
                "examples/$short.php must not take over the shared window - build your own",
            );
        }
    }

    /**
     * The sidebar groups the pages by section; until GtkScrolledWindow is bound that
     * is also what keeps it short enough to fit on screen. A class missing from the
     * map would be unreachable in the application.
     */
    public function testEveryClassBelongsToExactlyOneSidebarSection(): void
    {
        require_once __DIR__ . '/../examples/bootstrap.php';

        $placed = [];
        foreach (\PhpGtk4\Examples\Demo::sections() as $section => $members) {
            foreach ($members as $member) {
                self::assertArrayNotHasKey($member, $placed, "$member is in two sections");
                $placed[$member] = $section;
            }
        }

        $registered = [];
        foreach (self::registeredClasses() as [$short]) {
            $registered[] = $short;
        }
        sort($registered);
        $mapped = array_keys($placed);
        sort($mapped);
        self::assertSame($registered, $mapped, 'Demo::sections() must list every registered class once');
    }

    public function testTheCombinedDemoMountsEveryClass(): void
    {
        $classes = [];
        foreach (self::registeredClasses() as [$short]) {
            $classes[] = $short;
        }
        $pages = [];
        foreach (glob(self::DIR . '/*.php') ?: [] as $file) {
            $name = basename($file, '.php');
            if (in_array($name, ['bootstrap', 'demo'], true)) {
                continue;
            }
            $pages[] = $name;
        }
        sort($classes);
        sort($pages);
        self::assertSame($classes, $pages, 'examples/ must hold exactly one page per registered class');
    }
}
