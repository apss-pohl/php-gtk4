<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GtkBaselinePosition;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientable;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;

/**
 * GtkBox is the only container bound so far: GTK 4 dropped GtkContainer, and a
 * window or a button holds exactly one child, so this is what makes a real
 * layout possible at all.
 */
final class BoxTest extends GtkTestCase
{
    public function testDefaultsToAHorizontalBoxWithNoSpacing(): void
    {
        $box = new GtkBox(GtkOrientation::Horizontal, 0);
        self::assertSame(GtkOrientation::Horizontal, $box->get_orientation());
        self::assertSame(0, $box->get_spacing());
        self::assertFalse($box->get_homogeneous());
        self::assertSame([], $box->get_children());
    }

    public function testConstructorTakesOrientationAndSpacing(): void
    {
        $box = new GtkBox(GtkOrientation::Vertical, 12);
        self::assertSame(GtkOrientation::Vertical, $box->get_orientation());
        self::assertSame(12, $box->get_spacing());
    }

    public function testAppendAndPrependOrderTheChildren(): void
    {
        $box = new GtkBox(GtkOrientation::Horizontal, 0);
        $first = new GtkLabel('first');
        $second = new GtkLabel('second');
        $zeroth = new GtkLabel('zeroth');

        $box->append($first);
        $box->append($second);
        $box->prepend($zeroth);

        self::assertSame([$zeroth, $first, $second], $box->get_children());
        // Handles are identities: wrapping the same C object gives the same PHP object.
        self::assertSame($box, $first->get_parent());
    }

    public function testInsertChildAfterPlacesANewChild(): void
    {
        $box = new GtkBox(GtkOrientation::Horizontal, 0);
        $a = new GtkLabel('a');
        $b = new GtkLabel('b');
        $box->append($a);
        $box->append($b);

        $box->insert_child_after(new GtkLabel('between'), $a);
        self::assertSame(['a', 'between', 'b'], self::texts($box));

        $box->insert_child_after(new GtkLabel('first'), null);   // null = at the front
        self::assertSame(['first', 'a', 'between', 'b'], self::texts($box));
    }

    /**
     * GTK inserts, it never reparents: gtk_box_append() and friends assert that the
     * child has no parent. Without a guard that is a Gtk-CRITICAL on stderr and a
     * silently ignored call - it has to be a PHP error.
     */

    public function testMovingAChildMeansRemoveThenInsert(): void
    {
        $box = new GtkBox(GtkOrientation::Horizontal, 0);
        $a = new GtkLabel('a');
        $b = new GtkLabel('b');
        $c = new GtkLabel('c');
        foreach ([$a, $b, $c] as $child) {
            $box->append($child);
        }

        $box->remove($c);
        $box->insert_child_after($c, $a);
        self::assertSame(['a', 'c', 'b'], self::texts($box));
    }

    /** @return list<string> */
    private static function texts(GtkBox $box): array
    {
        return array_map(
            static fn(GtkWidget $child): string => $child instanceof GtkLabel ? $child->get_text() : '?',
            $box->get_children(),
        );
    }

    public function testRemoveDropsTheChild(): void
    {
        $box = new GtkBox(GtkOrientation::Horizontal, 0);
        $keep = new GtkLabel('keep');
        $drop = GtkButton::new_with_label('drop');
        $box->append($keep);
        $box->append($drop);

        $box->remove($drop);
        self::assertSame([$keep], $box->get_children());
        self::assertNull($drop->get_parent());
    }



    public function testSpacingAndHomogeneousRoundTrip(): void
    {
        $box = new GtkBox(GtkOrientation::Horizontal, 0);
        $box->set_spacing(8);
        $box->set_homogeneous(true);
        self::assertSame(8, $box->get_spacing());
        self::assertTrue($box->get_homogeneous());

        // ...and as PHP properties, like every other GObject property.
        $box->spacing = 3;
        $box->homogeneous = false;
        self::assertSame(3, $box->get_spacing());
        self::assertFalse($box->get_homogeneous());
    }



    public function testOrientationRoundTrips(): void
    {
        $box = new GtkBox(GtkOrientation::Horizontal, 0);
        $box->set_orientation(GtkOrientation::Vertical);
        self::assertSame(GtkOrientation::Vertical, $box->get_orientation());
        self::assertSame(GtkOrientation::Vertical, $box->orientation);
    }

    public function testNestedBoxesAreJustWidgets(): void
    {
        $outer = new GtkBox(GtkOrientation::Vertical, 6);
        $inner = new GtkBox(GtkOrientation::Horizontal, 2);
        $outer->append($inner);
        $inner->append(new GtkLabel('deep'));

        self::assertSame([$inner], $outer->get_children());
        self::assertInstanceOf(GtkWidget::class, $outer->get_children()[0]);
        self::assertCount(1, $inner->get_children());
    }

    public function testAWindowCanFinallyHoldMoreThanOneWidget(): void
    {
        $win = $this->window();
        $box = new GtkBox(GtkOrientation::Vertical, 4);
        $box->append(new GtkLabel('header'));
        $box->append(GtkButton::new_with_label('action'));
        $box->append(new GtkLabel('footer'));
        $win->set_child($box);

        self::assertSame($box, $win->get_child());
        self::assertCount(3, $box->get_children());
    }

    public function testExpandFlagsRoundTrip(): void
    {
        $child = new GtkLabel('stretchy');
        self::assertFalse($child->get_hexpand());
        $child->set_hexpand(true);
        $child->set_vexpand(true);
        self::assertTrue($child->get_hexpand());
        self::assertTrue($child->get_vexpand());
        self::assertTrue($child->hexpand);
        $child->vexpand = false;
        self::assertFalse($child->get_vexpand());
    }

    public function testOrientableInterfaceAndBaseline(): void
    {
        $box = new GtkBox(GtkOrientation::Horizontal, 0);
        self::assertInstanceOf(GtkOrientable::class, $box, 'the GIR interface is a PHP interface');
        $box->set_orientation(GtkOrientation::Vertical);
        self::assertSame(GtkOrientation::Vertical, $box->get_orientation());
        $box->set_baseline_position(GtkBaselinePosition::Bottom);
        self::assertSame(GtkBaselinePosition::Bottom, $box->get_baseline_position());
    }

    public function testReorderChildAfter(): void
    {
        $box = new GtkBox(GtkOrientation::Horizontal, 0);
        $a = new GtkLabel();
        $b = new GtkLabel();
        $c = new GtkLabel();
        foreach ([$a, $b, $c] as $child) {
            $box->append($child);
        }
        $box->reorder_child_after($a, $c);      // a moves after c
        self::assertSame([$b, $c, $a], $box->get_children());
        $box->reorder_child_after($a, null);    // null sibling = first
        self::assertSame([$a, $b, $c], $box->get_children());
    }
}
