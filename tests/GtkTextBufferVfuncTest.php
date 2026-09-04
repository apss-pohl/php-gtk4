<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GdkTexture;
use Gtk4\GtkTextTag;
use PhpGtk4\Tests\Subclass\RecordingTextBuffer;

/**
 * The GtkTextBuffer class-struct slots, driven from the ordinary API: every generated vfunc
 * thunk is a GTK -> PHP entry point that only runs when a subclass overrides the slot, and the
 * native vfunc_*() next to it is what parent:: chains back into. Both are invisible to the
 * generic sweeps, which never subclass anything.
 */
final class GtkTextBufferVfuncTest extends GtkTestCase
{
    private function buffer(): RecordingTextBuffer
    {
        $buffer = new RecordingTextBuffer();
        $buffer->calls = [];
        return $buffer;
    }

    public function testInsertingTextRoutesThroughTheSlots(): void
    {
        $buffer = $this->buffer();
        $buffer->set_text('hello world');

        self::assertContains('insert_text', $buffer->calls);
        self::assertContains('changed', $buffer->calls);
        self::assertContains('modified_changed', $buffer->calls, 'the buffer went from clean to modified');
        self::assertSame('hello world', $buffer->get_text(
            $buffer->get_start_iter(),
            $buffer->get_end_iter(),
            false,
        ), 'chaining to parent:: still performs the insert');
    }

    public function testTagsRouteThroughApplyAndRemove(): void
    {
        $buffer = $this->buffer();
        $buffer->set_text('hello world');
        $tag = new GtkTextTag('bold');
        self::assertTrue($buffer->get_tag_table()->add($tag));

        $start = $buffer->get_start_iter();
        $end = $buffer->get_end_iter();
        $buffer->calls = [];
        $buffer->apply_tag($tag, $start, $end);
        $buffer->remove_tag($tag, $start, $end);

        self::assertSame(['apply_tag', 'remove_tag'], $buffer->calls);
    }

    public function testMarksRouteThroughMarkSetAndMarkDeleted(): void
    {
        $buffer = $this->buffer();
        $buffer->set_text('hello world');
        $buffer->calls = [];

        $mark = $buffer->create_mark('here', $buffer->get_start_iter(), true);
        self::assertContains('mark_set', $buffer->calls);

        $buffer->move_mark($mark, $buffer->get_end_iter());
        $buffer->delete_mark($mark);
        self::assertContains('mark_deleted', $buffer->calls);
    }

    public function testUserActionsAndDeleteRoutesThroughTheSlots(): void
    {
        $buffer = $this->buffer();
        $buffer->set_text('hello world');
        $buffer->calls = [];

        $buffer->begin_user_action();
        $buffer->delete($buffer->get_start_iter(), $buffer->get_end_iter());
        $buffer->end_user_action();

        self::assertSame(['begin_user_action', 'delete_range', 'changed', 'end_user_action'], $buffer->calls);
        self::assertSame(0, $buffer->get_char_count());
    }

    public function testUndoAndRedoRouteThroughTheSlots(): void
    {
        $buffer = $this->buffer();
        $buffer->set_enable_undo(true);
        // Not set_text(): GTK wraps that one in begin/end_irreversible_action, so it leaves
        // nothing to undo. An ordinary insert is what the history records.
        $buffer->insert($buffer->get_start_iter(), 'hello world');
        self::assertTrue($buffer->get_can_undo());

        $buffer->calls = [];
        $buffer->undo();
        self::assertContains('undo', $buffer->calls);
        self::assertSame(0, $buffer->get_char_count());

        $buffer->calls = [];
        $buffer->redo();
        self::assertContains('redo', $buffer->calls);
        self::assertSame(11, $buffer->get_char_count());
    }

    public function testInsertPaintableRoutesThroughTheSlot(): void
    {
        $buffer = $this->buffer();
        $texture = GdkTexture::new_from_bytes(PngFixture::red(2, 1));
        $buffer->calls = [];

        $buffer->insert_paintable($buffer->get_start_iter(), $texture);

        self::assertContains('insert_paintable', $buffer->calls);
        self::assertSame(1, $buffer->get_char_count(), 'a paintable counts as one character');
    }

    public function testModifiedChangedRoutesThroughTheSlot(): void
    {
        $buffer = $this->buffer();
        $buffer->set_text('hello world');
        $buffer->calls = [];

        $buffer->set_modified(false);

        self::assertSame(['modified_changed'], $buffer->calls);
        self::assertFalse($buffer->get_modified());
    }
}
