<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GListModel;
use Gtk4\GtkBitset;
use Gtk4\GtkMultiSelection;
use Gtk4\GtkNoSelection;
use Gtk4\GtkNotebook;
use Gtk4\GtkSelectionModel;
use Gtk4\GtkSelectionModelObject;
use Gtk4\GtkSingleSelection;
use Gtk4\GtkStringList;
use Gtk4\GtkStringObject;

/**
 * The GtkSelectionModel interface and its three implementations, plus GtkBitset - the set of
 * positions every selection query answers with.
 */
final class SelectionTest extends GtkTestCase
{
    private const int INVALID = 0xFFFFFFFF;  // GTK_INVALID_LIST_POSITION

    private static function list(string ...$items): GtkStringList
    {
        return new GtkStringList($items);
    }

    /** @return list<int> the selected positions, in order */
    private static function positions(GtkBitset $set): array
    {
        $out = [];
        for ($i = 0; $i < $set->get_size(); $i++) {
            $out[] = $set->get_nth($i);
        }
        return $out;
    }

    public function testSingleSelectionSelectsOneItem(): void
    {
        $sel = new GtkSingleSelection(self::list('a', 'b', 'c'));
        // autoselect is on by default, so a fresh selection already has the first item.
        self::assertSame(0, $sel->get_selected());
        $item = $sel->get_selected_item();
        self::assertInstanceOf(GtkStringObject::class, $item);
        self::assertSame('a', $item->get_string());

        $sel->set_selected(2);
        self::assertSame(2, $sel->get_selected());
        self::assertTrue($sel->is_selected(2));
        self::assertFalse($sel->is_selected(0));
        self::assertSame([2], self::positions($sel->get_selection()));

        // The model reads like the one behind it - the selection is a decorator.
        self::assertSame(3, $sel->get_n_items());
        self::assertSame('GObject', $sel->get_item_type(), 'a selection takes any object');
    }

    public function testSingleSelectionWithoutAModelHasNothingToSelect(): void
    {
        $sel = new GtkSingleSelection();
        self::assertNull($sel->get_model());
        $sel->set_selected(1);
        self::assertSame(self::INVALID, $sel->get_selected(), 'GTK_INVALID_LIST_POSITION');
        self::assertNull($sel->get_selected_item());
        self::assertSame(0, $sel->get_selection()->get_size());
    }

    public function testUnselectingNeedsCanUnselect(): void
    {
        $sel = new GtkSingleSelection(self::list('a', 'b'));
        self::assertFalse($sel->get_can_unselect());
        self::assertFalse($sel->unselect_item(0), 'refused while can-unselect is off');
        self::assertSame(0, $sel->get_selected());

        $sel->set_autoselect(false);
        $sel->set_can_unselect(true);
        self::assertTrue($sel->unselect_item(0));
        self::assertSame(self::INVALID, $sel->get_selected(), 'nothing is selected now');
    }

    public function testSelectionChangedSignalCarriesTheRange(): void
    {
        $sel = new GtkSingleSelection(self::list('a', 'b', 'c'));
        $seen = [];
        $sel->connect(
            'selection-changed',
            function (GtkSelectionModel $model, int $position, int $n_items) use (&$seen): void {
                $seen[] = [$position, $n_items];
            },
        );
        $sel->set_selected(2);
        self::assertNotSame([], $seen, 'moving the selection reports the range it touched');
        foreach ($seen as [$position, $n_items]) {
            self::assertGreaterThanOrEqual(0, $position);
            self::assertGreaterThan(0, $n_items);
        }
    }

    public function testMultiSelectionSelectsRanges(): void
    {
        $multi = new GtkMultiSelection(self::list('a', 'b', 'c', 'd'));
        self::assertSame([], self::positions($multi->get_selection()), 'nothing is selected at first');

        self::assertTrue($multi->select_range(1, 2, false));
        self::assertSame([1, 2], self::positions($multi->get_selection()));
        // GTK is allowed to answer with more than the range asked for, never with less.
        self::assertContains(1, self::positions($multi->get_selection_in_range(0, 2)));

        self::assertTrue($multi->unselect_item(1));
        self::assertSame([2], self::positions($multi->get_selection()));

        self::assertTrue($multi->select_all());
        self::assertSame([0, 1, 2, 3], self::positions($multi->get_selection()));
        self::assertTrue($multi->unselect_all());
        self::assertTrue($multi->get_selection()->is_empty());
    }

    public function testSetSelectionAppliesASetThroughAMask(): void
    {
        $multi = new GtkMultiSelection(self::list('a', 'b', 'c', 'd'));
        // Only the positions in the mask are touched: 0 and 1 become selected, 2 and 3 are
        // outside the mask and keep whatever they had.
        self::assertTrue($multi->select_item(3, false));
        self::assertTrue($multi->set_selection(GtkBitset::new_range(0, 2), GtkBitset::new_range(0, 2)));
        self::assertSame([0, 1, 3], self::positions($multi->get_selection()));
    }

    public function testNoSelectionRefusesEverySelection(): void
    {
        $none = new GtkNoSelection(self::list('a', 'b'));
        self::assertFalse($none->select_item(0, true));
        self::assertFalse($none->select_all());
        self::assertFalse($none->is_selected(0));
        self::assertTrue($none->get_selection()->is_empty());
        self::assertSame(2, $none->get_n_items(), 'it is still a list model');
    }

    public function testTheModelBehindASelectionIsSwappable(): void
    {
        $sel = new GtkSingleSelection(self::list('a'));
        $other = self::list('x', 'y');
        $sel->set_model($other);
        self::assertSame($other, $sel->get_model());
        self::assertSame(2, $sel->get_n_items());
        $sel->set_model(null);
        self::assertNull($sel->get_model());
        self::assertSame(0, $sel->get_n_items());
    }

    public function testAPrivateSelectionModelWrapsToTheInterfaceFallback(): void
    {
        // GtkNotebookPages is GTK-private and implements GtkSelectionModel (which is a
        // GListModel): wrap() answers with the most derived registered interface's class.
        $nb = new GtkNotebook();
        $nb->append_page(new \Gtk4\GtkLabel(), null);
        $pages = $nb->get_pages();
        self::assertInstanceOf(GtkSelectionModelObject::class, $pages);
        self::assertInstanceOf(GtkSelectionModel::class, $pages);
        self::assertInstanceOf(GListModel::class, $pages);
        self::assertSame(1, $pages->get_n_items());
        self::assertTrue($pages->is_selected(0), 'the current page is the selected one');
    }

    public function testBitsetIsBuiltFromRangesAndValues(): void
    {
        $set = GtkBitset::new_empty();
        self::assertTrue($set->is_empty());
        self::assertSame(0, $set->get_size());
        self::assertSame(self::INVALID, $set->get_minimum(), 'an empty set has no minimum');

        self::assertTrue($set->add(4));
        self::assertFalse($set->add(4), 'already there');
        $set->add_range(0, 2);
        self::assertSame([0, 1, 4], self::positions($set));
        self::assertSame(0, $set->get_minimum());
        self::assertSame(4, $set->get_maximum());
        self::assertSame(4, $set->get_nth(2));
        self::assertSame(2, $set->get_size_in_range(0, 2));

        self::assertTrue($set->remove(0));
        self::assertFalse($set->contains(0));
        $set->remove_all();
        self::assertTrue($set->is_empty());
    }

    public function testBitsetSetOperations(): void
    {
        $a = GtkBitset::new_range(0, 4);   // 0,1,2,3
        $b = GtkBitset::new_range(2, 4);   // 2,3,4,5

        $union = clone $a;
        $union->union($b);
        self::assertSame([0, 1, 2, 3, 4, 5], self::positions($union));

        $intersection = clone $a;
        $intersection->intersect($b);
        self::assertSame([2, 3], self::positions($intersection));

        $difference = clone $a;
        $difference->subtract($b);
        self::assertSame([0, 1], self::positions($difference));

        $symmetric = clone $a;
        $symmetric->difference($b);
        self::assertSame([0, 1, 4, 5], self::positions($symmetric), 'the symmetric difference');

        self::assertSame([0, 1, 2, 3], self::positions($a), 'the operands are the ones asked to change');
    }

    public function testBitsetIsAValueWithItsOwnEquality(): void
    {
        $set = GtkBitset::new_range(0, 2);
        $copy = clone $set;
        self::assertNotSame($set, $copy);
        self::assertTrue($set->equals($copy));
        self::assertSame(0, self::compare($set, $copy), 'a boxed record compares by value');

        // A refcounted record registers ref() as its boxed copy, so cloning has to go through
        // gtk_bitset_copy() - otherwise this add() would show up in $set too.
        $copy->add(9);
        self::assertFalse($set->equals($copy));
        self::assertNotSame(0, self::compare($set, $copy));
        self::assertSame([0, 1], self::positions($set));
    }

    public function testBitsetHasNoPublicConstructor(): void
    {
        $this->expectException(\Error::class);
        /** @phpstan-ignore new.privateConstructor (new_empty()/new_range() are the way in) */
        new GtkBitset();
    }

    /** The engine's compare handler, without static analysis folding the comparison. */
    private static function compare(object $a, object $b): int
    {
        return $a <=> $b;
    }
}
