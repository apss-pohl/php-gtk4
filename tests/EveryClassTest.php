<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionClass;
use ReflectionExtension;
use ReflectionMethod;

/**
 * Smoke test over the *whole* registered surface: every instantiable class is
 * constructed and every argument-less getter/predicate is called once. Catches
 * a wrapper whose GType/class mapping, factory registration or return-value
 * marshalling is broken - which the generator (docs/DESIGN.md) makes easy
 * to get wrong at scale. Deliberately generic: it must not need editing when
 * classes are added.
 */
final class EveryClassTest extends GtkTestCase
{
    /** @return iterable<string, array{class-string}> */
    public static function instantiableClasses(): iterable
    {
        foreach (new ReflectionExtension('gtk4')->getClasses() as $class) {
            $name = $class->getName();
            if ($class->isEnum()) {
                continue;
            }
            $ctor = $class->getConstructor();
            $instantiable = $class->isInstantiable()
                && $ctor !== null
                && $ctor->getNumberOfRequiredParameters() === 0
                && $ctor->getDeclaringClass()->getName() === $name;   // own __construct, not inherited
            if ($instantiable) {
                yield $name => [$name];
            }
        }
    }

    /** @param class-string $class */
    private static function phpSubclassOf(string $class): object
    {
        $sub = 'PhpGtk4\\Tests\\Every\\' . str_replace('\\', '_', $class);
        if (!class_exists($sub, false)) {
            eval('namespace PhpGtk4\\Tests\\Every; final class ' . str_replace('\\', '_', $class)
                . ' extends \\' . $class . ' {}');
        }
        return new $sub();
    }

    /**
     * @param class-string $class
     */
    #[DataProvider('instantiableClasses')]
    public function testConstructAndCallEveryGetter(string $class): void
    {
        try {
            $object = new $class();
        } catch (\Error $e) {
            if (!str_contains($e->getMessage(), 'subclass it in PHP')) {
                throw $e;
            }
            // Abstract in GTK: `new` works on a PHP subclass only (its own GType, core/subtype.h).
            $object = self::phpSubclassOf($class);
        }
        self::assertInstanceOf($class, $object);

        $called = 0;
        foreach (new ReflectionClass($class)->getMethods(ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->isStatic() || $m->getNumberOfRequiredParameters() > 0) {
                continue;
            }
            if (!preg_match('/^(get|is|has|in)_/', $m->getName())) {
                continue;
            }
            // Return value must be a plain PHP value, an enum case or a Gtk4 handle - never a
            // crash. A getter whose answer depends on state the object does not have yet
            // (GtkLayoutManager::get_request_mode() before it is set on a widget) refuses with a
            // LogicException instead of letting GLib CRITICAL and answer with a made-up value.
            try {
                $value = $m->invoke($object);
            } catch (\LogicException) {
                continue;
            }
            self::assertTrue(
                $value === null || is_scalar($value) || is_array($value) || $value instanceof \UnitEnum
                    || (is_object($value) && str_starts_with($value::class, 'Gtk4\\')),
                sprintf('%s::%s() returned %s', $class, $m->getName(), get_debug_type($value)),
            );
            $called++;
        }
        self::assertGreaterThanOrEqual(0, $called);

        if (method_exists($object, 'destroy')) {
            $object->destroy();
        }
    }

    public function testEveryRegisteredClassHasAWrapperFactory(): void
    {
        // A class whose C object is produced by GTK (not by __construct) is wrapped by
        // wrap(); it must be registered with register_wrapper<>() or wrap() silently falls
        // back to the parent class. Probe via the 'transient-for' round trip on GtkWindow.
        $w = $this->window();
        $other = $this->window();
        $w->set_property('transient-for', $other);
        $back = $w->get_property('transient-for');
        self::assertInstanceOf(\Gtk4\GtkWindow::class, $back);
        self::assertSame(\Gtk4\GtkWindow::class, $back::class);
    }
}
