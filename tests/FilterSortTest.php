<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GListModel;
use Gtk4\GListStore;
use Gtk4\GObject;
use Gtk4\GtkCustomFilter;
use Gtk4\GtkCustomSorter;
use Gtk4\GtkFilter;
use Gtk4\GtkFilterChange;
use Gtk4\GtkFilterListModel;
use Gtk4\GtkFilterMatch;
use Gtk4\GtkOrdering;
use Gtk4\GtkSorter;
use Gtk4\GtkSorterChange;
use Gtk4\GtkSorterOrder;
use Gtk4\GtkSortListModel;
use Gtk4\PhpValue;

/**
 * GtkCustomFilter (GtkCustomFilterFunc) and GtkCustomSorter (GCompareDataFunc) - the two
 * notified-scope callback shapes - plus the list models that consume them.
 */
final class FilterSortTest extends GtkTestCase
{
    /** @param list<int> $values */
    private static function store(array $values): GListStore
    {
        $store = new GListStore(PhpValue::class);
        foreach ($values as $v) {
            $store->append(new PhpValue($v));
        }
        return $store;
    }

    /** The int inside a PhpValue item (typed for the compare/match closures). */
    private static function int(PhpValue $v): int
    {
        $x = $v->get_value();
        self::assertIsInt($x);
        return $x;
    }

    /** @return list<mixed> */
    private static function values(GListModel $model): array
    {
        $out = [];
        for ($i = 0; $i < $model->get_n_items(); $i++) {
            $item = $model->get_item($i);
            self::assertInstanceOf(PhpValue::class, $item);
            $out[] = $item->get_value();
        }
        return $out;
    }

    public function testCustomFilterMatchFuncDecidesVisibility(): void
    {
        $filter = new GtkCustomFilter(fn(PhpValue $v): bool => $v->get_value() > 2);
        self::assertInstanceOf(GtkFilter::class, $filter);
        $model = new GtkFilterListModel(self::store([5, 3, 8, 1]), $filter);
        self::assertInstanceOf(GListModel::class, $model);
        self::assertSame('GObject', $model->get_item_type());   // GTK: always GObject
        self::assertSame([5, 3, 8], self::values($model));
        self::assertSame($filter, $model->get_filter());
        self::assertNull($model->get_item(99));   // past the end is a position, just an empty one
        // A negative position is not: it used to reach GTK as 4294967295 (php_gtk4.h,
        // check_range), which happened to answer null. It is an argument error.
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('must be between 0 and 4294967295');
        $model->get_item(-1);
    }

    public function testFilterChangedReevaluates(): void
    {
        $limit = 2;
        $filter = new GtkCustomFilter(function (PhpValue $v) use (&$limit): bool {
            return $v->get_value() > $limit;
        });
        $model = new GtkFilterListModel(self::store([5, 3, 8, 1]), $filter);
        self::assertSame([5, 3, 8], self::values($model));
        $limit = 4;
        $filter->changed(GtkFilterChange::MoreStrict);
        self::assertSame([5, 8], self::values($model));
        $limit = 0;
        $filter->changed(GtkFilterChange::Different);
        self::assertSame([5, 3, 8, 1], self::values($model));
    }

    public function testFilterFuncCanBeReplacedAndRemoved(): void
    {
        $filter = new GtkCustomFilter();   // no func: everything matches
        $model = new GtkFilterListModel(self::store([1, 2, 3]), $filter);
        self::assertSame(3, $model->get_n_items());
        $filter->set_filter_func(fn(PhpValue $v): bool => $v->get_value() === 2);
        self::assertSame([2], self::values($model));
        $filter->set_filter_func(null);
        self::assertSame(3, $model->get_n_items());
    }

    public function testFilterListModelModelAndFilterAreOptional(): void
    {
        $model = new GtkFilterListModel();
        self::assertSame(0, $model->get_n_items());
        self::assertNull($model->get_model());
        self::assertNull($model->get_filter());
        $store = self::store([1, 2]);
        $model->set_model($store);
        self::assertSame($store, $model->get_model());
        self::assertSame(2, $model->get_n_items());
        $model->set_filter(new GtkCustomFilter(fn(): bool => false));
        self::assertSame(0, $model->get_n_items());
        $model->set_filter(null);
        self::assertSame(2, $model->get_n_items());
        $model->set_model(null);
        self::assertSame(0, $model->get_n_items());
        self::assertNull($model->model);
    }

    public function testCustomSorterOrdersItems(): void
    {
        $sorter = new GtkCustomSorter(fn(PhpValue $a, PhpValue $b): int => self::int($a) <=> self::int($b));
        self::assertInstanceOf(GtkSorter::class, $sorter);
        $model = new GtkSortListModel(self::store([5, 3, 8, 1]), $sorter);
        self::assertSame([1, 3, 5, 8], self::values($model));
        self::assertSame($sorter, $model->get_sorter());
        self::assertSame('GObject', $model->get_item_type());   // GTK: always GObject
    }

    public function testSorterChangedAndReplacement(): void
    {
        $flags = new \ArrayObject(['desc' => false]);
        $sorter = new GtkCustomSorter(function (PhpValue $a, PhpValue $b) use ($flags): int {
            $c = self::int($a) <=> self::int($b);
            return $flags['desc'] === true ? -$c : $c;
        });
        $model = new GtkSortListModel(self::store([5, 3, 8, 1]), $sorter);
        self::assertSame([1, 3, 5, 8], self::values($model));
        $flags['desc'] = true;
        $sorter->changed(GtkSorterChange::Inverted);
        self::assertSame([8, 5, 3, 1], self::values($model));
        // Large return values are clamped to a sign, not truncated.
        $sorter->set_sort_func(fn(PhpValue $a, PhpValue $b): int => (self::int($a) - self::int($b)) * 1_000_000);
        self::assertSame([1, 3, 5, 8], self::values($model));
        $sorter->set_sort_func(null);
        $sorter->changed(GtkSorterChange::Different);
        self::assertSame([5, 3, 8, 1], self::values($model));
    }

    public function testSortListModelModelAndSorterAreOptional(): void
    {
        $model = new GtkSortListModel();
        self::assertSame(0, $model->get_n_items());
        self::assertNull($model->get_model());
        self::assertNull($model->get_sorter());
        $model->set_model(self::store([2, 1]));
        self::assertSame([2, 1], self::values($model));
        $model->set_sorter(new GtkCustomSorter(fn(PhpValue $a, PhpValue $b): int => self::int($a) <=> self::int($b)));
        self::assertSame([1, 2], self::values($model));
        $model->set_sorter(null);
        $model->set_model(null);
        self::assertSame(0, $model->get_n_items());
    }

    public function testChainingFilterAndSort(): void
    {
        $filtered = new GtkFilterListModel(
            self::store([5, 3, 8, 1, 9]),
            new GtkCustomFilter(fn(PhpValue $v): bool => self::int($v) % 2 === 1),
        );
        $sorted = new GtkSortListModel(
            $filtered,
            new GtkCustomSorter(fn(PhpValue $a, PhpValue $b): int => self::int($b) <=> self::int($a)),
        );
        self::assertSame([9, 5, 3, 1], self::values($sorted));
        self::assertSame($filtered, $sorted->get_model());
    }

    public function testCallablesAreReleasedWithTheirOwner(): void
    {
        $token = new \stdClass();
        $weak = \WeakReference::create($token);
        $filter = new GtkCustomFilter(function () use ($token): bool {
            return spl_object_id($token) > 0;
        });
        $sorter = new GtkCustomSorter(function () use ($token): int {
            return spl_object_id($token) > 0 ? 0 : 1;
        });
        unset($token);
        self::assertNotNull($weak->get());
        unset($filter);
        self::assertNotNull($weak->get(), 'sorter still holds it');
        unset($sorter);
        self::assertNull($weak->get(), 'callables must die with their owners');
    }

    public function testExceptionsInMatchAndCompareFuncsAreReported(): void
    {
        $filter = new GtkCustomFilter(function (): bool {
            throw new \LogicException('match failed');
        });
        $captured = $this->captureHandlerException(function () use ($filter): void {
            $model = new GtkFilterListModel(self::store([1]), $filter);
            self::assertSame(0, $model->get_n_items());   // a failed match hides the item
        });
        self::assertSame(['match failed', 'GtkCustomFilter::__construct', 0], $captured);

        $sorter = new GtkCustomSorter(function (): int {
            throw new \LogicException('compare failed');
        });
        $captured = $this->captureHandlerException(function () use ($sorter): void {
            $model = new GtkSortListModel(self::store([2, 1]), $sorter);
            self::assertSame([2, 1], self::values($model));   // failed compares keep the order
        });
        self::assertSame(['compare failed', 'GtkCustomSorter::__construct', 0], $captured);
    }

    public function testWrongItemTypesAreTypeErrors(): void
    {
        $this->expectException(\TypeError::class);
        // @phpstan-ignore argument.type
        new GtkFilterListModel(new PhpValue(1));
    }

    public function testFilterStrictnessAndMatch(): void
    {
        $filter = new GtkCustomFilter(static fn(GObject $item): bool => self::value($item) > 1);
        self::assertSame(GtkFilterMatch::Some, $filter->get_strictness());
        self::assertTrue($filter->match(new PhpValue(2)));
        self::assertFalse($filter->match(new PhpValue(1)));
        $filter->set_filter_func(null);
        self::assertSame(GtkFilterMatch::All, $filter->get_strictness(), 'no callback: everything matches');
        self::assertTrue($filter->match(new PhpValue(1)));
    }

    public function testSorterOrderAndCompare(): void
    {
        $sorter = new GtkCustomSorter(static fn(GObject $a, GObject $b): int => self::value($a) <=> self::value($b));
        self::assertSame(GtkSorterOrder::Partial, $sorter->get_order());
        self::assertSame(GtkOrdering::Smaller, $sorter->compare(new PhpValue(1), new PhpValue(2)));
        self::assertSame(GtkOrdering::Larger, $sorter->compare(new PhpValue(3), new PhpValue(2)));
        self::assertSame(GtkOrdering::Equal, $sorter->compare(new PhpValue(2), new PhpValue(2)));
        $sorter->set_sort_func(null);
        self::assertSame(GtkSorterOrder::None, $sorter->get_order());
    }

    public function testIncrementalModelsReportPendingWork(): void
    {
        $store = new GListStore(PhpValue::class);
        foreach (range(1, 20) as $i) {
            $store->append(new PhpValue($i));
        }
        $filtered = new GtkFilterListModel($store, new GtkCustomFilter(static fn(GObject $i): bool => true));
        self::assertFalse($filtered->get_incremental());
        self::assertSame(0, $filtered->get_pending(), 'non-incremental: everything is filtered at once');
        $filtered->set_incremental(true);
        self::assertTrue($filtered->get_incremental());
        self::assertGreaterThanOrEqual(0, $filtered->get_pending());

        $sorted = new GtkSortListModel($store, new GtkCustomSorter(static fn(GObject $a, GObject $b): int => 0));
        $sorted->set_incremental(true);
        self::assertTrue($sorted->get_incremental());
        self::assertGreaterThanOrEqual(0, $sorted->get_pending());
    }

    private static function value(GObject $item): int
    {
        $v = $item instanceof PhpValue ? $item->get_value() : 0;
        return is_int($v) ? $v : 0;
    }
}
