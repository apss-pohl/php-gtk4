<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use PHPUnit\Framework\TestCase;
use ReflectionExtension;
use ReflectionMethod;

/**
 * stubs/gtk4.php must list exactly the classes, declared methods and
 * constants the loaded extension registers (names only; types are docs).
 */
final class StubsTest extends TestCase
{
    private const STUB = __DIR__ . '/../stubs/gtk4.php';

    private static function source(): string
    {
        $src = file_get_contents(self::STUB);
        self::assertIsString($src);
        return $src;
    }

    /** @return array{classes: array<string, list<string>>, constants: list<string>} */
    private static function fromExtension(): array
    {
        $ext = new ReflectionExtension('gtk4');
        $classes = [];
        foreach ($ext->getClasses() as $class) {
            if (str_starts_with($class->getName(), 'PhpCpp')) {
                continue;
            }
            $own = array_filter(
                $class->getMethods(),
                fn(ReflectionMethod $m) => $m->getDeclaringClass()->getName() === $class->getName(),
            );
            $methods = array_map(fn(ReflectionMethod $m) => $m->getName(), $own);
            sort($methods);
            $classes[$class->getName()] = $methods;
        }
        ksort($classes);
        $constants = array_keys($ext->getConstants());
        sort($constants);
        return ['classes' => $classes, 'constants' => $constants];
    }

    /** @return array{classes: array<string, list<string>>, constants: list<string>} */
    private static function fromStub(): array
    {
        $src = self::source();
        preg_match('/^namespace\s+([\w\\\\]+);/m', $src, $m);
        $ns = isset($m[1]) ? $m[1] . '\\' : '';

        $classes = [];
        preg_match_all('/^(?:final\s+)?class\s+(\w+)[^{]*\{(.*?)^\}/ms', $src, $found, PREG_SET_ORDER);
        foreach ($found as [, $name, $body]) {
            preg_match_all('/function\s+(\w+)\s*\(/', $body, $mm);
            $methods = $mm[1];
            sort($methods);
            $classes[$ns . $name] = $methods;
        }
        ksort($classes);

        preg_match_all('/^const\s+(\w+)\s*=/m', $src, $mm);
        $constants = array_map(fn($c) => $ns . $c, $mm[1]);
        sort($constants);
        return ['classes' => $classes, 'constants' => $constants];
    }

    public function testStubDeclaresNamespace(): void
    {
        self::assertMatchesRegularExpression('/^namespace Gtk4;/m', self::source());
    }

    public function testClassesMatch(): void
    {
        self::assertSame(array_keys(self::fromExtension()['classes']), array_keys(self::fromStub()['classes']));
    }

    public function testMethodsMatchPerClass(): void
    {
        $stub = self::fromStub()['classes'];
        foreach (self::fromExtension()['classes'] as $class => $methods) {
            self::assertSame($methods, $stub[$class] ?? [], "declared methods of $class");
        }
    }

    public function testConstantsMatch(): void
    {
        self::assertSame(self::fromExtension()['constants'], self::fromStub()['constants']);
    }

    public function testStubMethodsHaveDummyBodies(): void
    {
        // IDE rule (CLAUDE.md): never `{}` - unset() params, placeholder return for non-void.
        $src = self::source();
        preg_match_all('/function\s+(\w+)\s*\(([^)]*)\)\s*(?::\s*([^\s{]+))?\s*\{([^}]*)\}/', $src, $m, PREG_SET_ORDER);
        self::assertNotEmpty($m);
        foreach ($m as [, $name, $params, $ret, $body]) {
            preg_match_all('/\$(\w+)/', $params, $pm);
            foreach ($pm[1] as $p) {
                self::assertStringContainsString("unset(\$$p)", $body, "$name(): parameter \$$p not unset()");
            }
            if ($ret !== '' && $ret !== 'void') {
                self::assertStringContainsString('return ', $body, "$name(): non-void without placeholder return");
            }
        }
    }
}
