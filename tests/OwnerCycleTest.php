<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkScale;
use Gtk4\GtkTextBuffer;
use Gtk4\GtkTextIter;
use PhpGtk4\Tests\Subclass\HelloLabel;

/**
 * A handle that holds its owner (object_hold_owner, BOXED_OWNERS) closes a cycle through C:
 * parent handle -> parent GObject -> child GObject -> (held) child handle -> owner -> parent
 * handle. Zend only ever saw the last edge, so a parent and the child it handed out leaked
 * until request shutdown once the script dropped both. The owner's get_gc now reports the held
 * dependents it is responsible for, and a boxed value holds its owner's *handle*.
 */
final class OwnerCycleTest extends GtkTestCase
{
    /** @return array{\WeakReference<GtkBox>, \WeakReference<GtkButton>} */
    private static function droppedTree(): array
    {
        $box = new GtkBox(GtkOrientation::Vertical, 0);
        $box->append(new GtkButton());
        $child = $box->get_first_child();
        self::assertInstanceOf(GtkButton::class, $child);

        return [\WeakReference::create($box), \WeakReference::create($child)];
    }

    public function testADroppedParentAndItsHeldChildAreCollected(): void
    {
        [$box, $child] = self::droppedTree();
        self::assertNotNull($box->get(), 'the cycle keeps both alive until a collection');

        gc_collect_cycles();

        self::assertNull($box->get());
        self::assertNull($child->get());
    }

    public function testACompositeWidgetAndItsPrivateChildAreCollected(): void
    {
        $scale = new GtkScale(GtkOrientation::Horizontal, null);
        $gizmo = $scale->get_first_child();
        self::assertNotNull($gizmo, 'a scale is composite: it hands out a private child');
        $weakScale = \WeakReference::create($scale);
        $weakGizmo = \WeakReference::create($gizmo);
        unset($scale, $gizmo);

        gc_collect_cycles();

        self::assertNull($weakScale->get());
        self::assertNull($weakGizmo->get());
    }

    /** The owner still reachable: the child handle, and the state a PHP subclass keeps on it, stay. */
    public function testAReachableParentKeepsTheChildAndItsState(): void
    {
        $box = new GtkBox(GtkOrientation::Vertical, 0);
        $box->append(new HelloLabel());
        $child = $box->get_first_child();
        self::assertInstanceOf(HelloLabel::class, $child);
        $child->set_label('changed');
        unset($child);

        gc_collect_cycles();

        $again = $box->get_first_child();
        self::assertInstanceOf(HelloLabel::class, $again);
        self::assertSame('changed', $again->get_label());
    }

    /**
     * The hold is only the owner's to report while the owner is the parent: a child moved to
     * another container has its GTK reference from there, so the old owner claims no edge and a
     * collection must not take the child's handle. (The child still names the old parent as its
     * owner, which keeps that handle alive for as long as the child's - a stale but safe hold.)
     */
    public function testAChildMovedElsewhereIsNotCollectedWithItsOldOwner(): void
    {
        $old = new GtkBox(GtkOrientation::Vertical, 0);
        $old->append(new HelloLabel());
        $child = $old->get_first_child();
        self::assertInstanceOf(HelloLabel::class, $child);
        $child->set_label('moved');
        $new = new GtkBox(GtkOrientation::Vertical, 0);
        $old->remove($child);
        $new->append($child);
        unset($old, $child);

        gc_collect_cycles();

        $again = $new->get_first_child();
        self::assertInstanceOf(HelloLabel::class, $again);
        self::assertSame('moved', $again->get_label());
    }

    /** A subclass storing the child it got from itself: the same cycle plus a property edge. */
    public function testASubclassStoringItsOwnChildIsCollected(): void
    {
        $box = new class (GtkOrientation::Vertical, 0) extends GtkBox {
            public ?GtkButton $row = null;
        };
        $box->append(new GtkButton());
        $row = $box->get_first_child();
        self::assertInstanceOf(GtkButton::class, $row);
        $box->row = $row;
        $weak = \WeakReference::create($box);
        unset($box, $row);

        gc_collect_cycles();

        self::assertNull($weak->get());
    }

    /** A boxed value's owner is a handle: a buffer storing one of its own iters is collectable. */
    public function testABufferStoringItsOwnIterIsCollected(): void
    {
        $buffer = new class (null) extends GtkTextBuffer {
            public ?GtkTextIter $cursor = null;
        };
        $buffer->set_text('hello');
        $buffer->cursor = $buffer->get_start_iter();
        $weak = \WeakReference::create($buffer);
        unset($buffer);

        gc_collect_cycles();

        self::assertNull($weak->get());
    }

    /** ... and still keeps the buffer alive for as long as the iter lives. */
    public function testAnIterKeepsItsBufferAlive(): void
    {
        $buffer = new GtkTextBuffer(null);
        $buffer->set_text('hello world');
        $iter = $buffer->get_start_iter();
        $weak = \WeakReference::create($buffer);
        unset($buffer);
        gc_collect_cycles();

        self::assertNotNull($weak->get());
        $iter->forward_word_end();
        self::assertSame(5, $iter->get_offset());
    }
}
