<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GListModel;
use Gtk4\GObject;
use Gtk4\GtkStringList;
use Gtk4\GtkStringObject;
use Gtk4\GtkTreeListModel;
use Gtk4\GtkTreeListRow;

/**
 * GtkTreeListModel: a flat list model over a tree that a PHP callable unfolds one level at a
 * time (GtkTreeListModelCreateModelFunc, gen/overrides/Gtk.TreeListModel.*).
 */
final class TreeListTest extends GtkTestCase
{
    /** Children of 'a' are 'a1' and 'a2'; anything longer than one character is a leaf. */
    private static function tree(bool $passthrough = false, bool $autoexpand = false): GtkTreeListModel
    {
        return new GtkTreeListModel(
            new GtkStringList(['a', 'b']),
            $passthrough,
            $autoexpand,
            function (GObject $item): ?GListModel {
                if (!$item instanceof GtkStringObject || strlen($item->get_string()) !== 1) {
                    return null;
                }
                $name = $item->get_string();
                return new GtkStringList([$name . '1', $name . '2']);
            },
        );
    }

    /** @return list<string> the strings the model currently shows, top to bottom */
    private static function strings(GtkTreeListModel $model): array
    {
        $out = [];
        for ($i = 0; $i < $model->get_n_items(); $i++) {
            $row = $model->get_row($i);
            $item = $row?->get_item();
            $out[] = $item instanceof GtkStringObject ? $item->get_string() : '?';
        }
        return $out;
    }

    public function testAnUnexpandedTreeIsItsRoot(): void
    {
        $tree = self::tree();
        self::assertSame(2, $tree->get_n_items());
        self::assertSame('GtkTreeListRow', $tree->get_item_type(), 'without passthrough, rows are the items');
        self::assertSame(['a', 'b'], self::strings($tree));
        self::assertFalse($tree->get_passthrough());
        self::assertFalse($tree->get_autoexpand());
    }

    public function testExpandingARowInsertsItsChildren(): void
    {
        $tree = self::tree();
        $row = $tree->get_row(0);
        self::assertInstanceOf(GtkTreeListRow::class, $row);
        self::assertTrue($row->is_expandable(), 'the create function answered with a model');
        self::assertFalse($row->get_expanded());
        self::assertSame(0, $row->get_depth());
        self::assertNull($row->get_parent(), 'a root row has no parent');

        $row->set_expanded(true);
        self::assertTrue($row->get_expanded());
        self::assertSame(['a', 'a1', 'a2', 'b'], self::strings($tree));

        $child = $tree->get_row(1);
        self::assertInstanceOf(GtkTreeListRow::class, $child);
        self::assertSame(1, $child->get_depth());
        self::assertSame(1, $child->get_position());
        self::assertSame($row, $child->get_parent());
        self::assertFalse($child->is_expandable(), 'a two-character name is a leaf');

        // The children are reachable through the row itself, expanded or not.
        $children = $row->get_children();
        self::assertInstanceOf(GListModel::class, $children);
        self::assertSame(2, $children->get_n_items());
        self::assertSame($child, $row->get_child_row(0));

        $row->set_expanded(false);
        self::assertSame(['a', 'b'], self::strings($tree));
    }

    public function testAutoexpandUnfoldsEveryNewRow(): void
    {
        $tree = self::tree(autoexpand: true);
        self::assertTrue($tree->get_autoexpand());
        self::assertSame(['a', 'a1', 'a2', 'b', 'b1', 'b2'], self::strings($tree));

        $tree->set_autoexpand(false);
        self::assertFalse($tree->get_autoexpand());
    }

    public function testPassthroughHandsOutTheItemsThemselves(): void
    {
        $tree = self::tree(passthrough: true);
        self::assertTrue($tree->get_passthrough());
        self::assertSame('GObject', $tree->get_item_type());

        $item = $tree->get_item(0);
        self::assertInstanceOf(GtkStringObject::class, $item, 'the item, not the row that holds it');
        self::assertSame('a', $item->get_string());
        // The row is still reachable, and that is what expands.
        $row = $tree->get_row(0);
        self::assertInstanceOf(GtkTreeListRow::class, $row);
        $row->set_expanded(true);
        self::assertSame(4, $tree->get_n_items());
    }

    public function testTheRootStaysTheModelItWasBuiltFrom(): void
    {
        $root = new GtkStringList(['a']);
        $tree = new GtkTreeListModel($root, false, false, fn(GObject $item): ?GListModel => null);
        self::assertSame($root, $tree->get_model());
        self::assertFalse($tree->get_row(0)?->is_expandable());

        // The root keeps driving the tree after construction.
        $root->append('c');
        self::assertSame(2, $tree->get_n_items());
    }

    public function testACreateFunctionThatAnswersWithSomethingElseIsATypeError(): void
    {
        $tree = new GtkTreeListModel(
            new GtkStringList(['a']),
            false,
            false,
            self::opaque(fn(GObject $item): int => 42),
        );
        $captured = $this->captureHandlerException(function () use ($tree): void {
            self::assertFalse($tree->get_row(0)?->is_expandable(), 'the row stays a leaf');
        });
        self::assertNotNull($captured);
        self::assertStringContainsString('must return a GListModel or null', $captured[0]);
        self::assertSame('GtkTreeListModel::__construct', $captured[1]);
    }

    public function testAThrowingCreateFunctionReachesTheHandler(): void
    {
        $tree = new GtkTreeListModel(
            new GtkStringList(['a']),
            false,
            false,
            function (GObject $item): ?GListModel {
                throw new \RuntimeException('no children today');
            },
        );
        $captured = $this->captureHandlerException(function () use ($tree): void {
            $tree->get_row(0)?->is_expandable();
        });
        self::assertNotNull($captured);
        self::assertSame('no children today', $captured[0]);
        self::assertSame('GtkTreeListModel::__construct', $captured[1]);
    }

    public function testTheConstructorNeedsARootAndACreateFunction(): void
    {
        $this->expectException(\ArgumentCountError::class);
        /** @phpstan-ignore arguments.count (the four arguments are all required) */
        new GtkTreeListModel(new GtkStringList(['a']));
    }

    public function testTheRootHasToBeAListModel(): void
    {
        $this->expectException(\TypeError::class);
        /** @phpstan-ignore argument.type (a widget is not a list model) */
        new GtkTreeListModel(new \Gtk4\GtkLabel(), false, false, fn(GObject $item): ?GListModel => null);
    }

    public function testARowBelongsToItsModel(): void
    {
        $this->expectException(\Error::class);
        /** @phpstan-ignore new.privateConstructor (GtkTreeListModel creates its rows) */
        new GtkTreeListRow();
    }
}
