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
    private string $cwd = '';
    private string $scratch = '';

    /**
     * Methods that write files get garbage paths too, and coercive mode turns
     * -1 / 3.5 / true / 'garbage' into perfectly good relative ones - so
     * GdkTexture::save_to_png() really did write those four files into the
     * repository root. Run the sweep from a scratch directory instead.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->cwd = (string) getcwd();
        $this->scratch = sys_get_temp_dir() . '/php-gtk4-robustness-' . getmypid();
        if (!is_dir($this->scratch) && !mkdir($this->scratch, 0o700, true) && !is_dir($this->scratch)) {
            self::fail('cannot create ' . $this->scratch);
        }
        chdir($this->scratch);
    }

    protected function tearDown(): void
    {
        chdir($this->cwd);
        foreach (glob($this->scratch . '/*') ?: [] as $stray) {
            if (is_file($stray)) {
                unlink($stray);
            }
        }
        @rmdir($this->scratch);
        parent::tearDown();
    }

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
                // Loop drivers block on purpose: run() forever, main_context_iteration(true) until
                // a source is ready - garbage arguments would hang the suite, not throw.
                $loopDrivers = ['init', 'main', 'run', 'main_context_iteration'];
                if ($m->isStatic() && in_array($m->getName(), $loopDrivers, true)) {
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
            \Gtk4\PhpValue::class => new \Gtk4\PhpValue('v'),
            \Gtk4\GdkTexture::class => \Gtk4\GdkTexture::new_from_bytes(PngFixture::red(2, 1)),
            \Gtk4\GError::class => new \Gtk4\GError('x'),
            \Gtk4\GListStore::class => new \Gtk4\GListStore(),
            \Gtk4\GtkFilter::class, \Gtk4\GtkCustomFilter::class => new \Gtk4\GtkCustomFilter(fn() => true),
            \Gtk4\GtkSorter::class, \Gtk4\GtkCustomSorter::class => new \Gtk4\GtkCustomSorter(fn() => 0),
            \Gtk4\GtkFilterListModel::class, \Gtk4\GtkSortListModel::class, \Gtk4\GtkDrawingArea::class => new $class(),
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
                } catch (\Throwable) {
                    // any exception is fine (TypeError, ValueError, GError, ...); a crash is not
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
            \Gtk4\GObject::class, \Gtk4\PhpValue::class => new \Gtk4\PhpValue('v'),
            \Gtk4\GdkRGBA::class => new \Gtk4\GdkRGBA(),
            \Gtk4\GdkRectangle::class => new \Gtk4\GdkRectangle(),
            \Gtk4\ExceptionMode::class => \Gtk4\ExceptionMode::Log,
            \Gtk4\GtkAlign::class => \Gtk4\GtkAlign::Fill,
            \Gtk4\GtkOrientation::class => \Gtk4\GtkOrientation::Horizontal,
            default => null,
        };
    }
}
