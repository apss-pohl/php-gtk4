<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GtkBinLayout;
use Gtk4\GtkBox;
use Gtk4\GtkBoxLayout;
use Gtk4\GtkLabel;
use Gtk4\GtkLayoutManager;
use Gtk4\GtkOrientation;
use Gtk4\GtkSizeRequestMode;
use Gtk4\GtkWidget;
use PhpGtk4\Tests\Subclass\StackLayout;

/**
 * The way a PHP class controls a widget's allocation. GTK 4 hands a widget that has a layout
 * manager to that manager and never reaches WidgetClass.size_allocate/measure (VfuncTest pins
 * that), so a GtkBox subclass cannot lay itself out - a GtkLayoutManager subclass can, and
 * GtkWidget::set_layout_manager() is what installs it.
 */
final class LayoutManagerTest extends GtkTestCase
{
    public function testAWidgetReportsTheManagerItWasGiven(): void
    {
        $box = new GtkBox(GtkOrientation::Horizontal, 0);
        self::assertInstanceOf(GtkBoxLayout::class, $box->get_layout_manager(), 'GtkBox is born with one');

        $layout = new StackLayout();
        $box->set_layout_manager($layout);
        self::assertSame($layout, $box->get_layout_manager());
        self::assertSame($box, $layout->get_widget());

        $box->set_layout_manager(null);
        self::assertNull($box->get_layout_manager());
    }

    public function testGtkDrivesAPhpLayoutManagerThroughItsVfuncs(): void
    {
        $box = new GtkBox(GtkOrientation::Horizontal, 0);
        $layout = new StackLayout();
        $box->set_layout_manager($layout);
        foreach (['one', 'two', 'three'] as $text) {
            $box->append(new GtkLabel($text));
        }

        $win = $this->window();
        $win->set_child($box);
        $win->present();
        $box->allocate(300, 150);  // GTK's own allocation pass, driven directly

        self::assertGreaterThan(0, $layout->measurements, 'vfunc_measure() ran');
        self::assertGreaterThan(0, $layout->allocations, 'vfunc_allocate() ran');
        self::assertCount(3, $layout->placed);
        // Every child got the full width and its own slice of the height, in order.
        self::assertSame(0, $layout->placed[0][3]);
        self::assertSame($layout->placed[1][1], $layout->placed[1][3]);
        self::assertSame(2 * $layout->placed[2][1], $layout->placed[2][3]);
        self::assertSame(300, $layout->placed[0][0]);
    }

    public function testAllocatePlacesTheChildWhereTheManagerAsked(): void
    {
        $box = new GtkBox(GtkOrientation::Horizontal, 0);
        $layout = new StackLayout();
        $box->set_layout_manager($layout);
        $label = new GtkLabel('one');
        $box->append($label);

        $win = $this->window();
        $win->set_child($box);
        $win->present();
        $box->allocate(200, 80);

        self::assertSame(200, $label->get_width());
        self::assertSame(80, $label->get_height());
    }

    public function testAllocateRejectsASizeThatIsNotAnInt(): void
    {
        $label = new GtkLabel('x');
        $this->expectException(\ValueError::class);
        $label->allocate(PHP_INT_MAX, 10);
    }

    public function testTheManagerAnswersTheRequestModeItsSubclassReturns(): void
    {
        $layout = new StackLayout();
        $box = new GtkBox(GtkOrientation::Horizontal, 0);
        $box->set_layout_manager($layout);
        self::assertSame(GtkSizeRequestMode::ConstantSize, $layout->get_request_mode());
    }

    public function testAConcreteManagerCanBeSwappedIn(): void
    {
        // GtkBinLayout gives the whole allocation to every child - the manager GtkWindow uses.
        $box = new GtkBox(GtkOrientation::Vertical, 0);
        $box->set_layout_manager(new GtkBinLayout());
        $label = new GtkLabel('filling');
        $box->append($label);

        $win = $this->window();
        $win->set_child($box);
        $win->present();
        $box->allocate(240, 120);

        self::assertSame(240, $label->get_width());
        self::assertSame(120, $label->get_height());
    }

    public function testTheNativeVfuncRefusesANativeManager(): void
    {
        // parent::vfunc_allocate() is for chaining from a PHP subclass, not a way into the slot.
        $layout = new GtkBoxLayout(GtkOrientation::Horizontal);
        $this->expectException(\LogicException::class);
        $layout->vfunc_allocate($this->window(), 10, 10, -1);
    }

    public function testAbstractManagerRefusesDirectConstruction(): void
    {
        $this->expectException(\Error::class);
        new GtkLayoutManager();
    }

    public function testMeasureReportsWhatTheSubclassReturns(): void
    {
        $box = new GtkBox(GtkOrientation::Horizontal, 0);
        $layout = new StackLayout();
        $box->set_layout_manager($layout);
        $box->append(new GtkLabel('one'));
        $box->append(new GtkLabel('two'));

        [$min, $nat] = $box->measure(GtkOrientation::Vertical, -1);
        self::assertSame($min, $nat, 'StackLayout reports one size for both');
        self::assertGreaterThan(0, $nat);
        self::assertGreaterThan(0, $layout->measurements);
    }

    public function testRequestModeRefusesAManagerThatIsNotSetOnAWidget(): void
    {
        // GTK reads the mode off the widget: without one it is a CRITICAL and the first enum
        // value, which says nothing to PHP.
        $this->expectException(\LogicException::class);
        new GtkBinLayout()->get_request_mode();
    }

    public function testLayoutChildRefusesAChildWithoutAParent(): void
    {
        // GTK finds the manager through the child's parent and answers NULL without one - a
        // value the declared GtkLayoutChild return type does not allow.
        $box = new GtkBox(GtkOrientation::Horizontal, 0);
        $box->set_layout_manager(new StackLayout());
        $layout = $box->get_layout_manager();
        self::assertNotNull($layout);
        $this->expectException(\LogicException::class);
        $layout->get_layout_child(new GtkLabel('orphan'));
    }

    public function testAManagerWithoutAWidgetHasNone(): void
    {
        self::assertNull(new StackLayout()->get_widget());
    }

    public function testLayoutChangedIsSafeWithoutAWidget(): void
    {
        // GTK returns early when there is no widget to resize - not a precondition failure.
        $layout = new StackLayout();
        $layout->layout_changed();
        self::assertNull($layout->get_widget());
    }

    public function testWidgetIsAGtkWidgetSubclass(): void
    {
        $layout = new StackLayout();
        $box = new GtkBox(GtkOrientation::Horizontal, 0);
        $box->set_layout_manager($layout);
        self::assertInstanceOf(GtkWidget::class, $layout->get_widget());
    }
}
