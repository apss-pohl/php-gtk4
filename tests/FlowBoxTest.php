<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GListStore;
use Gtk4\GtkFlowBox;
use Gtk4\GtkFlowBoxChild;
use Gtk4\GtkLabel;
use Gtk4\GtkSelectionMode;
use Gtk4\PhpValue;

/**
 * A wrapping grid of children: the same three callbacks as {@see ListBoxTest} without the
 * headers - filter, sort and the create-widget function behind bind_model() - plus the
 * line limits that make it a flow box rather than a list.
 */
final class FlowBoxTest extends GtkTestCase
{
    /** The string a PhpValue carries, for the model-bound children. */
    private static function textOf(PhpValue $item): string
    {
        $value = $item->get_value();
        return is_string($value) ? $value : '';
    }

    /** A box with $n children, each a label with its index. */
    private function box(int $n = 3): GtkFlowBox
    {
        $box = new GtkFlowBox();
        for ($i = 0; $i < $n; $i++) {
            $box->append(new GtkLabel("item $i"));
        }
        return $box;
    }

    /** An appended widget is wrapped in a GtkFlowBoxChild GTK creates. */
    public function testAppendWrapsTheChild(): void
    {
        $box = $this->box();

        $child = $box->get_child_at_index(0);
        self::assertInstanceOf(GtkFlowBoxChild::class, $child);
        self::assertInstanceOf(GtkLabel::class, $child->get_child());
        self::assertSame(0, $child->get_index());
        self::assertNull($box->get_child_at_index(9));
    }

    public function testTheLineLimitsAreWhatMakeItAFlowBox(): void
    {
        $box = $this->box();
        $box->set_min_children_per_line(2);
        $box->set_max_children_per_line(4);
        $box->set_homogeneous(true);

        self::assertSame(2, $box->get_min_children_per_line());
        self::assertSame(4, $box->get_max_children_per_line());
        self::assertTrue($box->get_homogeneous());
    }

    public function testSelectionFollowsTheMode(): void
    {
        $box = $this->box();
        $box->set_selection_mode(GtkSelectionMode::Multiple);
        $first = $box->get_child_at_index(0) ?? self::fail('no child 0');
        $second = $box->get_child_at_index(1) ?? self::fail('no child 1');

        $box->select_child($first);
        $box->select_child($second);
        self::assertTrue($first->is_selected());
        self::assertTrue($second->is_selected());

        $box->unselect_all();
        self::assertFalse($first->is_selected());
    }

    public function testTheFilterFuncHidesChildren(): void
    {
        $box = $this->box(4);
        $box->set_filter_func(static function (GtkFlowBoxChild $child): bool {
            return $child->get_index() < 2;
        });

        self::assertTrue($box->get_child_at_index(0)?->get_child_visible());
        self::assertFalse($box->get_child_at_index(3)?->get_child_visible());

        $box->set_filter_func(null);
        self::assertTrue($box->get_child_at_index(3)?->get_child_visible());
    }

    public function testTheSortFuncReordersChildren(): void
    {
        $box = new GtkFlowBox();
        foreach (['pear', 'apple', 'cherry'] as $name) {
            $child = new GtkFlowBoxChild();
            $child->set_name($name);
            $box->append($child);
        }

        $box->set_sort_func(static function (GtkFlowBoxChild $a, GtkFlowBoxChild $b): int {
            return strcmp($a->get_name(), $b->get_name());
        });

        self::assertSame('apple', $box->get_child_at_index(0)?->get_name());
        self::assertSame('pear', $box->get_child_at_index(2)?->get_name());
    }

    public function testBindModelFollowsTheModel(): void
    {
        $store = new GListStore(PhpValue::class);
        $store->append(new PhpValue('one'));
        $box = new GtkFlowBox();
        $box->bind_model($store, static function (PhpValue $item): GtkLabel {
            return new GtkLabel(self::textOf($item));
        });

        self::assertInstanceOf(GtkFlowBoxChild::class, $box->get_child_at_index(0));
        $store->append(new PhpValue('two'));
        self::assertInstanceOf(GtkFlowBoxChild::class, $box->get_child_at_index(1));

        $box->bind_model(null, null);
        self::assertNull($box->get_child_at_index(0), 'unbinding empties the box');
    }

    public function testAModelWithoutABuilderIsAValueError(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('must be a callable when a model is given');
        new GtkFlowBox()->bind_model(new GListStore(PhpValue::class), null);
    }

    public function testABuilderWithoutAModelIsAValueError(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('must be null when no model is given');
        new GtkFlowBox()->bind_model(null, static fn(): GtkLabel => new GtkLabel('x'));
    }

    /** A create function that throws is reported through the boundary, and the box survives. */
    public function testABuilderThatThrowsIsReported(): void
    {
        $store = new GListStore(PhpValue::class);
        $store->append(new PhpValue('one'));
        $box = new GtkFlowBox();

        $captured = $this->captureHandlerException(static function () use ($box, $store): void {
            $box->bind_model($store, static function (PhpValue $item): GtkLabel {
                throw new \RuntimeException('no widget for you');
            });
        });

        self::assertNotNull($captured);
        self::assertSame('no widget for you', $captured[0]);
        self::assertSame('GtkFlowBox::bind_model', $captured[1]);
        self::assertInstanceOf(GtkFlowBoxChild::class, $box->get_child_at_index(0));
    }
}
