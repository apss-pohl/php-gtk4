<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GtkLabel;
use Gtk4\GtkTextView;
use Gtk4\PangoAttrList;
use Gtk4\PangoContext;
use Gtk4\PangoEllipsizeMode;
use Gtk4\PangoFontMap;
use Gtk4\PangoLayout;
use Gtk4\PangoTabAlign;
use Gtk4\PangoTabArray;

/**
 * Pango, the text engine under every label: the layout that measures and lays out a paragraph,
 * the context and font map behind it, the tab stops a text widget aligns to and the attribute
 * list that styles a run of text.
 *
 * A widget hands out its own context (`create_pango_context()`), so nothing here has to build
 * one from a font map that only the backend can make.
 */
final class PangoTest extends GtkTestCase
{
    private function context(): PangoContext
    {
        return $this->window()->create_pango_context();
    }

    /** A layout measures text: the size is in Pango units, and there is a pixel form too. */
    public function testALayoutMeasuresItsText(): void
    {
        $layout = new PangoLayout($this->context());
        $layout->set_text('Hello, php-gtk4', -1);

        self::assertSame('Hello, php-gtk4', $layout->get_text());
        [$width, $height] = $layout->get_pixel_size();
        self::assertGreaterThan(0, $width);
        self::assertGreaterThan(0, $height);
        self::assertSame(1, $layout->get_line_count(), 'one line, nothing to wrap');
    }

    /** Wrapping is what a width turns one line into several. */
    public function testAWidthWrapsTheText(): void
    {
        $layout = new PangoLayout($this->context());
        $layout->set_text(str_repeat('word ', 40), -1);
        $layout->set_width(100 * 1024);   // Pango units: 1024 to the pixel

        self::assertGreaterThan(1, $layout->get_line_count());
        self::assertSame(100 * 1024, $layout->get_width());
    }

    public function testMarkupIsParsedIntoTextAndAttributes(): void
    {
        $layout = new PangoLayout($this->context());
        $layout->set_markup('<b>bold</b> and <i>italic</i>', -1);

        self::assertSame('bold and italic', $layout->get_text(), 'the markup is gone from the text');
        self::assertInstanceOf(PangoAttrList::class, $layout->get_attributes());
    }

    /** A context knows the font map it draws from, and a widget's context has a real one. */
    public function testAContextHasAFontMap(): void
    {
        $context = $this->context();

        self::assertInstanceOf(PangoFontMap::class, $context->get_font_map());
        self::assertSame($context->get_font_map(), $context->get_font_map(), 'the same handle');
    }

    /** Tab stops are positions, in Pango units or in pixels. */
    public function testATabArrayHoldsPositions(): void
    {
        $tabs = new PangoTabArray(2, true);
        $tabs->set_tab(0, PangoTabAlign::Left, 40);
        $tabs->set_tab(1, PangoTabAlign::Left, 120);

        self::assertSame(2, $tabs->get_size());
        self::assertTrue($tabs->get_positions_in_pixels());
        self::assertSame([PangoTabAlign::Left, 40], $tabs->get_tab(0));
        self::assertSame([PangoTabAlign::Left, 120], $tabs->get_tab(1));
    }

    /** What binding it was for: a text widget aligning to tab stops PHP chose. */
    public function testATextViewTakesTabStops(): void
    {
        $tabs = new PangoTabArray(1, true);
        $tabs->set_tab(0, PangoTabAlign::Left, 80);

        $view = new GtkTextView();
        $view->set_tabs($tabs);

        self::assertSame(1, $view->get_tabs()?->get_size());
        $view->set_tabs(null);
        self::assertNull($view->get_tabs(), 'back to the default tab stops');
    }

    /** A label's attributes are a list Pango applies to the text under it. */
    public function testALabelTakesAnAttributeList(): void
    {
        $label = new GtkLabel('attributed');
        $label->set_attributes(new PangoAttrList());

        self::assertInstanceOf(PangoAttrList::class, $label->get_attributes());
    }

    /** An empty attribute list equals another empty one: a boxed value compares by value. */
    public function testAttributeListsAreValues(): void
    {
        self::assertEquals(new PangoAttrList(), new PangoAttrList());
    }

    /** Ellipsizing is the layout's, not only the label's. */
    public function testALayoutCanEllipsize(): void
    {
        $layout = new PangoLayout($this->context());
        $layout->set_text(str_repeat('long ', 50), -1);
        $layout->set_width(80 * 1024);
        $layout->set_ellipsize(PangoEllipsizeMode::End);

        self::assertSame(PangoEllipsizeMode::End, $layout->get_ellipsize());
        self::assertTrue($layout->is_ellipsized());
    }
}
