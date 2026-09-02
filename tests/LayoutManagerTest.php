<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GtkBaselinePosition;
use Gtk4\GtkBinLayout;
use Gtk4\GtkBox;
use Gtk4\GtkBoxLayout;
use Gtk4\GtkCenterLayout;
use Gtk4\GtkFixedLayout;
use Gtk4\GtkFixedLayoutChild;
use Gtk4\GtkGridLayout;
use Gtk4\GtkLabel;
use Gtk4\GtkLayoutChild;
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

    public function testAManagerAnswersWithItsOwnLayoutChild(): void
    {
        $box = new GtkBox(GtkOrientation::Horizontal, 0);
        $fixed = new GtkFixedLayout();
        $box->set_layout_manager($fixed);
        $kid = new GtkLabel('kid');
        $box->append($kid);

        $child = $fixed->get_layout_child($kid);
        self::assertInstanceOf(GtkFixedLayoutChild::class, $child);
        self::assertSame($kid, $child->get_child_widget());
        self::assertSame($fixed, $child->get_layout_manager());
    }

    public function testALayoutChildCannotBeBuiltFromPhp(): void
    {
        // A layout manager creates them. GTK keeps the manager and the child widget unowned in
        // construct-only properties, so one built from PHP dangles the moment PHP drops what it
        // was handed - get_layout_manager() then wraps freed memory (SIGSEGV).
        foreach ([GtkLayoutChild::class, GtkFixedLayoutChild::class] as $class) {
            self::assertFalse(new \ReflectionClass($class)->isInstantiable(), "$class is not PHP's to make");
        }
    }

    public function testRootAndUnrootFollowTheWidgetIntoAndOutOfAWindow(): void
    {
        // GTK calls them when the widget the manager sits on gains or loses a toplevel.
        $box = new GtkBox(GtkOrientation::Horizontal, 0);
        $layout = new StackLayout();
        $box->set_layout_manager($layout);
        self::assertSame(0, $layout->rooted);

        $win = $this->window();
        $win->set_child($box);
        self::assertSame(1, $layout->rooted, 'vfunc_root() ran');

        $win->set_child(null);
        self::assertSame(0, $layout->rooted, 'vfunc_unroot() ran');
    }

    public function testTheNativeMeasureSlotIsReachableForParentChaining(): void
    {
        // GtkBinLayout's own measure through parent:: - the native vfunc_*() methods exist so a
        // PHP manager can chain instead of reimplementing GTK's layout.
        $box = new GtkBox(GtkOrientation::Horizontal, 0);
        $inner = new GtkLabel('measured');
        $box->append($inner);
        $layout = new GtkBinLayout();
        $box->set_layout_manager($layout);

        $this->expectException(\LogicException::class);
        $layout->vfunc_measure($box, GtkOrientation::Horizontal, -1);
    }

    public function testBoxLayoutCarriesWhatGtkBoxWouldHave(): void
    {
        // The manager behind every GtkBox: the box's own spacing/homogeneous/baseline live here.
        $layout = new GtkBoxLayout(GtkOrientation::Vertical);
        self::assertSame(GtkOrientation::Vertical, $layout->get_orientation());
        $layout->set_orientation(GtkOrientation::Horizontal);
        self::assertSame(GtkOrientation::Horizontal, $layout->get_orientation());

        $layout->set_spacing(11);
        self::assertSame(11, $layout->get_spacing());
        $layout->set_homogeneous(true);
        self::assertTrue($layout->get_homogeneous());
        $layout->set_baseline_child(1);
        self::assertSame(1, $layout->get_baseline_child());
        $layout->set_baseline_position(GtkBaselinePosition::Bottom);
        self::assertSame(GtkBaselinePosition::Bottom, $layout->get_baseline_position());
    }

    public function testBoxLayoutOnAPlainWidgetLaysItOut(): void
    {
        $box = new GtkBox(GtkOrientation::Horizontal, 0);
        $box->set_layout_manager(new GtkBoxLayout(GtkOrientation::Horizontal));
        $one = new GtkLabel('one');
        $two = new GtkLabel('two');
        $box->append($one);
        $box->append($two);

        $win = $this->window();
        $win->set_child($box);
        $win->present();
        $box->allocate(200, 40);

        self::assertGreaterThan(0, $one->get_width());
        self::assertGreaterThan(0, $two->get_width());
    }

    public function testCenterLayoutHoldsThreeSlots(): void
    {
        $layout = new GtkCenterLayout();
        self::assertNull($layout->get_start_widget());
        self::assertNull($layout->get_center_widget());
        self::assertNull($layout->get_end_widget());

        $start = new GtkLabel('start');
        $centre = new GtkLabel('centre');
        $end = new GtkLabel('end');
        $layout->set_start_widget($start);
        $layout->set_center_widget($centre);
        $layout->set_end_widget($end);
        self::assertSame($start, $layout->get_start_widget());
        self::assertSame($centre, $layout->get_center_widget());
        self::assertSame($end, $layout->get_end_widget());

        $layout->set_orientation(GtkOrientation::Vertical);
        self::assertSame(GtkOrientation::Vertical, $layout->get_orientation());
        $layout->set_baseline_position(GtkBaselinePosition::Top);
        self::assertSame(GtkBaselinePosition::Top, $layout->get_baseline_position());

        $layout->set_start_widget(null);
        self::assertNull($layout->get_start_widget());
    }

    public function testGridLayoutCarriesTheGridsSpacing(): void
    {
        $layout = new GtkGridLayout();
        $layout->set_column_spacing(7);
        $layout->set_row_spacing(9);
        self::assertSame(7, $layout->get_column_spacing());
        self::assertSame(9, $layout->get_row_spacing());

        $layout->set_column_homogeneous(true);
        $layout->set_row_homogeneous(true);
        self::assertTrue($layout->get_column_homogeneous());
        self::assertTrue($layout->get_row_homogeneous());

        $layout->set_baseline_row(2);
        self::assertSame(2, $layout->get_baseline_row());
        $layout->set_row_baseline_position(2, GtkBaselinePosition::Center);
        self::assertSame(GtkBaselinePosition::Center, $layout->get_row_baseline_position(2));
    }

    public function testWidgetIsAGtkWidgetSubclass(): void
    {
        $layout = new StackLayout();
        $box = new GtkBox(GtkOrientation::Horizontal, 0);
        $box->set_layout_manager($layout);
        self::assertInstanceOf(GtkWidget::class, $layout->get_widget());
    }
}
