<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GListStore;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkFilter;
use Gtk4\GtkFilterListModel;
use Gtk4\GtkFilterMatch;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkSizeRequestMode;
use Gtk4\GtkSortListModel;
use Gtk4\GtkWidget;
use Gtk4\PhpValue;
use PhpGtk4\Tests\Subclass\ArrayModel;
use PhpGtk4\Tests\Subclass\BigSquare;
use PhpGtk4\Tests\Subclass\EvenFilter;
use PhpGtk4\Tests\Subclass\HelloLabel;
use PhpGtk4\Tests\Subclass\NativeChain;
use PhpGtk4\Tests\Subclass\Square;
use PhpGtk4\Tests\Subclass\Throwing;
use PhpGtk4\Tests\Subclass\TitledWindow;
use PhpGtk4\Tests\Subclass\VBox;

/** src/core/subtype: PHP subclasses as real GTypes, vfunc_* overrides, construct properties. */
final class SubclassTest extends GtkTestCase
{
    public function testAbstractClassIsConstructibleOnlyThroughASubclass(): void
    {
        $sq = new Square();
        self::assertInstanceOf(GtkWidget::class, $sq);
        $this->expectException(\Error::class);
        $this->expectExceptionMessage('subclass it in PHP');
        $class = self::className(GtkWidget::class);
        new $class();
    }

    private static function className(string $name): string
    {
        return $name;  // hides the literal from static analysis
    }

    public function testMeasureVfuncDrivesGtkGeometry(): void
    {
        $sq = new Square();
        self::assertSame([40, 40, -1, -1], $sq->measure(GtkOrientation::Horizontal, -1));
        self::assertSame([60, 60, -1, -1], $sq->measure(GtkOrientation::Vertical, -1));
        self::assertSame(2, $sq->measured, 'GTK called the PHP method');
        self::assertSame(GtkSizeRequestMode::ConstantSize, $sq->get_request_mode());
    }

    public function testParentChainingAcrossTwoPhpLevels(): void
    {
        $big = new BigSquare();
        self::assertSame([140, 140, -1, -1], $big->measure(GtkOrientation::Horizontal, -1));
        self::assertSame(1, $big->measured, 'parent::vfunc_measure() ran Square\'s override once');
    }

    public function testParentChainingReachesGtk(): void
    {
        $w = new NativeChain();
        self::assertSame([7, 7, -1, -1], $w->measure(GtkOrientation::Horizontal, -1));
        self::assertSame([0, 0], array_slice($w->native, 0, 2), 'GtkWidget\'s own measure() reports nothing');
    }

    public function testNativeVfuncMethodRefusesANativeInstance(): void
    {
        // vfunc_*() is the slot below the PHP levels, for parent:: chaining; on a plain GtkLabel
        // it would bypass the public API's preconditions.
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('parent:: chaining');
        new GtkLabel('abc')->vfunc_measure(GtkOrientation::Horizontal, -1);
    }

    public function testFilterWrittenInPhp(): void
    {
        $store = new GListStore(PhpValue::class);
        foreach ([1, 2, 3, 4, 5, 6] as $i) {
            $store->append(new PhpValue($i));
        }
        $filter = new EvenFilter();
        self::assertInstanceOf(GtkFilter::class, $filter);
        self::assertSame(GtkFilterMatch::Some, $filter->get_strictness());
        $model = new GtkFilterListModel($store, $filter);
        self::assertSame(3, $model->get_n_items());
        self::assertSame(6, $filter->calls, 'GTK matched every item through the PHP vfunc');
        $item = $model->get_item(0);
        self::assertInstanceOf(PhpValue::class, $item);
        self::assertSame(2, $item->get_value());
        self::assertTrue($filter->match(new PhpValue(8)), 'match() dispatches to the PHP override too');
        self::assertFalse($filter->match(new PhpValue(9)));
    }

    public function testConstructorArgumentsBecomeConstructProperties(): void
    {
        $box = new VBox('x');
        self::assertSame('x', $box->tag);
        self::assertSame(7, $box->get_spacing());
        self::assertSame(GtkOrientation::Vertical, $box->get_orientation());
        self::assertInstanceOf(GtkBox::class, $box);
    }

    public function testRenamedConstructorArgument(): void
    {
        // gtk_label_new(str) sets "label": gen/ctor-props.txt maps it for the subtype path
        $label = new HelloLabel('hello');
        self::assertSame('hello', $label->get_text());
        self::assertSame(HelloLabel::class, $label::class);
    }

    public function testRootSubtypeFollowsTheOwnershipRule(): void
    {
        $win = new TitledWindow();
        self::assertSame('titled', $win->get_title());
        $weak = \WeakReference::create($win);
        unset($win);
        $again = $weak->get();
        self::assertInstanceOf(TitledWindow::class, $again, 'the toplevel list holds it, the handle stays');
        $again->destroy();
        unset($again);
        self::assertNull($weak->get());
    }

    public function testIdentityAndClassSurviveGtkRoundTrip(): void
    {
        $box = new GtkBox(GtkOrientation::Horizontal, 0);
        $box->append(new Square());
        $child = $box->get_first_child();
        self::assertInstanceOf(Square::class, $child);
        self::assertSame([40, 40, -1, -1], $child->measure(GtkOrientation::Horizontal, -1));
    }

    public function testThrowingVfuncGoesThroughTheExceptionBoundary(): void
    {
        $w = new Throwing();
        $result = null;
        $seen = $this->captureHandlerException(function () use ($w, &$result): void {
            $result = $w->measure(GtkOrientation::Horizontal, -1);
        });
        self::assertSame([0, 0, -1, -1], $result, 'GTK got the fallback');
        self::assertNotNull($seen);
        self::assertSame(['measure failed', 'GtkWidget::vfunc_measure'], [$seen[0], $seen[1]]);
    }

    public function testPlainWrapperSubclassOfAFinalClassStillWorks(): void
    {
        // GtkLabel is final in GIR? No - GtkFilterListModel is: no GType for a PHP subclass of it,
        // `new` builds the native class and the PHP object is a plain wrapper subclass.
        $sub = new class (new GListStore(PhpValue::class), null) extends GtkFilterListModel {
            public int $x = 1;
        };
        self::assertSame(0, $sub->get_n_items());
        self::assertSame(1, $sub->x);
    }

    public function testSubclassOfAConcreteClassGetsItsOwnGType(): void
    {
        $b = new class extends GtkButton {
            public function vfunc_clicked(): void
            {
                $this->set_label('clicked from vfunc');
            }
        };
        $b->emit('clicked');
        self::assertSame('clicked from vfunc', $b->get_label(), 'the clicked class handler is the PHP method');
    }

    public function testGListModelImplementedInPhp(): void
    {
        // `implements GListModel` adds the GTK interface to the class' GType: GTK calls the PHP
        // get_item_type/get_n_items/get_item through generated thunks.
        $model = new ArrayModel();
        $model->push('a');
        $model->push('b');
        $sorted = new GtkSortListModel($model, null);
        self::assertSame(2, $sorted->get_n_items());
        self::assertGreaterThan(0, $model->calls, 'GTK asked the PHP model for its size');
        $item = $sorted->get_item(1);
        self::assertInstanceOf(PhpValue::class, $item);
        self::assertSame('b', $item->get_value());
        $model->push('c');
        self::assertSame(3, $sorted->get_n_items(), 'items-changed emitted from PHP reaches the wrapper');
        self::assertSame('GObject', $sorted->get_item_type(), 'a sort model reports GObject items');
        self::assertSame('PhpValue', $model->get_item_type());
    }

    public function testManySubclassesAllWrapBackToThemselves(): void
    {
        // The GType -> PHP class registry (core/subtype) is an append-only hash table read
        // without a lock, so what a chain of several nodes in one bucket does is worth a test:
        // register enough classes to fill every bucket a few times over and have GTK hand each
        // one back through wrap().
        $classes = [];
        for ($i = 0; $i < 600; $i++) {
            $short = 'Many_' . $i;
            eval("namespace PhpGtk4\\Tests\\Many; final class $short extends \\Gtk4\\GtkButton {}");
            $classes[] = 'PhpGtk4\\Tests\\Many\\' . $short;
        }

        $box = new GtkBox(GtkOrientation::Vertical, 0);
        foreach ($classes as $fqcn) {
            if (!is_a($fqcn, GtkButton::class, true)) {
                self::fail("$fqcn did not come out a GtkButton");
            }
            $box->append(new $fqcn());
        }
        $seen = [];
        for ($child = $box->get_first_child(); $child !== null; $child = $child->get_next_sibling()) {
            $seen[] = $child::class;
        }

        self::assertSame($classes, $seen, 'every PHP GType wrapped back to its own class');
    }

    public function testGTypeNameCollisionIsRefused(): void
    {
        // `Sub\Foo` and `Sub__Foo` both map to the GType name Php__...__Sub__Foo; the second class
        // must not silently reuse the first one's GType (and vfuncs).
        eval('namespace PhpGtk4\\Tests\\Coll; final class Sub__Foo extends \\Gtk4\\GtkButton {}');
        eval('namespace PhpGtk4\\Tests\\Coll\\Sub; final class Foo extends \\Gtk4\\GtkButton {}');
        $first = self::className('PhpGtk4\\Tests\\Coll\\Sub__Foo');
        $second = self::className('PhpGtk4\\Tests\\Coll\\Sub\\Foo');
        self::assertInstanceOf(GtkButton::class, new $first());
        $this->expectException(\Error::class);
        $this->expectExceptionMessage('already used by the PHP class');
        new $second();
    }
}
