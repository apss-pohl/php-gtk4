<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionExtension;

/**
 * examples/ is the canonical showcase and it is atomic: one file per registered
 * class, named after it, runnable on its own (rule in CLAUDE.md). Adding a class
 * therefore means adding examples/<Class>.php.
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
            self::assertStringNotContainsString('require', $src, 'bootstrap.php must not require anything');
            return;
        }
        self::assertStringContainsString(
            "require __DIR__ . '/bootstrap.php';",
            $src,
            basename($file) . ' must require the shared harness so it runs on its own',
        );
    }
}
