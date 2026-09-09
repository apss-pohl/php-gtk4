<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GListStore;
use Gtk4\GtkLabel;
use Gtk4\GtkListBox;
use Gtk4\GtkListBoxRow;
use Gtk4\GtkSelectionMode;
use Gtk4\PhpValue;

/**
 * A list of rows: what goes in, what comes back, and the four callbacks GTK asks PHP for -
 * filter, sort, header and the create-widget function behind bind_model(). Those are the
 * hand-written part of the class ({@see gen/overrides}); the rest round-trips in the generated
 * smoke test.
 */
final class ListBoxTest extends GtkTestCase
{
    /** The string a PhpValue carries, for the model-bound rows. */
    private static function textOf(PhpValue $item): string
    {
        $value = $item->get_value();
        return is_string($value) ? $value : '';
    }

    /** A box with $n rows, each holding a label with the row's index. */
    private function box(int $n = 3): GtkListBox
    {
        $box = new GtkListBox();
        for ($i = 0; $i < $n; $i++) {
            $row = new GtkListBoxRow();
            $row->set_child(new GtkLabel("row $i"));
            $box->append($row);
        }
        return $box;
    }

    public function testRowsGoInAndComeBackByIndex(): void
    {
        $box = $this->box();
        $first = $box->get_row_at_index(0);

        self::assertInstanceOf(GtkListBoxRow::class, $first);
        self::assertSame(0, $first->get_index());
        self::assertSame(2, $box->get_row_at_index(2)?->get_index());
        self::assertNull($box->get_row_at_index(7), 'past the end there is no row');
    }

    public function testSelectionFollowsTheMode(): void
    {
        $box = $this->box();
        $box->set_selection_mode(GtkSelectionMode::Single);
        $row = $box->get_row_at_index(1) ?? self::fail('no row 1');

        $box->select_row($row);
        self::assertSame(1, $box->get_selected_row()?->get_index());
        self::assertTrue($row->is_selected());

        $box->unselect_row($row);
        self::assertNull($box->get_selected_row());
    }

    /** The filter decides which rows are shown; null shows them all again. */
    public function testTheFilterFuncHidesRows(): void
    {
        $box = $this->box(4);
        $box->set_filter_func(static function (GtkListBoxRow $row): bool {
            return $row->get_index() % 2 === 0;
        });

        self::assertTrue($box->get_row_at_index(0)?->get_child_visible());
        self::assertSame([true, false, true, false], self::visibility($box, 4));

        $box->set_filter_func(null);
        self::assertSame([true, true, true, true], self::visibility($box, 4));
    }

    /** @return list<bool> */
    private static function visibility(GtkListBox $box, int $n): array
    {
        $seen = [];
        for ($i = 0; $i < $n; $i++) {
            $seen[] = $box->get_row_at_index($i)?->get_child_visible() ?? false;
        }
        return $seen;
    }

    /** The sort func reorders the rows, so the indexes change under them. */
    public function testTheSortFuncReordersRows(): void
    {
        $box = new GtkListBox();
        foreach (['pear', 'apple', 'cherry'] as $name) {
            $row = new GtkListBoxRow();
            $row->set_name($name);
            $box->append($row);
        }

        $box->set_sort_func(static function (GtkListBoxRow $a, GtkListBoxRow $b): int {
            return strcmp($a->get_name(), $b->get_name());
        });

        self::assertSame('apple', $box->get_row_at_index(0)?->get_name());
        self::assertSame('cherry', $box->get_row_at_index(1)?->get_name());
        self::assertSame('pear', $box->get_row_at_index(2)?->get_name());
    }

    /** The header func is handed each row and the one above it, and sets the header itself. */
    public function testTheHeaderFuncIsToldWhatComesBefore(): void
    {
        $box = $this->box(3);
        $seen = [];
        $box->set_header_func(static function (GtkListBoxRow $row, ?GtkListBoxRow $before) use (&$seen): void {
            $seen[$row->get_index()] = $before?->get_index();
            if ($before === null) {
                $row->set_header(new GtkLabel('first'));
            }
        });

        self::assertSame([0 => null, 1 => 0, 2 => 1], $seen);
        self::assertInstanceOf(GtkLabel::class, $box->get_row_at_index(0)?->get_header());
        self::assertNull($box->get_row_at_index(1)?->get_header());
    }

    /** bind_model() builds a row per item and follows the model afterwards. */
    public function testBindModelFollowsTheModel(): void
    {
        $store = new GListStore(PhpValue::class);
        foreach (['one', 'two'] as $text) {
            $store->append(new PhpValue($text));
        }
        $box = new GtkListBox();
        $box->bind_model($store, static function (PhpValue $item): GtkListBoxRow {
            $row = new GtkListBoxRow();
            $row->set_child(new GtkLabel(self::textOf($item)));
            return $row;
        });

        self::assertSame('one', self::labelOf($box, 0));
        self::assertSame('two', self::labelOf($box, 1));

        $store->append(new PhpValue('three'));
        self::assertSame('three', self::labelOf($box, 2), 'the box followed the model');

        $store->remove(0);
        self::assertSame('two', self::labelOf($box, 0));
    }

    private static function labelOf(GtkListBox $box, int $index): ?string
    {
        $child = $box->get_row_at_index($index)?->get_child();
        return $child instanceof GtkLabel ? $child->get_text() : null;
    }

    /** Unbinding empties the box and drops the callable with the model. */
    public function testUnbindingEmptiesTheBox(): void
    {
        $store = new GListStore(PhpValue::class);
        $store->append(new PhpValue('one'));
        $box = new GtkListBox();
        $box->bind_model($store, static fn(PhpValue $item): GtkListBoxRow => new GtkListBoxRow());
        self::assertNotNull($box->get_row_at_index(0));

        $box->bind_model(null, null);
        self::assertNull($box->get_row_at_index(0));
    }

    /** A model with nothing to build its rows would leave GTK dereferencing null. */
    public function testAModelWithoutABuilderIsAValueError(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('Argument #2 ($create_widget_func) must be a callable when a model is given');
        new GtkListBox()->bind_model(new GListStore(PhpValue::class), null);
    }

    /**
     * And a builder with no model is refused rather than leaked: GTK returns before it stores
     * the destroy notify, so the callable would never be called and never released.
     */
    public function testABuilderWithoutAModelIsAValueError(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('Argument #2 ($create_widget_func) must be null when no model is given');
        new GtkListBox()->bind_model(null, static fn(): GtkListBoxRow => new GtkListBoxRow());
    }

    /** A create function that does not answer with a widget is reported, not a crash. */
    public function testABuilderThatReturnsRubbishIsReported(): void
    {
        $store = new GListStore(PhpValue::class);
        $store->append(new PhpValue('one'));
        $box = new GtkListBox();

        $captured = $this->captureHandlerException(static function () use ($box, $store): void {
            $box->bind_model($store, static fn(PhpValue $item): string => 'not a widget');
        });

        self::assertNotNull($captured);
        self::assertStringContainsString('must return a GtkWidget', $captured[0]);
        self::assertSame('GtkListBox::bind_model', $captured[1]);
    }
}
