<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GMenu;
use Gtk4\GtkTextBuffer;
use Gtk4\GtkTextView;
use Gtk4\GtkWrapMode;

/**
 * GtkTextView beyond the smoke round trips: the buffer it displays, wrap mode as a real
 * enum, editability, scrolling to a mark, and the nullable extra-menu the GIR annotation
 * misses (gen/overrides/Gtk.TextView.get_extra_menu).
 */
final class GtkTextViewTest extends GtkTestCase
{
    public function testViewCreatesItsOwnBufferOnDemand(): void
    {
        $view = new GtkTextView();
        $buffer = $view->get_buffer();
        self::assertInstanceOf(GtkTextBuffer::class, $buffer);
        self::assertSame($buffer, $view->get_buffer(), 'the on-demand buffer is stable');
    }

    public function testSetBufferAndNewWithBuffer(): void
    {
        $buffer = new GtkTextBuffer();
        $buffer->set_text('shared');
        $view = new GtkTextView();
        $view->set_buffer($buffer);
        self::assertSame($buffer, $view->get_buffer());

        $second = GtkTextView::new_with_buffer($buffer);
        self::assertSame($buffer, $second->get_buffer(), 'two views can share one buffer');

        $view->set_buffer(null);
        self::assertNotSame($buffer, $view->get_buffer(), 'null detaches; a fresh buffer appears on demand');
    }

    public function testWrapModeIsARealEnum(): void
    {
        $view = new GtkTextView();
        self::assertSame(GtkWrapMode::None, $view->get_wrap_mode());
        $view->set_wrap_mode(GtkWrapMode::Word);
        self::assertSame(GtkWrapMode::Word, $view->get_wrap_mode());
        self::assertSame(GtkWrapMode::Word, $view->wrap_mode, 'property access sees the same case');
    }

    public function testEditableAndCursorVisible(): void
    {
        $view = new GtkTextView();
        self::assertTrue($view->get_editable());
        $view->set_editable(false);
        self::assertFalse($view->get_editable());
        $view->set_cursor_visible(false);
        self::assertFalse($view->get_cursor_visible());
        $view->set_monospace(true);
        self::assertTrue($view->get_monospace());
    }

    public function testScrollToMarkOnAMappedView(): void
    {
        $buffer = new GtkTextBuffer();
        $buffer->set_text(str_repeat("line\n", 200));
        $view = new GtkTextView();
        $view->set_buffer($buffer);
        $mark = $buffer->create_mark('tail', $buffer->get_end_iter(), false);

        $win = $this->window();
        $win->set_child($view);
        $win->present();
        $view->scroll_to_mark($mark, 0.0, true, 0.0, 1.0);
        $view->scroll_mark_onscreen($mark);
        // The actual scroll happens in a layout pass; the calls must be accepted as-is.
        self::assertFalse($mark->get_deleted());
        $win->set_child(null);
    }

    public function testExtraMenuIsNullUntilSet(): void
    {
        $view = new GtkTextView();
        self::assertNull($view->get_extra_menu(), 'no extra menu by default (nullable despite the GIR)');
        $menu = new GMenu();
        $view->set_extra_menu($menu);
        self::assertSame($menu, $view->get_extra_menu());
        $view->set_extra_menu(null);
        self::assertNull($view->get_extra_menu());
    }

    public function testMarginsRoundTrip(): void
    {
        $view = new GtkTextView();
        $view->set_left_margin(7);
        $view->set_top_margin(9);
        self::assertSame(7, $view->get_left_margin());
        self::assertSame(9, $view->get_top_margin());
    }
}
