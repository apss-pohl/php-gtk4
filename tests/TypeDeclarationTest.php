<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionClass;
use ReflectionEnum;
use ReflectionExtension;
use ReflectionMethod;
use ReflectionNamedType;

/**
 * The declared types are a promise PHP does not keep for us: the engine never verifies the
 * return type of an internal function, so a wrong declaration is invisible at runtime and
 * PHPStan reasons on it anyway. `StubsTest` compares the *names* the stub and the extension
 * agree on; this compares the types against what the methods really return.
 *
 * The interface case is the one that bit (type review, 2026-08-30): `GtkEntry::get_delegate()`
 * declares `?GtkEditable` and returned a plain `Gtk4\GtkWidget`, because the concrete GtkText
 * was not bound and `wrap()` stops at the nearest registered *class*. Calling the interface's
 * own methods on the result was an `Error`.
 */
final class TypeDeclarationTest extends GtkTestCase
{
    /** Releases the owners {@see GtkInstances} pinned for the handles it handed out. */
    protected function tearDown(): void
    {
        GtkInstances::release();
        parent::tearDown();
    }

    /**
     * Every class the extension registers, abstract bases included: {@see GtkInstances} builds
     * those through their plainest concrete subclass, and the getters they *declare* are
     * exactly the ones no subclass test re-checks.
     *
     * @return iterable<string, array{class-string}>
     */
    public static function instantiableClasses(): iterable
    {
        foreach (new ReflectionExtension('gtk4')->getClasses() as $class) {
            if ($class->isInterface() || $class->isEnum()) {
                continue;
            }
            yield $class->getName() => [$class->getName()];
        }
    }

    /** @param class-string $class */
    #[DataProvider('instantiableClasses')]
    public function testArglessGettersReturnWhatTheyDeclare(string $class): void
    {
        $reflection = new ReflectionClass($class);
        $object = $this->construct($reflection);
        if ($object === null) {
            $reason = GtkInstances::UNREACHABLE[$class] ?? null;
            self::markTestSkipped(
                $reason === null
                    ? "$class has no instance this test can build"
                    : "$class cannot be built: $reason",
            );
        }

        $checked = 0;
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if (!$this->isSafeGetter($method, $class)) {
                continue;
            }
            $declared = $method->getReturnType();
            if (!$declared instanceof ReflectionNamedType || $declared->getName() === 'void') {
                continue;
            }
            try {
                $value = $method->invoke($object);
            } catch (\Throwable) {
                continue;   // refusing to answer is fine; answering with the wrong type is not
            }
            $checked++;
            // Reachability check for GtkInstances::UNREACHABLE: that list says which classes no
            // test can produce an instance of, and a getter handing one out is the proof that a
            // reason has gone stale (a later wave binding GtkWidget::get_settings() would make
            // GtkStyleProviderObject reachable, say). The claims are prose; this is the check.
            if (is_object($value)) {
                self::assertArrayNotHasKey(
                    $value::class,
                    GtkInstances::UNREACHABLE,
                    sprintf('%s::%s() hands out a class GtkInstances calls unreachable', $class, $method->getName()),
                );
            }
            self::assertTrue(
                $this->satisfies($value, $declared),
                sprintf(
                    '%s::%s() declares %s%s but returned %s',
                    $class,
                    $method->getName(),
                    $declared->allowsNull() && $declared->getName() !== 'mixed' ? '?' : '',
                    $declared->getName(),
                    get_debug_type($value),
                ),
            );
        }
        // The finding is the assertion inside the loop. There is nothing true left to assert
        // here - a class whose getters all refuse to answer checks nothing and is not wrong to -
        // so count the pass rather than dress a tautology up as an assertion (the hostile sweep
        // in RobustnessTest ends the same way).
        $this->addToAssertionCount(1);
        unset($checked);
    }

    /**
     * A method that promises an interface must return something implementing it. wrap() answers
     * with the nearest registered class, so an unbound implementation silently breaks the
     * promise - which is why the concrete class has to be in gen/allowlist.txt.
     */
    public function testEveryInterfaceReturnTypeIsSatisfiable(): void
    {
        $interfaces = [];
        foreach (new ReflectionExtension('gtk4')->getClasses() as $class) {
            if ($class->isInterface()) {
                $interfaces[$class->getName()] = true;
            }
        }
        self::assertNotSame([], $interfaces);

        $declaring = 0;
        foreach (new ReflectionExtension('gtk4')->getClasses() as $class) {
            foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                $type = $method->getReturnType();
                if (!$type instanceof ReflectionNamedType || !isset($interfaces[$type->getName()])) {
                    continue;
                }
                $declaring++;
            }
        }
        // Not a behaviour assertion on its own: it keeps the count visible, because every one
        // of these is a promise the getter sweep above has to be able to check.
        self::assertGreaterThan(0, $declaring, 'no method declares an interface return type');
    }

    private function satisfies(mixed $value, ReflectionNamedType $declared): bool
    {
        $name = $declared->getName();
        if ($value === null) {
            return $declared->allowsNull();
        }
        if ($name === 'mixed') {
            return true;
        }
        $actual = get_debug_type($value);
        if ($name === $actual) {
            return true;
        }
        if ($name === 'float' && $actual === 'int') {   // widening, as everywhere in PHP
            return true;
        }
        return (class_exists($name) || interface_exists($name)) && $value instanceof $name;
    }

    /**
     * The getter target. {@see GtkInstances} is asked first - it is the same table the hostile
     * sweep uses, so the two agree on which classes have a live instance - and only what it
     * does not name falls back to inventing scalar constructor arguments here.
     *
     * @param ReflectionClass<object> $class
     */
    private function construct(ReflectionClass $class): ?object
    {
        $name = $class->getName();
        if ($name === \Gtk4\GtkWindow::class) {
            return $this->window();
        }
        $named = GtkInstances::make($name);
        if ($named !== null) {
            return $named;
        }
        $constructor = $class->getConstructor();
        if ($constructor === null || $constructor->isPrivate()) {
            return null;
        }
        $arguments = [];
        foreach ($constructor->getParameters() as $parameter) {
            if ($parameter->isOptional()) {
                break;
            }
            $type = $parameter->getType();
            if ($type instanceof ReflectionNamedType && $type->allowsNull()) {
                $arguments[] = null;
                continue;
            }
            $typeName = $type instanceof ReflectionNamedType ? $type->getName() : 'mixed';
            $arguments[] = match (true) {
                $typeName === 'string' => 'x',
                $typeName === 'int' => 0,
                $typeName === 'float' => 0.0,
                $typeName === 'bool' => false,
                $typeName === 'array' => [],
                $typeName === 'callable' => static fn() => null,
                // A GTK enum parameter (GtkOrientation, GtkSizeGroupMode, ...) is inventable:
                // any case will do for calling arg-less getters afterwards, and this covers
                // every class whose only obstacle was one, without a per-class list.
                enum_exists($typeName) => (new ReflectionEnum($typeName)->getCases()[0] ?? null)?->getValue(),
                default => null,
            };
            if (end($arguments) === null && !($type?->allowsNull() ?? false)) {
                return null;   // an object argument we cannot invent
            }
        }
        try {
            return $class->newInstanceArgs($arguments);
        } catch (\Throwable) {
            return null;
        }
    }

    /** @param class-string $class */
    private function isSafeGetter(ReflectionMethod $method, string $class): bool
    {
        if ($method->getDeclaringClass()->getName() !== $class) {
            return false;   // inherited: checked on the class that declares it
        }
        if ($method->getNumberOfParameters() !== 0 || $method->isConstructor() || $method->isStatic()) {
            return false;
        }
        // vfunc_*() refuse a direct call, and the loop drivers would block the suite.
        return !str_starts_with($method->getName(), 'vfunc_')
            && !in_array($method->getName(), ['run', 'main', 'main_context_iteration', 'popup'], true);
    }
}
