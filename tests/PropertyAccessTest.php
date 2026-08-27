<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

/** src/core/object handlers: GObject properties as PHP properties, var_dump(). */
final class PropertyAccessTest extends GtkTestCase
{
    public function testReadAndWriteAsPhpProperty(): void
    {
        $w = $this->window();
        $w->title = 'via property';
        self::assertSame('via property', $w->get_title());
        self::assertSame('via property', $w->title);
    }

    public function testUnderscoreMapsToDash(): void
    {
        $w = $this->window();
        $w->default_width = 321;
        self::assertSame(321, $w->get_property('default-width'));
        self::assertSame(321, $w->default_width);
    }

    public function testCompoundAssignmentWritesThrough(): void
    {
        // Without get_property_ptr_ptr these would create a shadow dynamic property and drop the write.
        $w = $this->window();
        $w->default_width = 100;
        $w->default_width++;
        self::assertSame(101, $w->get_property('default-width'));
        $w->default_width += 9;
        self::assertSame(110, $w->default_width);
        $w->title = 'a';
        $w->title .= 'b';
        self::assertSame('ab', $w->get_title());
    }

    public function testUnsetGObjectPropertyThrows(): void
    {
        $w = $this->window();
        $this->expectException(\Error::class);
        $this->expectExceptionMessage('Cannot unset GObject property Gtk4\GtkWindow::$title');
        unset($w->title);
    }

    public function testIssetAndEmpty(): void
    {
        $w = $this->window();
        $w->title = null;
        self::assertFalse(isset($w->title), 'null property -> isset false');
        $w->title = 'x';
        self::assertTrue(isset($w->title));
        self::assertFalse(isset($w->no_such_property));
    }

    public function testObjectValuedPropertyRoundTrip(): void
    {
        $w = $this->window();
        $other = $this->window();
        $w->transient_for = $other;
        self::assertSame($other, $w->transient_for);
        $w->transient_for = null;
        self::assertNull($w->transient_for);
    }

    public function testWriteWrongTypeThrows(): void
    {
        $this->expectException(\TypeError::class);
        $this->window()->{self::dynamicName('transient_for')} = 'not an object';
    }

    /** Hides a property name from static analysis (declared return type only, no literal). */
    private static function dynamicName(string $name): string
    {
        return $name;
    }

    public function testUnknownPropertyFallsBackToStandardBehaviour(): void
    {
        $w = $this->window();
        $name = self::dynamicName('not_a_gobject_property');
        // Dynamic properties are deprecated on non-#[AllowDynamicProperties] classes in
        // PHP 8.2+ - the standard handlers must still be reached, not our GObject path.
        $deprecated = null;
        set_error_handler(function (int $no, string $msg) use (&$deprecated): bool {
            $deprecated = $msg;
            return true;
        }, E_DEPRECATED);
        try {
            $w->{$name} = 1;
        } finally {
            restore_error_handler();
        }
        self::assertNotNull($deprecated);
        self::assertStringContainsString('dynamic property', $deprecated);
    }

    public function testVarDumpShowsGObjectProperties(): void
    {
        $w = $this->window();
        $w->set_title('dumped');
        ob_start();
        var_dump($w);
        $dump = (string) ob_get_clean();
        self::assertStringContainsString('Gtk4\GtkWindow', $dump);
        self::assertStringContainsString('["title"]=>', $dump);
        self::assertStringContainsString('string(6) "dumped"', $dump);
        self::assertStringContainsString('["resizable"]=>', $dump);
        self::assertStringContainsString('["css-classes"]=>', $dump, 'GStrv is shown as an array');
    }

    public function testPrintRWorksToo(): void
    {
        $w = $this->window();
        $w->set_title('pr');
        $out = print_r($w, true);
        self::assertStringContainsString('[title] => pr', $out);
    }
}
