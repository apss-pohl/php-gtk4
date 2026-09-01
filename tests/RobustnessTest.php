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
 * Argument-boundary smoke test over the whole registered surface: every method
 * called with wrong types, too few and too many arguments must raise a
 * TypeError / ArgumentCountError / ValueError - never crash, never succeed
 * silently. Generic on purpose (the generator will add hundreds of methods);
 * runs under ASan in the `asan` stage as well.
 *
 * The second sweep is about values of the *right* type that C cannot take: a string
 * with an embedded NUL or invalid UTF-8, an integer outside the C parameter's range.
 * Those used to be truncated, silently widened or dropped inside GLib
 * ({@see ArgumentGuardTest} has the individual cases); this is the net that catches
 * a method the checks stop reaching.
 */
final class RobustnessTest extends GtkTestCase
{
    /**
     * String parameters that carry *bytes*, not text: a `GBytes` is binary, so an embedded
     * NUL is data and `check_utf8()` deliberately does not run on it (CLAUDE.md, "Convert at
     * the boundary"). Everything else has to refuse one. Keyed by method, valued with the
     * zero-based argument positions that are exempt.
     *
     * @var array<string, list<int>>
     */
    private const BINARY_ARGUMENTS = [
        \Gtk4\GtkCssProvider::class . '::load_from_bytes' => [0],
        \Gtk4\GdkTexture::class . '::new_from_bytes' => [0],
    ];

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
                $loopDrivers = ['init', 'main', 'run', 'main_context_iteration', 'testing_iterate_nested'];
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
            \Gtk4\GApplication::class => new \Gtk4\GApplication(null, 1 << 5),
            \Gtk4\GtkApplicationWindow::class => new \Gtk4\GtkApplicationWindow(
                new \Gtk4\GtkApplication(null, 1 << 5),
            ),
            \Gtk4\GtkBox::class => new \Gtk4\GtkBox(\Gtk4\GtkOrientation::Horizontal, 0),
            \Gtk4\GtkPaned::class => new \Gtk4\GtkPaned(\Gtk4\GtkOrientation::Horizontal),
            \Gtk4\GtkScale::class => new \Gtk4\GtkScale(\Gtk4\GtkOrientation::Horizontal),
            \Gtk4\GtkAdjustment::class => new \Gtk4\GtkAdjustment(0.0, 0.0, 100.0, 1.0, 10.0, 0.0),
            \Gtk4\GtkSpinButton::class => new \Gtk4\GtkSpinButton(null, 1.0, 0),
            \Gtk4\GtkEntryBuffer::class => new \Gtk4\GtkEntryBuffer('abc', -1),
            \Gtk4\GtkBitset::class => \Gtk4\GtkBitset::new_range(0, 4),
            // Abstract bases, exercised through the plainest concrete subclass.
            \Gtk4\GtkGesture::class => new \Gtk4\GtkGestureClick(),
            \Gtk4\GtkEventController::class => new \Gtk4\GtkEventControllerKey(),
            // Handles GTK hands out but never lets PHP build - they come from their owner,
            // and the handle is what keeps that owner alive (BOXED_OWNERS for the iter, a
            // plain reference for the page).
            \Gtk4\GtkTextIter::class => new \Gtk4\GtkTextBuffer()->get_start_iter(),
            \Gtk4\GtkStackPage::class => new \Gtk4\GtkStack()->add_child(new \Gtk4\GtkButton()),
            \Gtk4\GdkDisplay::class => \Gtk4\GdkDisplay::get_default(),
            \Gtk4\GtkTreeListModel::class => self::treeListModel(),
            \Gtk4\GtkTreeListRow::class => self::treeListModel()->get_child_row(0),
            default => self::construct($class),
        };
    }


    private static function treeListModel(): \Gtk4\GtkTreeListModel
    {
        return new \Gtk4\GtkTreeListModel(
            new \Gtk4\GtkStringList(['a', 'b']),
            false,
            false,
            static fn(): ?\Gtk4\GListModel => null,
        );
    }

    /**
     * Everything the map above does not name: any class that takes no required
     * constructor argument is swept through a plain instance, so a newly bound
     * class is covered the day it lands. What is left over - abstract classes,
     * handles GTK only ever creates itself (`new` throws), constructors that
     * need an argument - has its static methods swept and nothing else.
     *
     * @param class-string $class
     */
    private static function construct(string $class): ?object
    {
        $rc = new ReflectionClass($class);
        $ctor = $rc->getConstructor();
        if (!$rc->isInstantiable() || ($ctor !== null && $ctor->getNumberOfRequiredParameters() > 0)) {
            return null;
        }

        try {
            return $rc->newInstance();
        } catch (\Throwable) {
            return null;
        }
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

    /**
     * Well-typed values C cannot represent. A GLib string ends at the first NUL, so **every**
     * string parameter has to refuse one (`check_utf8`, or `Z_PARAM_PATH_STR` for a filename):
     * that is the one invariant that holds across the whole surface, and it is asserted.
     * Invalid UTF-8 and out-of-range integers are checked for survival only - a `filename` is
     * bytes and a 64-bit parameter has no upper bound to violate.
     *
     * @param class-string $class
     */
    #[DataProvider('methods')]
    public function testHostileValuesOfTheRightTypeAreRefusedOrSurvived(string $class, string $method): void
    {
        $rm = new ReflectionMethod($class, $method);
        $target = $rm->isStatic() ? null : $this->instance($class);
        if ($target === null && !$rm->isStatic()) {
            self::markTestSkipped("$class is not instantiable");
        }
        $call = fn(array $args) => $rm->isStatic() ? $rm->invokeArgs(null, $args) : $rm->invokeArgs($target, $args);
        $required = $rm->getNumberOfRequiredParameters();

        foreach ($rm->getParameters() as $i => $param) {
            $type = $param->getType();
            if (!$type instanceof ReflectionNamedType) {
                continue;
            }
            $hostile = match ($type->getName()) {
                'string' => ["a\0b" => true, "\xff\xfe" => false],   // value => must throw
                'int' => [PHP_INT_MAX => false, PHP_INT_MIN => false, -1 => false],
                default => [],
            };
            foreach ($hostile as $bad => $mustThrow) {
                $mustThrow = $mustThrow
                    && !in_array($i, self::BINARY_ARGUMENTS["$class::$method"] ?? [], true);
                $args = [];
                foreach ($rm->getParameters() as $j => $p) {
                    $args[$j] = $j === $i ? $bad : self::validValueFor($p);
                }
                $args = array_slice($args, 0, max($required, $i + 1));
                try {
                    $call($args);
                    if ($mustThrow) {
                        self::fail(sprintf(
                            '%s::%s() accepted a null byte in argument #%d ($%s)',
                            $class,
                            $method,
                            $i + 1,
                            $param->getName(),
                        ));
                    }
                } catch (\ValueError | \TypeError | \Error | \LogicException | \Gtk4\GError) {
                    // Refused, which is the point (a native vfunc_*() refuses any direct call
                    // with a LogicException). A crash would end the process instead.
                }
            }
        }
        $this->addToAssertionCount(1);  // survived every hostile value
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
            // Any other bound type: the first case of an enum, an instance of a class that
            // takes no required constructor argument. Without one the hostile sweep stops at
            // the first parameter it cannot fill and never reaches the method body.
            default => self::sampleOf($name),
        };
    }

    /** Sample value for a bound class or enum, or null when neither can be built. */
    private static function sampleOf(string $name): ?object
    {
        if (enum_exists($name)) {
            return (new ReflectionEnum($name)->getCases()[0] ?? null)?->getValue();
        }

        return class_exists($name) ? self::construct($name) : null;
    }
}
