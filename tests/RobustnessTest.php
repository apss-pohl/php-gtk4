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
        \Gtk4\GdkContentProvider::class . '::new_for_bytes' => [1],
    ];

    private string $cwd = '';
    private string $scratch = '';

    /**
     * Methods that write files get garbage paths too, and coercive mode turns
     * -1 / 3.5 / true / 'garbage' into perfectly good relative ones - so
     * GdkTexture::save_to_png() really did write those four files into the
     * repository root. Run the sweep from a scratch directory instead.
     */
    /**
     * The file listing the `Class::method` keys whose sweep GTK is known to complain about.
     * One line each, sorted; `#` comments and blank lines ignored.
     */
    private const PINNED = __DIR__ . '/robustness-criticals.txt';

    /**
     * Its whole job is to hand GTK hostile values of the right type, so GTK's own preconditions
     * firing is an expected outcome here rather than a defect - but *which* methods reach one is
     * pinned in {@see PINNED}, not tolerated wholesale. tearDown() gates on that list, so a
     * method that starts complaining fails the sweep even though the suite as a whole expects
     * complaints. Keyed on the triggering method, never on GTK's wording, which changes between
     * versions.
     */
    protected function toleratesGtkCriticals(): bool
    {
        return true;   // gated by tearDown() against PINNED instead
    }

    /** The key currently being swept, or '' outside the two sweep tests. */
    private string $sweeping = '';

    /**
     * The pinned keys, read once.
     *
     * @return array<string, true>
     */
    private static function pinned(): array
    {
        /** @var array<string, true>|null $keys */
        static $keys = null;
        if ($keys === null) {
            $keys = [];
            foreach (file(self::PINNED, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
                $line = trim($line);
                if ($line !== '' && !str_starts_with($line, '#')) {
                    $keys[$line] = true;
                }
            }
        }
        return $keys;
    }

    /**
     * Compare what GTK actually said against the pin. Both directions fail: a method that
     * complains without being listed is a boundary the binding should be closing (or a line to
     * add deliberately), and a listed method that stayed quiet means the list is stale.
     *
     * Called from tearDown() *after* parent::tearDown() has restored the error handler, so a
     * failure here cannot leave one installed (PHPUnit calls that risky).
     *
     * @param list<string> $seen
     */
    private function gateAgainstPinnedList(string $key, array $seen): void
    {
        if ($key === '') {
            return;
        }
        // GTK4_PIN_REPORT=<file>: append what GTK said for every key, pinned or not - the review
        // surface behind the pin file, since a line pins the *method* and not GTK's wording
        // (tests/README-robustness-pin.md).
        $report = getenv('GTK4_PIN_REPORT');
        if (is_string($report) && $report !== '' && $seen !== []) {
            file_put_contents($report, $key . "\t" . implode(' | ', array_unique($seen)) . "\n", FILE_APPEND);
        }
        // GTK reporting about the *machine*, not about the value it was handed: the same call
        // is silent on a desktop and a complaint on a CI runner, so neither direction of the pin
        // can hold it. Only what is genuinely environmental belongs here.
        $seen = array_values(array_filter($seen, static fn(string $m): bool => !str_contains(
            $m,
            'not supported by GtkFileChooserNativePortal because portal is too old',
        )));
        $isPinned = isset(self::pinned()[$key]);
        if ($seen !== [] && !$isPinned) {
            self::fail(
                "GTK complained while sweeping $key, which " . basename(self::PINNED)
                . " does not list. Close the boundary, or add the line if GTK's precondition is "
                . "the point:\n  " . implode("\n  ", $seen),
            );
        }
        if ($seen === [] && $isPinned) {
            self::fail(
                basename(self::PINNED) . " lists $key but GTK no longer complains about it - "
                . 'drop the line.',
            );
        }
    }

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
        $key = $this->sweeping;
        GtkInstances::release();
        $seen = $this->gtkCriticals();
        $this->sweeping = '';
        chdir($this->cwd);
        foreach (glob($this->scratch . '/*') ?: [] as $stray) {
            if (is_file($stray)) {
                unlink($stray);
            }
        }
        @rmdir($this->scratch);
        parent::tearDown();
        $this->gateAgainstPinnedList($key, $seen);
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

    /**
     * Every `@property` the IDE stub declares, with its type and access - `read` for a
     * `@property-read` tag, `write` for `@property-write`, `both` for a plain `@property`.
     *
     * @return iterable<string, array{class-string, string, string, string}>
     */
    public static function properties(): iterable
    {
        $stub = (string) file_get_contents(__DIR__ . '/../stubs/gtk4.php');
        preg_match_all('#/\*\*(.*?)\*/\s*(?:final\s+)?class\s+(\w+)#s', $stub, $classes, PREG_SET_ORDER);
        foreach ($classes as [, $doc, $short]) {
            /** @var class-string $class */
            $class = 'Gtk4\\' . $short;
            preg_match_all('/@property(-read|-write)?\\s+(\\S+)\\s+\\$(\\w+)/', $doc, $tags, PREG_SET_ORDER);
            foreach ($tags as [, $access, $type, $name]) {
                yield "$class::\$$name" => [$class, $name, $type, $access === '' ? 'both' : ltrim($access, '-')];
            }
        }
    }

    /**
     * A property write converts like the equivalent setter's parameter, so the same hostile
     * values are refused or survived - and a read-only or construct-only property refuses every
     * write with an Error rather than growing a dynamic property or warning from GLib.
     *
     * @param class-string $class
     */
    #[DataProvider('properties')]
    public function testHostilePropertyWritesAreRefusedOrSurvived(
        string $class,
        string $name,
        string $type,
        string $access,
    ): void {
        $this->sweeping = $class . '::$' . $name . '#properties';
        $target = $this->instance($class);
        if ($target === null) {
            self::markTestSkipped(self::whyNoInstance($class));
        }
        if ($access === 'read') {
            try {
                $target->$name = null;
                self::fail("$class::\$$name is declared read-only but took a write");
            } catch (\Error $e) {
                self::assertStringContainsString('Cannot modify', $e->getMessage());
            }
            return;
        }
        // value, must be refused
        $hostile = match (ltrim($type, '?')) {
            'string' => [["a\0b", true], ["\xff\xfe", true], [5, true]],
            'int' => [[PHP_INT_MAX, false], [PHP_INT_MIN, false], [-1, false], ['x', true]],
            'float' => [[1e308, false], [-1e308, false], ['x', true]],
            'bool' => [['yes', true]],
            'array' => [[["a\0b"], true], [[[1]], true]],
            default => [],
        };
        $hostile[] = [new \stdClass(), true];  // never the declared type
        foreach ($hostile as [$bad, $mustThrow]) {
            try {
                $target->$name = $bad;
                if ($mustThrow) {
                    self::fail(sprintf('%s::$%s accepted %s', $class, $name, get_debug_type($bad)));
                }
            } catch (\ValueError | \TypeError | \Error | \LogicException | \Gtk4\GError) {
                // refused, which is the point; a crash would end the process
            }
        }
        $this->addToAssertionCount(1);
    }

    /**
     * Signals whose class handler needs the widget in a state PHP cannot put it in from a bare
     * instance - realized, mapped, in a toplevel - and which GTK asserts on rather than refuses:
     * emitting them is the lifecycle running backwards, never something a script means to do.
     */
    private const LIFECYCLE_SIGNALS = ['realize', 'unrealize', 'map', 'unmap', 'destroy', 'show', 'hide'];

    /**
     * Every signal of every class {@see GtkInstances} can build, with what list_signals() says
     * about it.
     *
     * @return iterable<string, array{class-string, string, array{params: list<string>, return: ?string,
     *     action: bool, detailed: bool}}>
     */
    public static function signals(): iterable
    {
        foreach (new ReflectionExtension('gtk4')->getClasses() as $class) {
            if ($class->isEnum() || $class->isInterface() || !$class->isSubclassOf(\Gtk4\GObject::class)) {
                continue;
            }
            $name = $class->getName();
            $instance = $name === \Gtk4\GtkWindow::class ? new \Gtk4\GtkWindow() : GtkInstances::make($name);
            if (!$instance instanceof \Gtk4\GObject) {
                continue;
            }
            foreach ($instance->list_signals() as $signal => $info) {
                yield "$name::$signal" => [$name, $signal, $info];
            }
            if ($instance instanceof \Gtk4\GtkWindow) {
                $instance->destroy();
            }
        }
        GtkInstances::release();
    }

    /**
     * A value a signal parameter of GType `$gtype` accepts, or null when the sweep cannot build
     * one (an object of an unbound class, a boxed record without a constructor).
     */
    private static function signalValueFor(string $gtype): mixed
    {
        return match ($gtype) {
            'gchararray' => 'x',
            'gint', 'guint', 'glong', 'gulong', 'gint64', 'guint64', 'gsize', 'gssize', 'gchar', 'guchar' => 1,
            'gdouble', 'gfloat' => 1.0,
            'gboolean' => true,
            'GStrv' => ['x'],
            'GVariant' => 'v',
            // an enum's first case, an instance of a class with a no-argument constructor, or
            // nothing (the row is then skipped, naming the type)
            default => self::sampleOf('Gtk4\\' . $gtype),
        };
    }

    /**
     * emit() converts every argument like a typed parameter and then runs GTK's own class
     * handler with it: each string parameter has to refuse a NUL and invalid UTF-8, each
     * parameter has to refuse a value of another type, and the hostile values of the right
     * type are survived. What GTK's handler complains about is pinned as `<class>::<signal>#emit`.
     *
     * @param class-string $class
     * @param array{params: list<string>, return: ?string, action: bool, detailed: bool} $info
     */
    #[DataProvider('signals')]
    public function testHostileSignalEmissionsAreRefusedOrSurvived(string $class, string $signal, array $info): void
    {
        $this->sweeping = $class . '::' . $signal . '#emit';
        if (in_array($signal, self::LIFECYCLE_SIGNALS, true)) {
            $this->sweeping = '';
            self::markTestSkipped("$signal is the widget lifecycle, not something a script emits");
        }
        $target = $this->instance($class);
        if (!$target instanceof \Gtk4\GObject) {
            self::markTestSkipped(self::whyNoInstance($class));
        }
        $valid = [];
        foreach ($info['params'] as $gtype) {
            $value = self::signalValueFor($gtype);
            if ($value === null) {
                $this->sweeping = '';
                self::markTestSkipped("no value for a $gtype parameter");
            }
            $valid[] = $value;
        }
        foreach ($info['params'] as $i => $gtype) {
            $hostile = match ($gtype) {
                'gchararray' => [["a\0b", true], ["\xff\xfe", true], [[], true]],
                'gint', 'guint', 'glong', 'gulong', 'gint64', 'guint64', 'gsize', 'gssize' => [
                    [-1, false], [PHP_INT_MAX, false], [PHP_INT_MIN, false], [[], true],
                ],
                'gdouble', 'gfloat' => [[INF, false], [-INF, false], [[], true]],
                'gboolean' => [[[], true]],
                default => [[new \stdClass(), true]],
            };
            foreach ($hostile as [$bad, $mustThrow]) {
                $args = $valid;
                $args[$i] = $bad;
                try {
                    $target->emit($signal, ...$args);
                    if ($mustThrow) {
                        self::fail(sprintf(
                            '%s::%s accepted %s for parameter #%d (%s)',
                            $class,
                            $signal,
                            get_debug_type($bad),
                            $i + 1,
                            $gtype,
                        ));
                    }
                } catch (\ValueError | \TypeError | \Error | \LogicException | \Gtk4\GError) {
                    // refused, which is the point; a crash would end the process
                }
            }
        }
        $this->addToAssertionCount(1);
    }

    /**
     * Every native `vfunc_*()` with a scalar return that a public method of the same name
     * drives: a PHP subclass overriding it answers a hostile value of the declared type, and the
     * thunk has to refuse what the C type cannot hold (check_range, check_utf8) rather than hand
     * it to GTK.
     *
     * @return iterable<string, array{class-string, string, string}>
     */
    public static function vfuncReturns(): iterable
    {
        foreach (new ReflectionExtension('gtk4')->getClasses() as $class) {
            if ($class->isEnum() || $class->isInterface() || $class->isFinal()) {
                continue;
            }
            foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC) as $m) {
                $name = $m->getName();
                if (!str_starts_with($name, 'vfunc_') || $m->getDeclaringClass()->getName() !== $class->getName()) {
                    continue;
                }
                $ret = $m->getReturnType();
                if (!$ret instanceof ReflectionNamedType) {
                    continue;
                }
                if (!in_array($ret->getName(), ['int', 'string', 'float'], true)) {
                    continue;
                }
                $public = substr($name, 6);
                if (!$class->hasMethod($public) || $class->getMethod($public)->isStatic()) {
                    continue;
                }
                yield $class->getName() . '::' . $name => [$class->getName(), $name, $ret->getName()];
            }
        }
    }

    /**
     * @param class-string $class
     */
    #[DataProvider('vfuncReturns')]
    public function testHostileVfuncReturnsAreRefusedOrSurvived(string $class, string $vfunc, string $type): void
    {
        $this->sweeping = $class . '::' . $vfunc . '#return';
        $subclass = self::answeringSubclass($class, $vfunc);
        $instance = self::inventInstance($subclass);
        if ($instance === null) {
            $this->sweeping = '';
            self::markTestSkipped("$class has no constructor arguments this sweep can invent");
        }
        $method = new ReflectionMethod($class, substr($vfunc, 6));
        $args = [];
        foreach (array_slice($method->getParameters(), 0, $method->getNumberOfRequiredParameters()) as $p) {
            $args[] = self::validValueFor($p);
        }
        $hostile = match ($type) {
            'int' => [-1, PHP_INT_MAX, PHP_INT_MIN],
            'string' => ["a\0b", "\xff\xfe", ''],
            default => [INF, -INF, NAN],
        };
        foreach ($hostile as $answer) {
            $subclass::$answer = $answer;
            try {
                $this->captureHandlerException(fn() => $method->invokeArgs($instance, $args));
            } catch (\ValueError | \TypeError | \Error | \LogicException | \Gtk4\GError) {
                // the public method refused before reaching the slot: fine
            }
        }
        $this->addToAssertionCount(1);
    }

    /**
     * An eval'd subclass of $class whose $vfunc override answers with its static $answer, typed
     * as the native method declares (the engine enforces that much; the thunk the rest).
     *
     * @param class-string $class
     * @return class-string
     */
    private static function answeringSubclass(string $class, string $vfunc): string
    {
        $short = 'Answer_' . str_replace('\\', '_', $class) . '_' . $vfunc;
        $fqcn = 'PhpGtk4\\Tests\\Sweep\\' . $short;
        if (!class_exists($fqcn, false)) {
            $m = new ReflectionMethod($class, $vfunc);
            $params = [];
            foreach ($m->getParameters() as $p) {
                $t = (string) $p->getType();
                $bare = ltrim($t, '?');
                $decl = in_array($bare, ['void', 'bool', 'int', 'float', 'string', 'array', 'mixed'], true)
                    ? $t : (str_starts_with($t, '?') ? '?' : '') . '\\' . $bare;
                $params[] = "$decl \$" . $p->getName()
                    . ($p->isDefaultValueAvailable() ? ' = ' . var_export($p->getDefaultValue(), true) : '');
            }
            $ret = (string) $m->getReturnType();
            eval("namespace PhpGtk4\\Tests\\Sweep; final class $short extends \\$class {\n"
                . "    public static mixed \$answer = null;\n"
                . "    public function $vfunc(" . implode(', ', $params) . "): $ret { return self::\$answer; }\n}");
        }
        /** @var class-string $fqcn */
        return $fqcn;
    }

    /**
     * An instance of $class from invented scalar constructor arguments (the way
     * TypeDeclarationTest builds what GtkInstances does not name), or null.
     *
     * @param class-string $class
     */
    private static function inventInstance(string $class): ?object
    {
        $constructor = new ReflectionClass($class)->getConstructor();
        if ($constructor === null || !$constructor->isPublic()) {
            return null;
        }
        $arguments = [];
        foreach ($constructor->getParameters() as $p) {
            if ($p->isOptional()) {
                break;
            }
            $t = $p->getType();
            if ($t instanceof ReflectionNamedType && $t->allowsNull()) {
                $arguments[] = null;
                continue;
            }
            $value = self::validValueFor($p);
            if ($value === null) {
                return null;
            }
            $arguments[] = $value;
        }
        try {
            return new $class(...$arguments);
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * The sweep target. Everything but the window comes from {@see GtkInstances}, which the
     * getter sweep shares: a class either has a live instance in both or is skipped by both.
     *
     * @param class-string $class
     */
    private function instance(string $class): ?object
    {
        if ($class === \Gtk4\GObject::class || $class === \Gtk4\GtkWindow::class) {
            return $this->window();   // GObject: any handle; the window is torn down for us
        }
        return GtkInstances::make($class);
    }

    /**
     * Why a class has no sweep target. {@see GtkInstances::UNREACHABLE} names the ones nothing
     * can build and says why, so a skip is a documented decision rather than a shrug.
     *
     * @param class-string $class
     */
    private static function whyNoInstance(string $class): string
    {
        $reason = GtkInstances::UNREACHABLE[$class] ?? null;
        return $reason === null
            ? "$class is not instantiable"
            : "$class cannot be built: $reason";
    }

    /**
     * @param class-string $class
     */
    #[DataProvider('methods')]
    public function testWrongArgumentsThrowInsteadOfCrashing(string $class, string $method): void
    {
        $this->sweeping = $class . '::' . $method . '#arguments';
        $rm = new ReflectionMethod($class, $method);
        $target = $rm->isStatic() ? null : $this->instance($class);
        if ($target === null && !$rm->isStatic()) {
            self::markTestSkipped(self::whyNoInstance($class));
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
        // The finding is a self::fail() inside the loop, or the process not surviving it. This
        // one only states that the sweep actually probed something: every non-variadic method has
        // at least the too-many-arguments probe, so a zero here means the sweep silently did
        // nothing. A variadic one has no upper arity to violate and may have nothing to probe.
        if ($rm->isVariadic()) {
            $this->addToAssertionCount(1);
        } else {
            self::assertGreaterThan(0, $checks, "$class::$method(): the sweep probed nothing");
        }
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
        $this->sweeping = $class . '::' . $method . '#values';
        $rm = new ReflectionMethod($class, $method);
        $target = $rm->isStatic() ? null : $this->instance($class);
        if ($target === null && !$rm->isStatic()) {
            self::markTestSkipped(self::whyNoInstance($class));
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

        return class_exists($name) ? GtkInstances::plain($name) : null;
    }
}
