<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GtkButton;
use Gtk4\GtkTextChildAnchor;
use Gtk4\GtkTextView;

/**
 * A widget inside running text: the anchor is the place in the buffer, and the view is what puts
 * a widget there. Two objects have to agree - the buffer holds the anchor, the view holds the
 * widget - which is why the anchor exists at all rather than the widget going into the buffer.
 */
final class TextChildAnchorTest extends GtkTestCase
{
    private function view(string $text = 'before after'): GtkTextView
    {
        $view = new GtkTextView();
        $view->get_buffer()->set_text($text, -1);
        return $view;
    }

    /** create_child_anchor() inserts a fresh anchor at the iterator in one step. */
    public function testTheBufferCreatesAnAnchorAtAnIterator(): void
    {
        $view = $this->view();
        $buffer = $view->get_buffer();

        $anchor = $buffer->create_child_anchor($buffer->get_iter_at_offset(6));
        self::assertInstanceOf(GtkTextChildAnchor::class, $anchor);

        // The anchor occupies one position in the text, so the buffer grew by a character.
        self::assertSame(13, $buffer->get_char_count());
    }

    /** An anchor built by PHP is inserted with insert_child_anchor() instead. */
    public function testAnAnchorCanBeBuiltAndThenInserted(): void
    {
        $view = $this->view();
        $buffer = $view->get_buffer();
        $anchor = new GtkTextChildAnchor();

        $buffer->insert_child_anchor($buffer->get_iter_at_offset(6), $anchor);

        $iter = $buffer->get_iter_at_child_anchor($anchor);
        self::assertSame(6, $iter->get_offset(), 'the buffer knows where the anchor went');
    }

    /** The iterator at the anchor's position answers with the anchor again. */
    public function testAnIteratorFindsTheAnchorAtItsPosition(): void
    {
        $view = $this->view();
        $buffer = $view->get_buffer();
        $anchor = $buffer->create_child_anchor($buffer->get_iter_at_offset(6));

        self::assertNotNull($buffer->get_iter_at_offset(6)->get_child_anchor());
        self::assertNull(
            $buffer->get_iter_at_offset(0)->get_child_anchor(),
            'an ordinary character has no anchor',
        );
    }

    /** What the anchor is for: a real widget, laid out inside the text. */
    public function testTheViewPutsAWidgetAtTheAnchor(): void
    {
        $view = $this->view();
        $buffer = $view->get_buffer();
        $anchor = $buffer->create_child_anchor($buffer->get_iter_at_offset(6));

        $button = new GtkButton();
        $view->add_child_at_anchor($button, $anchor);

        self::assertSame($view, $button->get_parent(), 'the view took the widget');
    }

    /** A replacement character is what a copy of the text shows where the widget sits. */
    public function testAnAnchorCanCarryAReplacementCharacter(): void
    {
        $anchor = GtkTextChildAnchor::new_with_replacement('*');
        self::assertInstanceOf(GtkTextChildAnchor::class, $anchor);
    }

    /** GTK counts characters, not bytes: two of them is not one. */
    public function testAReplacementOfMoreThanOneCharacterIsAValueError(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('Argument #1 ($character) must be exactly one character');
        GtkTextChildAnchor::new_with_replacement('ab');
    }

    /** ...and one multi-byte character is still one character. */
    public function testAMultiByteReplacementIsOneCharacter(): void
    {
        self::assertInstanceOf(
            GtkTextChildAnchor::class,
            GtkTextChildAnchor::new_with_replacement('★'),
        );
    }
}
