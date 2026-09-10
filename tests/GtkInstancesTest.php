<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GtkWindow;
use ReflectionClass;
use ReflectionExtension;

/**
 * Keeps {@see GtkInstances::UNREACHABLE} honest. The list is what the two generic sweeps print
 * instead of a bare "not instantiable", so a stale line turns a skip into a lie: it claims the
 * class cannot be built when the real reason is that nobody wrote the branch.
 *
 * The other half of the guard is dynamic and lives in {@see TypeDeclarationTest}, which fails if
 * any getter ever hands out one of these classes.
 */
final class GtkInstancesTest extends GtkTestCase
{
    /** Releases the owners {@see GtkInstances} pinned for the handles it handed out. */
    protected function tearDown(): void
    {
        GtkInstances::release();
        parent::tearDown();
    }

    /** @return iterable<string, array{class-string, string}> */
    public static function unreachableClasses(): iterable
    {
        foreach (GtkInstances::UNREACHABLE as $class => $reason) {
            if (!Features::available($class)) {
                continue;   // a class of a feature this build lacks (tests/Features.php)
            }
            yield $class => [$class, $reason];
        }
    }

    /**
     * A renamed or dropped class must not leave its excuse behind.
     *
     * @param class-string $class
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('unreachableClasses')]
    public function testEveryEntryNamesARegisteredClassWithAReason(string $class, string $reason): void
    {
        $registered = array_map(
            static fn(ReflectionClass $c): string => $c->getName(),
            new ReflectionExtension('gtk4')->getClasses(),
        );
        self::assertContains(
            $class,
            $registered,
            "$class is listed as unreachable but the extension no longer registers it",
        );
        self::assertNotSame('', trim($reason), "$class needs a reason, not an empty string");
    }

    /**
     * Writing a factory branch without deleting the line would leave both claims standing.
     *
     * @param class-string $class
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('unreachableClasses')]
    public function testNothingBuildsAnUnreachableClass(string $class, string $reason): void
    {
        self::assertNull(
            GtkInstances::make($class),
            "GtkInstances builds $class, so it is not unreachable: $reason",
        );
    }

    /**
     * The gap the dynamic half cannot see, and the one that actually bit.
     *
     * {@see TypeDeclarationTest} only catches a stale excuse when some *getter* hands the class
     * out, which needs the factory to build the object holding that getter. A class nothing
     * returns keeps its excuse forever - GskColorMatrixNode kept "needs a graphene matrix and
     * vec4 (not bound)" for waves after both were bound and its constructor was public.
     *
     * A public constructor is the giveaway: the generator emits one only when every parameter is
     * a type this binding speaks, so `new` works and the class is reachable by definition. If a
     * class here grows one, either write the factory branch and delete the line, or refuse the
     * constructor in gen/skip.txt with the reason.
     *
     * @param class-string $class
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('unreachableClasses')]
    public function testAnUnreachableClassHasNoPublicConstructor(string $class, string $reason): void
    {
        // null: no constructor at all. false: private, so `new` is refused. Either is honest.
        self::assertNotSame(
            true,
            new ReflectionClass($class)->getConstructor()?->isPublic(),
            "$class has a public constructor, so PHP can build one - the excuse \"$reason\" is"
            . ' stale. Add a GtkInstances branch and drop the line.',
        );
    }

    /**
     * The third state, and the one nothing used to count. A class the factory cannot build and
     * the list does not excuse is skipped by both sweeps with "no instance this test can build"
     * - silently, since a skip is not a failure - and PangoFontMap sat there for three waves:
     * no branch, no excuse, no sweep. Every registered class is either built here or named
     * in UNREACHABLE; a class the machine cannot build (ENVIRONMENTAL) is skipped with what it
     * lacks, so the report says so instead of the guard failing on a runner without it.
     *
     * @param class-string $class
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('registeredClasses')]
    public function testEveryClassIsBuiltOrExcused(string $class): void
    {
        if (isset(GtkInstances::UNREACHABLE[$class])) {
            self::assertNull(GtkInstances::make($class), "$class is excused, yet the factory builds one");
            return;
        }
        $instance = $class === GtkWindow::class ? $this->window() : GtkInstances::make($class);
        if ($instance === null && isset(GtkInstances::ENVIRONMENTAL[$class])) {
            self::markTestSkipped("$class: " . GtkInstances::ENVIRONMENTAL[$class]);
        }
        self::assertInstanceOf(
            $class,
            $instance,
            "$class is neither built by GtkInstances nor excused in UNREACHABLE, so no sweep sees it",
        );
    }

    /** @return iterable<string, array{class-string}> */
    public static function registeredClasses(): iterable
    {
        foreach (new ReflectionExtension('gtk4')->getClasses() as $rc) {
            if ($rc->isInterface() || $rc->isEnum()) {
                continue;
            }
            /** @var class-string $name */
            $name = $rc->getName();
            yield $name => [$name];
        }
    }

    /** The list is the exception, not the rule: the table has to carry its weight. */
    public function testTheListIsSmallerThanWhatTheFactoryCovers(): void
    {
        $built = 0;
        foreach (new ReflectionExtension('gtk4')->getClasses() as $class) {
            if ($class->isEnum() || $class->isInterface()) {
                continue;
            }
            $built += GtkInstances::make($class->getName()) === null ? 0 : 1;
            GtkInstances::release();
        }
        self::assertGreaterThan(count(GtkInstances::UNREACHABLE), $built);
    }
}
