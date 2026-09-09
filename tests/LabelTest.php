<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GtkButton;
use Gtk4\GtkJustification;
use Gtk4\GtkLabel;
use Gtk4\PangoEllipsizeMode;

/** GtkLabel beyond text in / text out: markup, mnemonics, layout enums (generated wave 0 API). */
final class LabelTest extends GtkTestCase
{
    public function testMarkupIsStrippedFromText(): void
    {
        $l = new GtkLabel();
        $l->set_use_markup(true);
        $l->set_label('<b>bold</b> and plain');
        self::assertTrue($l->get_use_markup());
        self::assertSame('<b>bold</b> and plain', $l->get_label(), 'get_label() keeps the markup');
        self::assertSame('bold and plain', $l->get_text(), 'get_text() is what is displayed');
    }

    public function testMnemonicKeyvalComesFromTheUnderscore(): void
    {
        $l = GtkLabel::new_with_mnemonic('_Quit');
        self::assertSame(ord('Q'), $l->get_mnemonic_keyval(), 'GDK_KEY_Q is the ASCII code of the letter as written');
        self::assertSame('Quit', $l->get_text(), 'the underscore is not part of the text');

        $target = new GtkButton();
        $l->set_mnemonic_widget($target);
        self::assertSame($target, $l->get_mnemonic_widget());
        $l->set_mnemonic_widget(null);
        self::assertNull($l->get_mnemonic_widget());
    }

    public function testLayoutEnumsRoundTrip(): void
    {
        $l = new GtkLabel();
        $l->set_justify(GtkJustification::Right);
        self::assertSame(GtkJustification::Right, $l->get_justify());
        $l->set_ellipsize(PangoEllipsizeMode::Middle);
        self::assertSame(PangoEllipsizeMode::Middle, $l->get_ellipsize(), 'a Pango enum works like a GTK one');
        $l->set_xalign(0.25);
        self::assertEqualsWithDelta(0.25, $l->get_xalign(), 1e-6);
        $l->set_lines(3);
        self::assertSame(3, $l->get_lines());
        // property access sees the same enum instance
        self::assertSame(GtkJustification::Right, $l->justify);
    }

    public function testWrapAndWidthChars(): void
    {
        $l = new GtkLabel();
        self::assertFalse($l->get_wrap());
        $l->set_wrap(true);
        $l->set_max_width_chars(12);
        $l->set_width_chars(4);
        self::assertTrue($l->get_wrap());
        self::assertSame(12, $l->get_max_width_chars());
        self::assertSame(4, $l->get_width_chars());
    }
}
