<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GtkLabel;
use Gtk4\GtkTextView;
use Gtk4\PangoAttrList;
use Gtk4\PangoContext;
use Gtk4\PangoEllipsizeMode;
use Gtk4\PangoFontMap;
use Gtk4\PangoLanguage;
use Gtk4\PangoLayout;
use Gtk4\PangoRectangle;
use Gtk4\PangoScript;
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

    /**
     * The geometry a program needs to draw its own text, which was unreachable until
     * PangoRectangle had a boxed type to cross in: Pango's struct has no GType of its own, so the
     * binding registers one (src/Pango/PangoRectangleType.h) the way it does for GskRoundedRect.
     *
     * Units are Pango units - 1024 to the pixel - except where the call says pixels.
     */
    public function testALayoutMeasuresWhereItsInkAndLinesAre(): void
    {
        $layout = new PangoLayout($this->context());
        $layout->set_text('Hello', -1);

        [$ink, $logical] = $layout->get_extents();
        self::assertInstanceOf(PangoRectangle::class, $ink);
        self::assertGreaterThan(0, $logical->width, 'the line has a width in Pango units');
        self::assertSame(0, $logical->width % 1, 'and it is an integer count of them');

        [$pixelInk, $pixelLogical] = $layout->get_pixel_extents();
        // The logical rectangle is rounded to the nearest pixel edge at both ends (Pango's
        // "nearest" conversion, PANGO_PIXELS on each edge), so the width is the difference of
        // two rounded edges, not the width rounded - on Windows' fonts the two differ by one.
        $nearest = static fn(int $units): int => (int) floor(($units + 512) / 1024);
        self::assertSame(
            $nearest($logical->x + $logical->width) - $nearest($logical->x),
            $pixelLogical->width,
            '1024 units to the pixel, rounded at each edge',
        );
        self::assertLessThanOrEqual($pixelLogical->width, $pixelInk->width, 'ink fits its line');

        // Where a caret would sit, and how wide the character under it is.
        $second = $layout->index_to_pos(1);
        self::assertGreaterThan(0, $second->x, 'the second character starts after the first');
        self::assertGreaterThan(0, $second->width);

        [$strong, $weak] = $layout->get_cursor_pos(2);
        self::assertInstanceOf(PangoRectangle::class, $weak);
        self::assertGreaterThan(0, $strong->height, 'a caret is as tall as the line');
    }

    /** The engine's compare handler, without static analysis folding the comparison. */
    private static function compare(object $a, object $b): int
    {
        return $a <=> $b;
    }

    /** A rectangle is a value: fields are properties, a clone compares equal, `to_pixels()` converts. */
    public function testAPangoRectangleIsAValue(): void
    {
        $rect = new PangoRectangle(1024, 2048, 4096, 8192);
        self::assertSame([1024, 2048, 4096, 8192], [$rect->x, $rect->y, $rect->width, $rect->height]);

        $pixels = $rect->to_pixels();
        self::assertSame([1, 2, 4, 8], [$pixels->x, $pixels->y, $pixels->width, $pixels->height]);
        self::assertSame(1024, $rect->x, 'to_pixels() answers with a new rectangle');

        $copy = clone $rect;
        self::assertNotSame($rect, $copy);
        self::assertSame(0, self::compare($rect, $copy), 'compared by value, like every boxed type');

        $rect->width = 512;
        self::assertSame(512, $rect->width, 'the fields are writable');
        self::assertSame(4096, $copy->width, 'and the clone keeps its own');
        self::assertNotSame(0, self::compare($rect, $copy));
    }

    /**
     * A language tag is an opaque boxed value Pango interns: `from_string()` is the only way in,
     * it lower-cases what it is given, and a null tag is a null answer - which is why the return
     * is nullable and `PangoContext::get_language()` is too. The context of a *fresh* Pango
     * context carries none until one is set; a widget's context has one already.
     */
    public function testALanguageTagIsAValueAContextCanCarry(): void
    {
        $german = PangoLanguage::from_string('de-DE');
        self::assertInstanceOf(PangoLanguage::class, $german);
        self::assertSame('de-de', $german->to_string(), 'the tag is normalised to lower case');
        self::assertNull(PangoLanguage::from_string(null), 'no tag, no language');

        self::assertTrue($german->matches('de'), 'a range the tag falls in');
        self::assertFalse($german->matches('fr'));
        self::assertTrue($german->includes_script(PangoScript::Latin));
        self::assertNotSame('', $german->get_sample_string(), 'a pangram to measure with');

        self::assertInstanceOf(PangoLanguage::class, PangoLanguage::get_default());

        $context = $this->context();
        $context->set_language($german);
        self::assertSame('de-de', $context->get_language()?->to_string());
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
