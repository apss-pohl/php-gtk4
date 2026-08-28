<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GApplicationFlags;
use Gtk4\GtkAlign;
use Gtk4\GtkApplication;
use Gtk4\GtkButton;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;

/** GEnum <-> PHP enums, GFlags <-> constant classes (src/core/enums). */
final class EnumTest extends GtkTestCase
{
    public function testEnumCasesMirrorTheCValues(): void
    {
        self::assertSame(0, GtkAlign::Fill->value);
        self::assertSame(2, GtkAlign::End->value);
        self::assertSame(5, GtkAlign::BaselineCenter->value);
        self::assertSame(1, GtkOrientation::Vertical->value);
        self::assertSame(GtkAlign::End, GtkAlign::from(2));
    }

    public function testTypedMethodsUseEnums(): void
    {
        $b = new GtkButton();
        self::assertSame(GtkAlign::Fill, $b->get_halign(), 'GTK default');
        $b->set_halign(GtkAlign::End);
        $b->set_valign(GtkAlign::Center);
        self::assertSame(GtkAlign::End, $b->get_halign());
        self::assertSame(GtkAlign::Center, $b->get_valign());
    }

    public function testPropertiesReturnEnumCasesAndAcceptCasesOrInts(): void
    {
        $b = new GtkButton();
        $b->halign = GtkAlign::Start;
        self::assertSame(GtkAlign::Start, $b->halign);
        self::assertSame(GtkAlign::Start, $b->get_property('halign'));
        $b->set_property('valign', 3);  // raw int still accepted for the C-level value
        self::assertSame(GtkAlign::Center, $b->valign);
        $b->set_property('valign', GtkAlign::BaselineFill);
        self::assertSame(GtkAlign::BaselineFill, $b->get_valign());
    }

    public function testWrongEnumClassIsATypeError(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/expected Gtk4\\\\GtkAlign or int/');
        new GtkButton()->set_property('halign', GtkOrientation::Vertical);
    }

    public function testTypedSetterRejectsInts(): void
    {
        // Typed methods take the enum only (the property path is the lenient one).
        $this->expectException(\TypeError::class);
        self::opaque([new GtkButton(), 'set_halign'])(2);
    }

    public function testGeneratedEnumPropertiesUseTheEnum(): void
    {
        // GtkLabel:justify is GtkJustification - a generated PHP enum since wave 0; a property
        // read yields the case, a write accepts the case or the plain int.
        $l = new GtkLabel();
        self::assertSame(\Gtk4\GtkJustification::Left, $l->get_property('justify'));
        $l->set_property('justify', 1);
        self::assertSame(\Gtk4\GtkJustification::Right, $l->get_property('justify'));
        $l->set_property('justify', \Gtk4\GtkJustification::Center);
        self::assertSame(\Gtk4\GtkJustification::Center, $l->get_property('justify'));
    }

    public function testFlagsAreConstantsFromTheHeaders(): void
    {
        self::assertSame(0, GApplicationFlags::DEFAULT_FLAGS);
        self::assertSame(1 << 5, GApplicationFlags::NON_UNIQUE);
        self::assertSame(1 << 2, GApplicationFlags::HANDLES_OPEN);
        $flags = GApplicationFlags::NON_UNIQUE | GApplicationFlags::HANDLES_OPEN;
        $app = new GtkApplication(null, $flags);
        self::assertSame($flags, $app->get_property('flags'));
    }

    public function testVarDumpShowsTheCase(): void
    {
        $b = new GtkButton();
        $b->halign = GtkAlign::End;
        ob_start();
        var_dump($b);
        $dump = (string) ob_get_clean();
        self::assertStringContainsString('enum(Gtk4\GtkAlign::End)', $dump);
    }
}
