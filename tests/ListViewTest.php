<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GLib;
use Gtk4\GListModel;
use Gtk4\GMenu;
use Gtk4\GtkColumnView;
use Gtk4\GtkColumnViewColumn;
use Gtk4\GtkGridView;
use Gtk4\GtkLabel;
use Gtk4\GtkListItem;
use Gtk4\GtkListItemFactory;
use Gtk4\GtkListScrollFlags;
use Gtk4\GtkListTabBehavior;
use Gtk4\GtkListView;
use Gtk4\GtkScrollInfo;
use Gtk4\GtkSignalListItemFactory;
use Gtk4\GtkSingleSelection;
use Gtk4\GtkSorter;
use Gtk4\GtkSortType;
use Gtk4\GtkStringList;
use Gtk4\GtkStringObject;

/**
 * The list widgets: GtkListView / GtkGridView / GtkColumnView over a selection model, with
 * GtkSignalListItemFactory building the rows out of GtkListItems.
 */
final class ListViewTest extends GtkTestCase
{
    private static function selection(string ...$items): GtkSingleSelection
    {
        return new GtkSingleSelection(new GtkStringList($items));
    }

    /** A factory that puts a label into every row and writes the item's string into it. */
    private static function labelFactory(): GtkSignalListItemFactory
    {
        $factory = new GtkSignalListItemFactory();
        $factory->connect('setup', function (GtkSignalListItemFactory $f, GtkListItem $item): void {
            $item->set_child(new GtkLabel());
        });
        $factory->connect('bind', function (GtkSignalListItemFactory $f, GtkListItem $item): void {
            $child = $item->get_child();
            $object = $item->get_item();
            if ($child instanceof GtkLabel && $object instanceof GtkStringObject) {
                $child->set_text($object->get_string());
            }
        });
        return $factory;
    }

    /** Show $widget in a window and let GTK build the rows (the factory runs while it does). */
    private function realize(\Gtk4\GtkWidget $widget): void
    {
        $win = $this->window();
        $win->set_default_size(200, 200);
        $win->set_child($widget);
        $win->present();
        for ($i = 0; $i < 40; $i++) {
            GLib::main_context_iteration(false);
        }
    }

    public function testFactoryBuildsTheRowsOfAListView(): void
    {
        $factory = new GtkSignalListItemFactory();
        $steps = [];
        $factory->connect('setup', function (GtkSignalListItemFactory $f, GtkListItem $item) use (&$steps): void {
            $steps[] = 'setup';
            self::assertNull($item->get_item(), 'setup runs before an item is bound');
            $item->set_child(new GtkLabel());
        });
        $factory->connect('bind', function (GtkSignalListItemFactory $f, GtkListItem $item) use (&$steps): void {
            $object = $item->get_item();
            self::assertInstanceOf(GtkStringObject::class, $object);
            $steps[] = 'bind ' . $item->get_position() . ' ' . $object->get_string();
            $child = $item->get_child();
            if ($child instanceof GtkLabel) {
                $child->set_text($object->get_string());
            }
        });

        $view = new GtkListView(self::selection('one', 'two'), $factory);
        $this->realize($view);

        self::assertContains('setup', $steps);
        self::assertContains('bind 0 one', $steps);
        self::assertContains('bind 1 two', $steps);
    }

    public function testAListItemCarriesItsRowState(): void
    {
        $seen = null;
        $factory = new GtkSignalListItemFactory();
        $factory->connect('setup', function (GtkSignalListItemFactory $f, GtkListItem $item) use (&$seen): void {
            $seen ??= $item;
            $item->set_child(new GtkLabel('row'));
        });

        $this->realize(new GtkListView(self::selection('a'), $factory));

        self::assertInstanceOf(GtkListItem::class, $seen);
        self::assertInstanceOf(GtkLabel::class, $seen->get_child());
        self::assertTrue($seen->get_selectable());
        self::assertTrue($seen->get_activatable());
        $seen->set_selectable(false);
        $seen->set_activatable(false);
        self::assertFalse($seen->get_selectable());
        self::assertFalse($seen->get_activatable());
        $seen->set_accessible_label('the row');
        self::assertSame('the row', $seen->get_accessible_label());
    }

    public function testAListItemIsNeverConstructedFromPhp(): void
    {
        $this->expectException(\Error::class);
        /** @phpstan-ignore new.privateConstructor (GTK's factory creates them) */
        new GtkListItem();
    }

    public function testListViewKeepsItsModelAndFactory(): void
    {
        $selection = self::selection('a', 'b');
        $factory = self::labelFactory();
        $view = new GtkListView($selection, $factory);
        self::assertSame($selection, $view->get_model());
        self::assertSame($factory, $view->get_factory());
        self::assertInstanceOf(GtkListItemFactory::class, $factory);

        $header = self::labelFactory();
        $view->set_header_factory($header);
        self::assertSame($header, $view->get_header_factory());

        $view->set_show_separators(true);
        self::assertTrue($view->get_show_separators());
        $view->set_single_click_activate(true);
        self::assertTrue($view->get_single_click_activate());
        $view->set_tab_behavior(GtkListTabBehavior::Item);
        self::assertSame(GtkListTabBehavior::Item, $view->get_tab_behavior());

        $view->set_model(null);
        self::assertNull($view->get_model());
    }

    public function testActivateCarriesThePosition(): void
    {
        $view = new GtkListView(self::selection('a', 'b', 'c'), self::labelFactory());
        $position = null;
        $view->connect('activate', function (GtkListView $v, int $pos) use (&$position): void {
            $position = $pos;
        });
        $view->emit('activate', 1);
        self::assertSame(1, $position);
    }

    public function testScrollToTakesAScrollInfoWithoutLosingIt(): void
    {
        $view = new GtkListView(self::selection('a', 'b', 'c', 'd'), self::labelFactory());
        $this->realize($view);

        $info = new GtkScrollInfo();
        $info->set_enable_horizontal(false);
        self::assertFalse($info->get_enable_horizontal());
        self::assertTrue($info->get_enable_vertical());

        // GTK takes the scroll info over (transfer full); the handle keeps its own reference,
        // so using it afterwards must not touch freed memory.
        $view->scroll_to(3, GtkListScrollFlags::FOCUS, $info);
        $view->scroll_to(0, GtkListScrollFlags::NONE, null);
        self::assertFalse($info->get_enable_horizontal(), 'the handle still owns its value');
    }

    public function testGridViewArrangesTheSameModelInColumns(): void
    {
        $selection = self::selection('a', 'b', 'c', 'd', 'e', 'f');
        $grid = new GtkGridView($selection, self::labelFactory());
        $grid->set_min_columns(2);
        $grid->set_max_columns(3);
        self::assertSame(2, $grid->get_min_columns());
        self::assertSame(3, $grid->get_max_columns());
        $grid->set_enable_rubberband(true);
        self::assertTrue($grid->get_enable_rubberband());

        $this->realize($grid);
        self::assertSame($selection, $grid->get_model());
    }

    public function testColumnViewHoldsItsColumns(): void
    {
        $view = new GtkColumnView(self::selection('a', 'b'));
        $first = new GtkColumnViewColumn('First', self::labelFactory());
        $second = new GtkColumnViewColumn('Second');
        $view->append_column($first);
        $view->append_column($second);

        $columns = $view->get_columns();
        self::assertInstanceOf(GListModel::class, $columns);
        self::assertSame(2, $columns->get_n_items());
        self::assertSame($first, $columns->get_item(0));
        self::assertSame($view, $first->get_column_view());

        $inserted = new GtkColumnViewColumn('Middle');
        $view->insert_column(1, $inserted);
        self::assertSame($inserted, $columns->get_item(1));

        $view->remove_column($inserted);
        self::assertSame(2, $columns->get_n_items());
        self::assertNull($inserted->get_column_view(), 'a removed column belongs to no view');

        $view->set_show_column_separators(true);
        self::assertTrue($view->get_show_column_separators());
        $view->set_reorderable(false);
        self::assertFalse($view->get_reorderable());
    }

    public function testColumnCarriesTitleFactoryAndMenu(): void
    {
        $column = new GtkColumnViewColumn();
        self::assertNull($column->get_title());
        self::assertNull($column->get_factory());

        $column->set_title('Name');
        self::assertSame('Name', $column->get_title());
        $factory = self::labelFactory();
        $column->set_factory($factory);
        self::assertSame($factory, $column->get_factory());
        $column->set_id('name');
        self::assertSame('name', $column->get_id());
        $column->set_expand(true);
        self::assertTrue($column->get_expand());
        $column->set_fixed_width(120);
        self::assertSame(120, $column->get_fixed_width());
        $column->set_visible(false);
        self::assertFalse($column->get_visible());

        $menu = new GMenu();
        $column->set_header_menu($menu);
        self::assertSame($menu, $column->get_header_menu());
    }

    public function testSortingByAColumnNeedsASorterOnIt(): void
    {
        $view = new GtkColumnView(self::selection('b', 'a'));
        $column = new GtkColumnViewColumn('Name');
        $view->append_column($column);

        // The view's own sorter is what a GtkSortListModel would follow; it exists as soon as
        // the view does, and reports GtkSorter (GtkColumnViewSorter is GTK-private).
        self::assertInstanceOf(GtkSorter::class, $view->get_sorter());

        self::assertNull($column->get_sorter(), 'a column without a sorter cannot sort');
        $view->sort_by_column($column, GtkSortType::Descending);
        $view->sort_by_column(null, GtkSortType::Ascending);
    }
}
