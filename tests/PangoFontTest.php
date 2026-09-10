<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GdkRGBA;
use Gtk4\GskTextNode;
use Gtk4\GtkLabel;
use Gtk4\GtkSnapshot;
use Gtk4\PangoContext;
use Gtk4\PangoFont;
use Gtk4\PangoFontDescription;
use Gtk4\PangoFontFace;
use Gtk4\PangoFontFamily;
use Gtk4\PangoFontMap;
use Gtk4\PangoFontMetrics;
use Gtk4\PangoFontset;
use Gtk4\PangoGlyphString;
use Gtk4\PangoLanguage;
use Gtk4\PangoRectangle;

/**
 * Pango's font leaves: the font a description resolves to, the family and face it belongs to,
 * the fontset a description resolves to per script, the metrics a font measures with, and the
 * glyph string that is text after shaping.
 *
 * The four GObjects are abstract and belong to the backend - like PangoFontMap, `new` is refused
 * and a widget's context is where they come from. The two records are boxed values with GTypes
 * of their own, so nothing here needs a synthetic type.
 */
final class PangoFontTest extends GtkTestCase
{
    private function context(): PangoContext
    {
        return $this->window()->create_pango_context();
    }

    private function sans(): PangoFont
    {
        $font = $this->context()->load_font(PangoFontDescription::from_string('Sans 12'));
        self::assertInstanceOf(PangoFont::class, $font, 'Sans is an alias family every map lists');
        return $font;
    }

    /** The one shaping this binding has: a layout through a snapshot is a text node. */
    private function shaped(string $text): GskTextNode
    {
        $snapshot = new GtkSnapshot();
        $snapshot->append_layout(new GtkLabel($text)->get_layout(), new GdkRGBA('black'));
        $node = $snapshot->to_node();
        self::assertInstanceOf(GskTextNode::class, $node, 'one line of plain text is one text node');
        return $node;
    }

    public function testAFontIsWhatTheMapLoadsForADescription(): void
    {
        $font = $this->sans();
        $got = $font->describe();

        self::assertSame('12', substr($got->to_string(), -2), 'the size asked for');
        self::assertNotSame('Sans', $got->get_family(), 'the alias resolved to a real family');
        self::assertInstanceOf(PangoFontMap::class, $font->get_font_map());
        self::assertTrue($font->has_char(mb_ord('a')));
        self::assertFalse($font->has_char(0x10FFFF), 'a noncharacter is in no font');

        // The size in device units is the same font described another way.
        $absolute = $font->describe_with_absolute_size();
        self::assertSame($got->get_family(), $absolute->get_family());
        self::assertGreaterThan($got->get_size(), $absolute->get_size(), 'device units, not points');
    }

    public function testAFontHasAFaceInAFamily(): void
    {
        $face = $this->sans()->get_face();
        self::assertInstanceOf(PangoFontFace::class, $face);
        self::assertNotSame('', $face->get_face_name());
        self::assertFalse($face->is_synthesized(), 'the regular face is a real file');

        $family = $face->get_family();
        self::assertInstanceOf(PangoFontFamily::class, $family);
        self::assertSame($this->sans()->describe()->get_family(), $family->get_name());
        self::assertSame($family->get_name(), $face->describe()->get_family(), 'describe() names the family');
    }

    public function testAFamilyListsItsFacesAsAListModel(): void
    {
        $map = $this->sans()->get_font_map();
        self::assertInstanceOf(PangoFontMap::class, $map);
        $family = $map->get_family('Sans');

        self::assertSame('Sans', $family->get_name());
        self::assertFalse($family->is_monospace());
        self::assertGreaterThan(0, $family->get_n_items(), 'at least one face');
        self::assertSame('PangoFontFace', $family->get_item_type());
        self::assertInstanceOf(PangoFontFace::class, $family->get_item(0));

        self::assertInstanceOf(PangoFontFace::class, $family->get_face(null), 'null picks the default face');
        self::assertNull($family->get_face('No Such Face'));
        self::assertTrue($map->get_family('Monospace')->is_monospace());
    }

    public function testAFontsetResolvesPerCharacter(): void
    {
        $set = $this->context()->load_fontset(
            PangoFontDescription::from_string('Sans 12'),
            PangoLanguage::get_default(),
        );
        self::assertInstanceOf(PangoFontset::class, $set);

        $latin = $set->get_font(mb_ord('a'));
        self::assertInstanceOf(PangoFont::class, $latin);
        self::assertTrue($latin->has_char(mb_ord('a')), 'the member chosen for a character has it');
        self::assertInstanceOf(PangoFontMetrics::class, $set->get_metrics());
    }

    public function testMetricsDescribeAFontBeforeAnyTextIsSet(): void
    {
        $metrics = $this->sans()->get_metrics(null);

        self::assertGreaterThan(0, $metrics->get_ascent());
        self::assertGreaterThan(0, $metrics->get_descent());
        self::assertGreaterThanOrEqual(
            $metrics->get_ascent() + $metrics->get_descent(),
            $metrics->get_height(),
            'a line is at least ascent plus descent',
        );
        self::assertGreaterThan(0, $metrics->get_approximate_char_width());
        self::assertGreaterThanOrEqual(
            $metrics->get_approximate_char_width(),
            $metrics->get_approximate_digit_width() * 2,
        );
        self::assertGreaterThan(0, $metrics->get_underline_thickness());
        self::assertGreaterThan(0, $metrics->get_strikethrough_thickness());
        self::assertGreaterThan($metrics->get_underline_position(), $metrics->get_strikethrough_position());

        // The context measures the same font the same way, and a bigger one bigger.
        $viaContext = $this->context()->get_metrics(PangoFontDescription::from_string('Sans 12'), null);
        self::assertSame($metrics->get_ascent(), $viaContext->get_ascent());
        $bigger = $this->context()->get_metrics(PangoFontDescription::from_string('Sans 24'), null);
        self::assertGreaterThan($metrics->get_ascent(), $bigger->get_ascent());
    }

    /**
     * `new PangoContext()` has no font map, and Pango asserts on every call that needs one - the
     * loaders, and get_metrics(), which goes on to read a NULL fontset. The handle is in the wrong
     * state for the call, so it is a LogicException here, before Pango sees it.
     */
    public function testAContextWithoutAFontMapRefusesToLoad(): void
    {
        $bare = new PangoContext();
        self::assertNull($bare->get_font_map());
        $desc = PangoFontDescription::from_string('Sans 12');

        $calls = [
            fn() => $bare->load_font($desc),
            fn() => $bare->load_fontset($desc, PangoLanguage::get_default()),
            fn() => $bare->get_metrics($desc, null),
        ];
        foreach ($calls as $call) {
            try {
                $call();
                self::fail('a context without a font map cannot load anything');
            } catch (\LogicException $e) {
                self::assertStringContainsString('no font map', $e->getMessage());
            }
        }
    }

    public function testAGlyphStringMeasuresItselfWithAFont(): void
    {
        $node = $this->shaped('Affinity');
        $glyphs = $node->get_glyphs();
        $font = $node->get_font();

        self::assertInstanceOf(PangoGlyphString::class, $glyphs);
        self::assertGreaterThan(0, $node->get_num_glyphs());
        self::assertLessThan(8, $node->get_num_glyphs(), '"ffi" shapes to one glyph, so fewer glyphs than characters');
        self::assertGreaterThan(0, $glyphs->get_width(), 'the advance sum, in Pango units');

        [$ink, $logical] = $glyphs->extents($font);
        self::assertInstanceOf(PangoRectangle::class, $ink);
        self::assertSame($glyphs->get_width(), $logical->width, 'the logical width is the advance sum');

        // A range measures from its own start, so the per-glyph widths add up to the whole.
        $sum = 0;
        for ($i = 0; $i < $node->get_num_glyphs(); $i++) {
            [, $cell] = $glyphs->extents_range($i, $i + 1, $font);
            $sum += $cell->width;
        }
        self::assertSame($glyphs->get_width(), $sum);

        // A value: the copy is equal and independent.
        $copy = clone $glyphs;
        self::assertSame(0, $glyphs <=> $copy);
        $copy->set_size(1);
        self::assertNotSame(0, $glyphs <=> $copy);
    }

    /**
     * Pango's set_size() reallocates and leaves the new glyphs as the allocator left them, so a
     * fresh string used to answer get_width() from uninitialised memory (1658067 on one run). The
     * binding fills what grew with PANGO_GLYPH_EMPTY and no geometry: a new string measures as
     * empty - no advance, no ink - until something shapes into it.
     */
    public function testANewGlyphStringIsEmptyHoweverLargeItIsMade(): void
    {
        $glyphs = new PangoGlyphString();
        self::assertSame(0, $glyphs->get_width());

        $glyphs->set_size(64);
        self::assertSame(0, $glyphs->get_width(), 'grown glyphs are empty, not whatever was in the heap');
        [$ink, $logical] = $glyphs->extents($this->sans());
        self::assertSame(0, $logical->width);
        self::assertSame(0, $ink->width, 'the empty glyph, not glyph 0 - .notdef would draw a box');

        $glyphs->set_size(2);
        $glyphs->set_size(200);
        self::assertSame(0, $glyphs->get_width(), 'and again after shrinking and regrowing');

        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('greater than or equal to 0');
        $glyphs->set_size(-1);
    }

    public function testATextNodeIsBuiltFromItsPartsAndTakenApartAgain(): void
    {
        $original = $this->shaped('Render node');
        $font = $original->get_font();
        $glyphs = $original->get_glyphs();

        $rebuilt = new GskTextNode($font, $glyphs, new GdkRGBA('#3584e4'), $original->get_offset());

        self::assertSame($original->get_num_glyphs(), $rebuilt->get_num_glyphs());
        self::assertSame(0, $original->get_bounds() <=> $rebuilt->get_bounds(), 'the same glyphs, the same bounds');
        self::assertSame($font->describe()->to_string(), $rebuilt->get_font()->describe()->to_string());
        self::assertSame(0, $rebuilt->get_color() <=> new GdkRGBA('#3584e4'));
        self::assertFalse($rebuilt->has_color_glyphs());

        // and the copy is a value of its own: shrinking it does not touch the node
        $glyphs->set_size(1);
        self::assertSame($original->get_num_glyphs(), $rebuilt->get_num_glyphs());
    }
}
