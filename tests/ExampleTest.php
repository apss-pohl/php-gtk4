<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use PHPUnit\Framework\TestCase;
use ReflectionExtension;

/**
 * examples/example.php is the canonical showcase: every registered class must
 * be used in it (rule in CLAUDE.md).
 */
final class ExampleTest extends TestCase
{
    private const EXAMPLE = __DIR__ . '/../examples/example.php';

    public function testEveryClassAppearsInTheExample(): void
    {
        $src = file_get_contents(self::EXAMPLE);
        self::assertIsString($src);
        $src = preg_replace('/^use .*$/m', '', $src) ?? '';
        $missing = [];
        foreach (new ReflectionExtension('gtk4')->getClasses() as $class) {
            $name = $class->getName();
            if (str_starts_with($name, 'PhpCpp')) {
                continue;
            }
            $short = substr($name, strrpos($name, '\\') + 1);
            // Used as `new X(`, `X::`, a type hint `X $`, or imported and used.
            if (!preg_match('/\b' . preg_quote($short, '/') . '\b(?!\s*[,}])/', $src)) {
                $missing[] = $name;
            }
        }
        self::assertSame([], $missing, 'classes not showcased in examples/example.php');
    }

    public function testExampleHasNoSyntaxErrors(): void
    {
        $out = [];
        exec(sprintf('%s -n -l %s 2>&1', escapeshellarg(PHP_BINARY), escapeshellarg(self::EXAMPLE)), $out, $rc);
        self::assertSame(0, $rc, implode("\n", $out));
    }
}
