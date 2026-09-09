<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GtkButton;
use Gtk4\GtkLabel;

/** GtkButton constructors and looks (generated wave 0 API): GTK's static factories, not PHP defaults. */
final class ButtonTest extends GtkTestCase
{
    public function testFactoriesAreStaticMethods(): void
    {
        $plain = new GtkButton();
        self::assertNull($plain->get_label());
        self::assertNull($plain->get_child());

        $labelled = GtkButton::new_with_label('Go');
        self::assertSame('Go', $labelled->get_label());
        self::assertInstanceOf(GtkLabel::class, $labelled->get_child(), 'the label is a real child widget');

        $mnemonic = GtkButton::new_with_mnemonic('_Save');
        self::assertSame('_Save', $mnemonic->get_label());
        self::assertTrue($mnemonic->get_use_underline());

        $icon = GtkButton::new_from_icon_name('edit-copy');
        self::assertSame('edit-copy', $icon->get_icon_name());
        self::assertNull($icon->get_label(), 'an icon button has no label');
    }

    public function testIconReplacesLabelAndBack(): void
    {
        $b = GtkButton::new_with_label('text');
        $b->set_icon_name('edit-paste');
        self::assertSame('edit-paste', $b->get_icon_name());
        self::assertNull($b->get_label());
        $b->set_label('text again');
        self::assertSame('text again', $b->get_label());
        self::assertNull($b->get_icon_name());
    }

    public function testFrameAndUnderline(): void
    {
        $b = new GtkButton();
        self::assertTrue($b->get_has_frame());
        $b->set_has_frame(false);
        self::assertFalse($b->get_has_frame());
        $b->set_use_underline(true);
        self::assertTrue($b->get_use_underline());
        $b->has_frame = true;
        self::assertTrue($b->get_has_frame(), 'the GObject property is the same state');
    }
}
