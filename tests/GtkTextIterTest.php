<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GtkTextBuffer;
use Gtk4\GtkTextIter;
use Gtk4\GtkTextSearchFlags;

/**
 * GtkTextIter as a boxed value handle: iters come from the buffer (never `new`), clone
 * copies the position, navigation and comparison work on the copy, search returns a
 * match range or null.
 */
final class GtkTextIterTest extends GtkTestCase
{
    private function buffer(string $text): GtkTextBuffer
    {
        $b = new GtkTextBuffer();
        $b->set_text($text);
        return $b;
    }

    public function testNewIsRefused(): void
    {
        $this->expectException(\Error::class);
        /** @phpstan-ignore new.privateConstructor (the refusal is the point) */
        new GtkTextIter();
    }

    public function testCloneIsAnIndependentCopy(): void
    {
        $iter = $this->buffer('abc')->get_start_iter();
        $copy = clone $iter;
        self::assertTrue($iter->equal($copy));
        self::assertTrue($copy->forward_char());
        self::assertFalse($iter->equal($copy), 'advancing the copy leaves the original alone');
        self::assertSame(0, $iter->get_offset());
        self::assertSame(1, $copy->get_offset());
    }

    public function testCharacterNavigation(): void
    {
        $iter = $this->buffer("ab\ncd")->get_start_iter();
        self::assertTrue($iter->is_start());
        self::assertSame(ord('a'), $iter->get_char());
        self::assertTrue($iter->forward_chars(3));
        self::assertSame([1, 0], [$iter->get_line(), $iter->get_line_offset()]);
        $iter->forward_to_end();
        self::assertTrue($iter->is_end());
        self::assertSame(5, $iter->get_offset());
        self::assertTrue($iter->backward_line());
        self::assertSame(0, $iter->get_offset(), 'backward_line lands at the start of the previous line');
    }

    public function testWordNavigationAndPredicates(): void
    {
        $iter = $this->buffer('one two three')->get_start_iter();
        self::assertTrue($iter->starts_word());
        self::assertTrue($iter->forward_word_end());
        self::assertSame(3, $iter->get_offset());
        self::assertTrue($iter->ends_word());
        // forward_word_ends() answers false when the move lands on the end iterator,
        // even though it moved - assert the position, not the return.
        $iter->forward_word_ends(2);
        self::assertSame(13, $iter->get_offset(), 'behind "three"');
        self::assertTrue($iter->is_end());
        self::assertTrue($iter->backward_word_start());
        self::assertSame(8, $iter->get_offset(), 'back to the start of "three"');
        self::assertTrue($iter->inside_word());
    }

    public function testCompareOrderAndRange(): void
    {
        $b = $this->buffer('abcdef');
        $lo = $b->get_iter_at_offset(1);
        $hi = $b->get_iter_at_offset(4);
        self::assertLessThan(0, $lo->compare($hi));
        self::assertGreaterThan(0, $hi->compare($lo));
        self::assertSame(0, $lo->compare(clone $lo));
        self::assertTrue($b->get_iter_at_offset(2)->in_range($lo, $hi));
        self::assertFalse($b->get_iter_at_offset(5)->in_range($lo, $hi));

        // order() swaps the two so the receiver is the earlier one
        $hi->order($lo);
        self::assertSame([1, 4], [$hi->get_offset(), $lo->get_offset()], 'order() swapped the positions');
    }

    public function testTextSliceBetweenIters(): void
    {
        $b = $this->buffer('hello world');
        $start = $b->get_iter_at_offset(6);
        self::assertSame('world', $start->get_text($b->get_end_iter()));
        self::assertSame('world', $start->get_slice($b->get_end_iter()));
    }

    public function testSettersMoveTheIter(): void
    {
        $iter = $this->buffer("ab\ncd")->get_start_iter();
        $iter->set_offset(4);
        self::assertSame(4, $iter->get_offset());
        $iter->set_line(1);
        self::assertSame(3, $iter->get_offset());
        $iter->set_line_offset(1);
        self::assertSame(4, $iter->get_offset());
    }

    public function testForwardSearchFindsARangeOrNull(): void
    {
        $b = $this->buffer('say Hello twice: hello');
        $match = $b->get_start_iter()->forward_search('hello', GtkTextSearchFlags::CASE_INSENSITIVE, null);
        self::assertIsArray($match);
        [$s, $e] = $match;
        self::assertSame([4, 9], [$s->get_offset(), $e->get_offset()]);
        self::assertSame('Hello', $s->get_text($e));

        self::assertNull($b->get_start_iter()->forward_search('absent', 0, null), 'no match: null');

        $back = $b->get_end_iter()->backward_search('hello', 0, null);
        self::assertIsArray($back);
        self::assertSame(17, $back[0]->get_offset(), 'backward search finds the case-sensitive late match');
    }

    public function testGetBufferAnswersTheOwningBuffer(): void
    {
        $b = $this->buffer('x');
        self::assertSame($b, $b->get_start_iter()->get_buffer());
    }

    public function testIterKeepsItsBufferAlive(): void
    {
        // No PHP reference to the buffer survives this line - the iter handle holds the
        // only remaining one (BoxedClass::owner). Without it this read dangled into freed
        // memory and crashed the process.
        $iter = $this->buffer('kept alive')->get_start_iter();
        self::assertSame('kept alive', $iter->get_text($iter->get_buffer()->get_end_iter()));
        $copy = clone $iter;                 // the clone refs the buffer too
        unset($iter);
        self::assertSame(0, $copy->get_offset());
    }
}
