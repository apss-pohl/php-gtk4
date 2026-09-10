<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use PhpGtk4\Gen\Gir;
use PhpGtk4\Gen\Type;
use PhpGtk4\Gen\TypeMap;
use PhpGtk4\Gen\TypeSet;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * The type map decides what every GIR type looks like in PHP and what has to happen at the
 * boundary; before the split (2026-08-30) it was 700 lines inside a 3 200-line script and the
 * only way to ask it anything was to run the whole generator and read 340 files.
 *
 * The `gen` stage still proves the output as a whole: it regenerates in place and fails on any
 * diff. What this adds is the small, readable half - the mappings a wave actually reasons about,
 * pinned one at a time, including the two the dialogs wave introduced.
 */
final class GeneratorTypeMapTest extends TestCase
{
    private static ?TypeMap $map = null;

    /**
     * The generator is a script, not a library: including it would run it. The three files the
     * type map needs are self-contained, so they are the ones required here.
     */
    public static function setUpBeforeClass(): void
    {
        $gen = __DIR__ . '/../gen/gir';
        require_once $gen . '/config.php';
        require_once $gen . '/model.php';
        require_once $gen . '/loader.php';
        require_once $gen . '/type-map.php';
    }

    private static function map(): TypeMap
    {
        if (self::$map === null) {
            $gir = new Gir();
            foreach (['GLib-2.0', 'GObject-2.0', 'Gio-2.0', 'cairo-1.0', 'Gtk-4.0'] as $namespace) {
                $file = Gir::locate($namespace);
                if ($file === null) {
                    self::markTestSkipped("$namespace.gir is not installed (gir1.2-gtk-4.0)");
                }
                $gir->load($file);
            }
            $types = new TypeSet($gir);
            $types->handwritten['cairo.Context'] = true;
            self::$map = new TypeMap($gir, $types, self::ignoreSkips(...));
        }
        return self::$map;
    }

    /** The generator records unmappable members in gen/report.md; here they are just answers. */
    private static function ignoreSkips(): void
    {
        // The generator turns these into gen/report.md lines; here the answer alone is the point.
    }

    /** @return iterable<string, array{string, bool, ?string}> GIR type, nullable, expected PHP type */
    public static function scalarTypes(): iterable
    {
        yield 'utf8' => ['utf8', false, 'string'];
        yield 'utf8 nullable' => ['utf8', true, '?string'];
        yield 'filename' => ['filename', false, 'string'];
        yield 'gboolean' => ['gboolean', false, 'bool'];
        yield 'gdouble' => ['gdouble', false, 'float'];
        yield 'gint' => ['gint', false, 'int'];
        yield 'guint64' => ['guint64', false, 'int'];
        yield 'none' => ['none', false, 'void'];
        // Values, not handles (docs/DESIGN.md): a variant is a PHP value, bytes and a GType
        // are strings, and since the dialogs wave a GFile is the path it points at.
        yield 'GVariant' => ['GLib.Variant', false, 'mixed'];
        yield 'GBytes' => ['GLib.Bytes', false, 'string'];
        yield 'GType' => ['GObject.GType', false, 'string'];
        yield 'cairo context' => ['cairo.Context', false, 'CairoContext'];
        // Outside the closure: the member is skipped and reported, never guessed at.
        yield 'unknown type' => ['Gtk.Whatever', false, null];
    }

    #[DataProvider('scalarTypes')]
    public function testPhpTypeOfAGirType(string $gir, bool $nullable, ?string $expected): void
    {
        $type = new Type($gir, null);
        self::assertSame($expected, self::map()->phpType($type, $nullable));
    }

    /** GStrv is the one array shape that maps to a plain PHP list. */
    public function testStrvIsRecognisedByShapeNotByName(): void
    {
        // A GIR <array> without a name is 'array' (loader.php); the element decides everything.
        $strv = new Type('array', 'char**', isArray: true, element: new Type('utf8', null));
        self::assertTrue(TypeMap::isStrv($strv));
        self::assertSame('array', self::map()->phpType($strv, false));

        $counted = new Type('array', 'char**', isArray: true, element: new Type('utf8', null), lengthParam: 1);
        self::assertFalse(TypeMap::isStrv($counted), 'a counted array is not a GStrv');
    }
}
