<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GtkTextBuffer;
use Gtk4\GtkTextIter;
use Gtk4\GtkTextMark;
use Gtk4\GtkTextTag;
use Gtk4\GtkTextTagTable;
use PhpGtk4\Tests\Subclass\ShoutingBuffer;

/**
 * GtkTextBuffer beyond the smoke round trips: text in/out with iters, the byte-count
 * guard of the insert family (gen/overrides/Gtk.TextBuffer.*), marks, tags, undo, the
 * insert-text vfunc on a PHP subclass, and that a stale iterator is survivable.
 */
final class GtkTextBufferTest extends GtkTestCase
{
    public function testSetTextAndGetTextRoundTrip(): void
    {
        $b = new GtkTextBuffer();
        $b->set_text("first line\nsecond line");
        self::assertSame("first line\nsecond line", $b->get_text($b->get_start_iter(), $b->get_end_iter(), true));
        self::assertSame(22, $b->get_char_count());
        self::assertSame(2, $b->get_line_count());
    }

    public function testSetTextHonoursAnExplicitByteCount(): void
    {
        $b = new GtkTextBuffer();
        $b->set_text('abcdef', 3);
        self::assertSame('abc', $b->get_text($b->get_start_iter(), $b->get_end_iter(), true));
        $b->set_text('abcdef', 0);
        self::assertSame(0, $b->get_char_count(), 'a zero count clears the buffer');
    }

    public function testByteCountPastTheStringIsRefused(): void
    {
        $b = new GtkTextBuffer();
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('byte length');
        $b->set_text('ab', 3);
    }

    public function testNegativeByteCountOtherThanMinusOneIsRefused(): void
    {
        $b = new GtkTextBuffer();
        $this->expectException(\ValueError::class);
        $b->set_text('ab', -2);
    }

    public function testByteCountInsideAUtf8CharacterIsRefused(): void
    {
        $b = new GtkTextBuffer();
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('UTF-8');
        $b->set_text('€uro', 1);   // the euro sign is three bytes
    }

    public function testInsertRevalidatesTheIterToTheEndOfTheInsertion(): void
    {
        $b = new GtkTextBuffer();
        $b->set_text('ad');
        $iter = $b->get_iter_at_offset(1);
        $b->insert($iter, 'bc');
        self::assertSame('abcd', $b->get_text($b->get_start_iter(), $b->get_end_iter(), true));
        self::assertSame(3, $iter->get_offset(), 'the iter now points behind the inserted text');
    }

    public function testInsertFamilyGuardsItsByteCount(): void
    {
        $b = new GtkTextBuffer();
        $b->set_text('x');
        $cases = [
            fn() => $b->insert($b->get_end_iter(), 'ab', 5),
            fn() => $b->insert_at_cursor('ab', 5),
            fn() => $b->insert_interactive($b->get_end_iter(), 'ab', 5, true),
            fn() => $b->insert_interactive_at_cursor('ab', 5, true),
            fn() => $b->insert_markup($b->get_end_iter(), '<b>a</b>', 99),
        ];
        foreach ($cases as $i => $case) {
            try {
                $case();
                self::fail("case $i accepted a byte count past the string");
            } catch (\ValueError) {
                // the guard named the argument; nothing was inserted
            }
        }
        self::assertSame('x', $b->get_text($b->get_start_iter(), $b->get_end_iter(), true));
    }

    public function testInsertInteractiveRespectsEditability(): void
    {
        $b = new GtkTextBuffer();
        $b->set_text('ro');
        self::assertFalse($b->insert_interactive($b->get_end_iter(), 'x', -1, false), 'not editable: refused');
        self::assertTrue($b->insert_interactive($b->get_end_iter(), 'x', -1, true));
        self::assertSame('rox', $b->get_text($b->get_start_iter(), $b->get_end_iter(), true));
    }

    public function testInsertMarkupAppliesPangoMarkup(): void
    {
        $b = new GtkTextBuffer();
        $iter = $b->get_start_iter();
        $b->insert_markup($iter, '<b>bold</b> plain');
        self::assertSame('bold plain', $b->get_text($b->get_start_iter(), $b->get_end_iter(), true));
        self::assertNotSame([], $b->get_start_iter()->get_tags(), 'the markup became a tag');
    }

    public function testMarksTrackTheirPositionAcrossEdits(): void
    {
        $b = new GtkTextBuffer();
        $b->set_text('abc');
        $mark = $b->create_mark('here', $b->get_end_iter(), false);
        self::assertInstanceOf(GtkTextMark::class, $mark);
        self::assertSame('here', $mark->get_name());
        self::assertSame($mark, $b->get_mark('here'), 'wrap() returns the same handle');
        self::assertSame(3, $b->get_iter_at_mark($mark)->get_offset());

        $b->insert($b->get_start_iter(), 'xy');
        self::assertSame(5, $b->get_iter_at_mark($mark)->get_offset(), 'the mark moved with the text');

        $b->move_mark($mark, $b->get_start_iter());
        self::assertSame(0, $b->get_iter_at_mark($mark)->get_offset());

        $b->delete_mark($mark);
        self::assertTrue($mark->get_deleted());
        self::assertNull($b->get_mark('here'));
    }

    public function testAnonymousMarkAndDeleteByName(): void
    {
        $b = new GtkTextBuffer();
        $b->set_text('abc');
        $anon = $b->create_mark(null, $b->get_start_iter(), true);
        self::assertNull($anon->get_name());
        self::assertTrue($anon->get_left_gravity());

        $named = $b->create_mark('n', $b->get_end_iter(), false);
        $b->delete_mark_by_name('n');
        self::assertTrue($named->get_deleted());
    }

    public function testTagsApplyAndRemoveOverRanges(): void
    {
        $table = new GtkTextTagTable();
        $tag = new GtkTextTag('emph');
        self::assertTrue($table->add($tag));
        $b = new GtkTextBuffer($table);
        self::assertSame($table, $b->get_tag_table());

        $b->set_text('hello world');
        $b->apply_tag($tag, $b->get_iter_at_offset(0), $b->get_iter_at_offset(5));
        self::assertTrue($b->get_iter_at_offset(2)->has_tag($tag));
        self::assertFalse($b->get_iter_at_offset(7)->has_tag($tag));

        $b->remove_tag_by_name('emph', $b->get_start_iter(), $b->get_end_iter());
        self::assertFalse($b->get_iter_at_offset(2)->has_tag($tag));
    }

    public function testSelectionAndBounds(): void
    {
        $b = new GtkTextBuffer();
        $b->set_text('abcdef');
        self::assertNull($b->get_selection_bounds(), 'no selection: null, not an empty range');
        $b->select_range($b->get_iter_at_offset(1), $b->get_iter_at_offset(4));
        self::assertTrue($b->get_has_selection());
        $bounds = $b->get_selection_bounds();
        self::assertIsArray($bounds);
        [$start, $end] = $bounds;
        self::assertSame([1, 4], [$start->get_offset(), $end->get_offset()]);
        [$s, $e] = $b->get_bounds();
        self::assertSame([0, 6], [$s->get_offset(), $e->get_offset()]);
    }

    public function testUndoRedo(): void
    {
        $b = new GtkTextBuffer();
        $b->set_enable_undo(true);
        $b->begin_user_action();
        $b->insert_at_cursor('typed');
        $b->end_user_action();
        self::assertTrue($b->get_can_undo());
        $b->undo();
        self::assertSame(0, $b->get_char_count());
        self::assertTrue($b->get_can_redo());
        $b->redo();
        self::assertSame('typed', $b->get_text($b->get_start_iter(), $b->get_end_iter(), true));
    }

    public function testChangedSignalFires(): void
    {
        $b = new GtkTextBuffer();
        $b->connect('changed', $this->latch());
        $b->set_text('x');
        self::assertTrue($this->latched(), 'set_text emits changed');
    }

    public function testStaleIterSurvivesTheBufferChangingUnderIt(): void
    {
        $b = new GtkTextBuffer();
        $b->set_text('some text');
        $stale = $b->get_end_iter();
        $b->set_text('changed');
        // GTK warns ("Invalid text buffer iterator") and answers with a safe value; the
        // process must survive - iterators are snapshots, marks track positions.
        self::assertGreaterThanOrEqual(0, $stale->get_offset());
    }

    public function testPhpSubclassOverridesTheInsertTextSlot(): void
    {
        $b = new ShoutingBuffer(null);
        $b->set_text('quiet words');
        self::assertSame('QUIET WORDS', $b->get_text($b->get_start_iter(), $b->get_end_iter(), true));
    }

    public function testGetIterAtLineOutOfRangeIsNull(): void
    {
        $b = new GtkTextBuffer();
        $b->set_text("a\nb");
        $second = $b->get_iter_at_line(1);
        self::assertInstanceOf(GtkTextIter::class, $second);
        self::assertSame(1, $second->get_line());
        self::assertNull($b->get_iter_at_line(7), 'past the last line: null, not a clamped iter');
    }
}
