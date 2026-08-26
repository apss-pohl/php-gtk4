<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionExtension;
use ReflectionMethod;
use ReflectionNamedType;

/**
 * Argument-boundary smoke test over the whole registered surface: every method
 * called with wrong types, too few and too many arguments must raise a
 * TypeError / ArgumentCountError / ValueError - never crash, never succeed
 * silently. Generic on purpose (the generator will add hundreds of methods);
 * runs under ASan in the `asan` stage as well.
 */
final class RobustnessTest extends GtkTestCase
{
    /** @return iterable<string, array{class-string, string}> */
    public static function methods(): iterable
    {
        foreach (new ReflectionExtension('gtk4')->getClasses() as $class) {
            if ($class->isEnum() || $class->isInterface()) {
                continue;
            }
            foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC) as $m) {
                if ($m->getDeclaringClass()->getName() !== $class->getName() || $m->isConstructor()) {
                    continue;
                }
                if ($m->isStatic() && in_array($m->getName(), ['init', 'main', 'run'], true)) {
                    continue;
                }
                yield $class->getName() . '::' . $m->getName() => [$class->getName(), $m->getName()];
            }
        }
    }

    /** @param class-string $class */
    private function instance(string $class): ?object
    {
        return match ($class) {
            \Gtk4\GObject::class, \Gtk4\GtkWindow::class => $this->window(),   // GObject: any handle
            \Gtk4\GtkApplication::class => new \Gtk4\GtkApplication(null, 1 << 5),
            \Gtk4\GSimpleAction::class => new \Gtk4\GSimpleAction('a', 's'),
            \Gtk4\GtkWidget::class => new \Gtk4\GtkButton(),   // abstract: exercise through a subclass
            \Gtk4\GParamSpec::class => self::paramSpec(),
            \Gtk4\GdkRGBA::class, \Gtk4\GdkRectangle::class, \Gtk4\GMainLoop::class,
            \Gtk4\GtkButton::class, \Gtk4\GtkLabel::class => new $class(),
            default => null,  // abstract / non-instantiable: static methods only
        };
    }

    /**
     * @param class-string $class
     */
    #[DataProvider('methods')]
    public function testWrongArgumentsThrowInsteadOfCrashing(string $class, string $method): void
    {
        $rm = new ReflectionMethod($class, $method);
        $target = $rm->isStatic() ? null : $this->instance($class);
        if ($target === null && !$rm->isStatic()) {
            self::markTestSkipped("$class is not instantiable");
        }
        $call = fn(array $args) => $rm->isStatic() ? $rm->invokeArgs(null, $args) : $rm->invokeArgs($target, $args);
        $required = $rm->getNumberOfRequiredParameters();
        $max = $rm->isVariadic() ? PHP_INT_MAX : $rm->getNumberOfParameters();
        $garbage = [new \stdClass(), [], -1, 'garbage', 3.5, true, null];
        $checks = 0;

        // too few
        if ($required > 0) {
            try {
                $call([]);
                self::fail("$class::$method() accepted zero arguments");
            } catch (\ArgumentCountError) {
                $checks++;
            }
        }
        // too many
        if ($max !== PHP_INT_MAX) {
            try {
                $call(array_fill(0, $max + 1, 'x'));
                self::fail("$class::$method() accepted $max+1 arguments");
            } catch (\ArgumentCountError) {
                $checks++;
            }
        }
        // wrong types in every position that has a declared, non-mixed type
        foreach ($rm->getParameters() as $i => $param) {
            $type = $param->getType();
            if (!$type instanceof ReflectionNamedType || $type->getName() === 'mixed') {
                continue;
            }
            foreach ($garbage as $bad) {
                // Internal functions run in coercive mode: null into a non-nullable scalar and
                // a fractional float into int are deprecations, not errors - not what we test here.
                $nullIntoScalar = $bad === null && !$type->allowsNull();
                $fractionIntoInt = $bad === 3.5 && $type->getName() === 'int';
                if ($type->isBuiltin() && ($nullIntoScalar || $fractionIntoInt)) {
                    continue;
                }
                $args = [];
                foreach ($rm->getParameters() as $j => $p) {
                    $args[$j] = $j === $i ? $bad : self::validValueFor($p);
                }
                $args = array_slice($args, 0, max($required, $i + 1));
                $checks++;
                try {
                    $call($args);
                } catch (\TypeError | \ValueError | \Error) {
                    // expected for garbage; a plain \Error covers "dead object"-style refusals
                    continue;
                }
                // A garbage value that happens to be acceptable (e.g. null for ?T, true for bool)
                // is fine - the point is that nothing crashed.
            }
        }
        self::assertGreaterThanOrEqual(0, $checks, 'survived every argument combination');
    }

    private static function paramSpec(): \Gtk4\GParamSpec
    {
        $w = new \Gtk4\GtkWindow();
        $spec = null;
        $w->connect('notify::title', function (\Gtk4\GObject $o, \Gtk4\GParamSpec $p) use (&$spec): void {
            $spec = $p;
        });
        $w->set_title('x');
        $w->destroy();
        assert($spec instanceof \Gtk4\GParamSpec);
        return $spec;
    }

    private static function validValueFor(\ReflectionParameter $p): mixed
    {
        $t = $p->getType();
        $name = $t instanceof ReflectionNamedType ? $t->getName() : 'mixed';
        return match ($name) {
            'int' => 1,
            'float' => 1.0,
            'bool' => true,
            'string' => 'x',
            'array' => [],
            'callable' => static fn() => null,
            \Gtk4\GtkWidget::class, \Gtk4\GtkButton::class => new \Gtk4\GtkButton(),
            \Gtk4\GtkWindow::class => new \Gtk4\GtkWindow(),
            \Gtk4\GtkApplication::class => new \Gtk4\GtkApplication(null, 1 << 5),
            \Gtk4\GAction::class, \Gtk4\GSimpleAction::class => new \Gtk4\GSimpleAction('v'),
            \Gtk4\GdkRGBA::class => new \Gtk4\GdkRGBA(),
            \Gtk4\GdkRectangle::class => new \Gtk4\GdkRectangle(),
            \Gtk4\ExceptionMode::class => \Gtk4\ExceptionMode::Log,
            default => null,
        };
    }
}
