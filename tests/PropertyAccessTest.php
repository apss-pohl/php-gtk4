<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use PhpGtk4\Tests\Subclass\ShadowingWindow;

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

    public function testDeclaredPhpPropertyWinsOverTheGObjectOne(): void
    {
        // A NotesWindow with `private GtkEntry $title` once wrote GtkWindow's gchararray title.
        $w = new ShadowingWindow();
        $w->set_title('gtk');
        self::assertSame('php', $w->title, 'the declared property answers, not the GObject');
        self::assertSame('gtk', $w->get_title(), 'and the GObject property is untouched');
        $w->title = 'changed';
        $w->title .= '!';
        self::assertSame('changed!', $w->title, 'compound assignment stays on the PHP slot');
        self::assertSame('gtk', $w->get_title());
        $name = self::dynamicName('title');   // the isset()/unset() below, past static analysis
        self::assertTrue(isset($w->{$name}));
        self::assertTrue(property_exists($w, $name));

        // A private one, from inside the class: the ++ in setWidth() would have been a GObject write.
        $w->set_default_size(400, 300);
        self::assertSame(7, $w->width());
        $w->setWidth(41);
        self::assertSame(42, $w->width());
        self::assertSame(400, $w->get_property('default-width'), 'GTK never saw 41 or 42');

        // unset() is PHP's on a declared property, not the GObject refusal.
        unset($w->{$name});
        self::assertFalse(isset($w->{$name}));
        $this->expectException(\Error::class);
        $this->expectExceptionMessage('must not be accessed before initialization');
        $w->destroy();
        self::assertSame('unreachable', $w->{$name});
    }

    public function testDeclaredPropertyKeepsItsType(): void
    {
        $w = new ShadowingWindow();
        try {
            $this->expectException(\TypeError::class);
            $w->{self::dynamicName('title')} = 12;
        } finally {
            $w->destroy();
        }
    }

    public function testVarDumpShowsPhpPropertiesAndSkipsWhatTheyShadow(): void
    {
        $w = new ShadowingWindow();
        $w->set_title('gtk');
        ob_start();
        var_dump($w);
        $dump = (string) ob_get_clean();
        $w->destroy();
        self::assertStringContainsString('["title"]=>', $dump);
        self::assertStringContainsString('string(3) "php"', $dump, 'the PHP value, once');
        self::assertStringNotContainsString('"gtk"', $dump, 'the shadowed GObject title is not listed');
        self::assertStringContainsString(
            '["default_width":"PhpGtk4\\Tests\\Subclass\\ShadowingWindow":private]=>',
            $dump,
        );
        self::assertStringNotContainsString('["default-width"]=>', $dump);
        self::assertStringContainsString('["resizable"]=>', $dump, 'the rest of the GObject properties follow');
    }

    public function testPrintRWorksToo(): void
    {
        $w = $this->window();
        $w->set_title('pr');
        $out = print_r($w, true);
        self::assertStringContainsString('[title] => pr', $out);
    }
}
