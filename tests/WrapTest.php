<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GdkDisplay;
use Gtk4\GObject;
use Gtk4\Gtk;
use Gtk4\GtkBox;
use Gtk4\GtkBuilder;
use Gtk4\GtkButton;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkScale;
use Gtk4\GtkStack;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use PhpGtk4\Tests\Subclass\DestructCountingButton;
use PhpGtk4\Tests\Subclass\DestructCountingStack;
use PhpGtk4\Tests\Subclass\StatefulButton;

/** src/core/wrap: object handles, identity, ownership. */
final class WrapTest extends GtkTestCase
{
    public function testSameCObjectYieldsSamePhpObject(): void
    {
        $a = $this->window();
        $b = $this->window();
        $a->set_property('transient-for', $b);
        self::assertSame($b, $a->get_property('transient-for'), 'wrap() must return the existing handle');
    }

    public function testObjectPropertyIsWrappedAsNearestRegisteredClass(): void
    {
        // The display is a backend subclass (GdkX11Display, GdkWaylandDisplay) that has no
        // PHP class; wrap() walks the GType parents up to the registered GdkDisplay.
        $display = $this->window()->get_property('display');
        self::assertInstanceOf(GdkDisplay::class, $display);
        self::assertSame(GdkDisplay::class, $display::class);
    }

    public function testUnregisteredTypeFallsBackToGObject(): void
    {
        // GtkBuilder instantiates any GType GTK knows, bound here or not - the only way to get a
        // handle on an unregistered type without one existing in the binding. The candidates are
        // tried in order so that a later wave binding one does not leave the test with nothing.
        $object = self::builtFromUi(['GtkPrintSettings', 'GtkPageSetup', 'GtkTextChildAnchor']);
        self::assertInstanceOf(GObject::class, $object);
        self::assertSame(GObject::class, $object::class, 'the nearest registered ancestor is GObject');
        // A GObject handle is a handle like any other: identity holds while PHP holds it.
        self::assertSame(spl_object_id($object), spl_object_id($object));
    }

    public function testUnregisteredTypeFallsBackToItsNearestRegisteredAncestor(): void
    {
        // Not GObject this time: an unbound *widget* answers with the nearest class up its chain.
        $object = self::builtFromUi(['GtkMediaControls', 'GtkLevelBar', 'GtkVideo']);
        self::assertInstanceOf(GtkWidget::class, $object);
        self::assertSame(GtkWidget::class, $object::class);
    }

    /**
     * The first of $types php-gtk4 does not bind, instantiated through a .ui document.
     *
     * @param list<string> $types
     */
    private static function builtFromUi(array $types): GObject
    {
        foreach ($types as $type) {
            if (class_exists('Gtk4\\' . $type)) {
                continue;   // bound by a later wave: try the next candidate
            }
            $builder = new GtkBuilder();
            $builder->add_from_string("<interface><object class=\"$type\" id=\"x\"/></interface>");
            $object = $builder->get_object('x');
            self::assertNotNull($object);

            return $object;
        }
        self::fail('every candidate type is bound now - pick one php-gtk4 still does not bind');
    }

    public function testNullObjectProperty(): void
    {
        self::assertNull($this->window()->get_property('transient-for'));
        self::assertNull($this->window()->get_property('child'));
    }

    public function testUnwrapRejectsNonGObject(): void
    {
        $this->expectExceptionMessage('expected a GObject instance');
        $this->window()->set_property('transient-for', new \stdClass());
    }

    public function testUnwrapRejectsClosure(): void
    {
        $this->expectExceptionMessage('expected a GObject instance');
        $this->window()->set_property('transient-for', fn() => 1);
    }

    public function testUnwrapRejectsScalar(): void
    {
        $this->expectExceptionMessage('expected a GObject instance');
        $this->window()->set_property('transient-for', 'GtkWindow');
    }

    public function testUnwrapRejectsWrongGType(): void
    {
        // 'display' expects a GdkDisplay; a GtkWindow is a GObject but not that.
        $this->expectExceptionMessageMatches('/expected GdkDisplay, got GtkWindow/');
        $this->window()->set_property('display', $this->window());
    }

    public function testCloneIsRefused(): void
    {
        // clone_obj handler is NULL -> "Trying to clone an uncloneable object of class ...".
        $this->expectExceptionMessageMatches('/uncloneable|cannot be cloned/');
        $w = $this->window();
        $c = clone $w;
        unset($c);
    }

    public function testHandleOutlivesGtkWindowDestroy(): void
    {
        $w = new GtkWindow();
        $w->set_title('x');
        $w->present();
        $w->destroy();  // GTK drops its toplevel ref; we still hold ours
        self::assertFalse($w->get_property('visible'));
        self::assertSame('x', $w->get_title(), 'C object must stay alive while PHP holds a handle');
    }

    public function testDestroySignalFiresWhenLastHandleIsReleased(): void
    {
        $w = new GtkWindow();
        $w->destroy();
        $w->connect('destroy', $this->latch());
        self::assertFalse($this->latched(), 'dispose must not run while a handle exists');
        unset($w);
        self::assertTrue($this->latched(), 'releasing the last handle finalizes the GObject');
    }

    public function testPlainGObjectDiesWithItsLastHandle(): void
    {
        // Constructors adopt the initial reference; a leaked ref here would keep
        // every GtkApplication/GSimpleAction/GListStore/PhpValue alive forever.
        $payload = new \stdClass();
        $weak = \WeakReference::create($payload);
        $item = new \Gtk4\PhpValue($payload);
        unset($payload, $item);
        self::assertNull($weak->get(), 'PhpValue finalized -> payload released');

        $store = new \Gtk4\GListStore();
        $destroyed = $this->latch();
        $store->connect('notify::n-items', $destroyed);
        $store->connect('items-changed', $this->latch());
        unset($store);
        self::assertFalse($this->latched(), 'no emission, and no crash finalizing with handlers attached');
    }

    public function testHandleObtainedFromSignalIsTheOriginal(): void
    {
        $w = $this->window();
        $seen = null;
        $w->connect('notify::title', function (GObject $obj) use (&$seen): void {
            $seen = $obj;
        });
        $w->set_title('t');
        self::assertSame($w, $seen);
    }

    // ---- toggle reference: GTK holding the C object keeps the PHP handle (and its state)

    public function testHandleSurvivesWhileGtkHoldsTheObject(): void
    {
        $box = new GtkBox(GtkOrientation::Horizontal, 0);
        $box->append(new StatefulButton());  // no PHP reference kept
        $child = $box->get_first_child();
        self::assertInstanceOf(StatefulButton::class, $child, 'the PHP subclass, not a fresh base wrapper');
        self::assertSame(42, $child->state, 'subclass state survived the script dropping its reference');
    }

    public function testHandleIsReleasedWhenGtkLetsGo(): void
    {
        $box = new GtkBox(GtkOrientation::Horizontal, 0);
        $b = new DestructCountingButton();
        DestructCountingButton::$destructed = 0;
        $box->append($b);
        unset($b);
        self::assertSame(0, DestructCountingButton::$destructed, 'held by the box');
        $child = $box->get_first_child();
        self::assertInstanceOf(GtkButton::class, $child);
        $box->remove($child);
        unset($child);
        self::assertSame(1, DestructCountingButton::$destructed, 'removed from the box = last reference gone = freed');
        self::assertNull($box->get_first_child());
    }

    public function testHoldIsTakenAndReleasedRepeatedly(): void
    {
        $box = new GtkBox(GtkOrientation::Horizontal, 0);
        $b = new StatefulButton();
        $b->state = 0;
        for ($i = 0; $i < 3; $i++) {
            $box->append($b);
            $box->remove($b);
            $b->state++;
        }
        self::assertSame(3, $b->state);
        $weak = \WeakReference::create($b);
        unset($b);
        self::assertNull($weak->get(), 'nothing holds it once GTK and the script let go');
    }

    public function testWindowHandleHeldByToplevelListUntilDestroy(): void
    {
        $w = new GtkWindow();
        $w->set_title('m');
        $weak = \WeakReference::create($w);
        unset($w);
        $again = $weak->get();
        self::assertInstanceOf(GtkWindow::class, $again, "GTK's toplevel list holds the window, so the handle stays");
        self::assertSame('m', $again->get_title());
        $again->destroy();
        unset($again);
        self::assertNull($weak->get(), 'destroy() dropped the toplevel ref; last PHP ref frees it');
    }

    public function testDisposedHandleThrowsInsteadOfTouchingTheGuttedObject(): void
    {
        // GTK 4's gtk_window_destroy() only drops GTK's reference (testHandleOutlivesGtkWindowDestroy);
        // disposal under a live handle comes from C owners, so it is driven from C here.
        $b = new GtkButton();
        $other = $this->window();
        Gtk::testing_run_dispose($b);
        try {
            $b->set_label('x');
            self::fail('a method on a disposed handle must throw');
        } catch (\Error $e) {
            self::assertStringContainsString('disposed GObject', $e->getMessage());
        }
        try {
            $other->set_child($b);
            self::fail('a disposed handle as an argument must throw');
        } catch (\Error $e) {
            self::assertStringContainsString('was disposed', $e->getMessage());
        }
        // The C object is still allocated (PHP holds it): dropping the handle finalizes it
        // cleanly - dispose ran once already and runs again on the last unref.
        // $e first: the exception set_child() threw holds $b in its trace arguments unless
        // zend.exception_ignore_args is on. Distro and setup-php builds set it in php.ini,
        // a hand-built PHP takes the compiled default (off) - and then the handle stays
        // alive and the weak reference below still resolves.
        unset($e);
        $weak = \WeakReference::create($b);
        unset($b);
        self::assertNull($weak->get());
    }

    // ------------------------------------------------------------------ owner-holding handles

    /**
     * `gtk_stack_get_pages()` returns a model that keeps a bare pointer to the stack, and the
     * stack keeps only a weak one back - so the model outlives its stack by construction and
     * used to read freed memory (`get_n_items()` answered 497, and ASan called it a SEGV).
     * `object_hold_owner()` makes the returned handle keep the stack's handle alive.
     */
    public function testModelHandedOutByAStackOutlivesTheStack(): void
    {
        $stack = new GtkStack();
        $stack->add_child(new GtkLabel('one'));
        $pages = $stack->get_pages();

        unset($stack);
        gc_collect_cycles();

        self::assertSame(1, $pages->get_n_items());
    }

    /** The whole point is that the owner is *not* freed early - and is freed once nothing holds it. */
    public function testTheHeldOwnerIsReleasedWithTheHandleThatHeldIt(): void
    {
        DestructCountingStack::$destructed = 0;
        $stack = new DestructCountingStack();
        $pages = $stack->get_pages();

        unset($stack);
        gc_collect_cycles();
        self::assertSame(0, DestructCountingStack::$destructed, 'the model still holds the stack');

        unset($pages);
        gc_collect_cycles();
        self::assertSame(1, DestructCountingStack::$destructed, 'and lets go with the last handle');
    }

    /**
     * A composite widget's `get_first_child()` hands out a private child that measures through
     * its parent's struct; measuring it after the parent was freed was a SEGV.
     */
    public function testInternalChildOfACompositeWidgetOutlivesIt(): void
    {
        $scale = new GtkScale(GtkOrientation::Horizontal);
        $child = $scale->get_first_child();
        self::assertInstanceOf(GtkWidget::class, $child);

        unset($scale);
        gc_collect_cycles();

        self::assertCount(2, $child->get_preferred_size());
    }

    /** The same C object is one handle, so asking twice does not stack owners up. */
    public function testAskingTwiceAnswersTheSameHandle(): void
    {
        $stack = new GtkStack();

        self::assertSame($stack->get_pages(), $stack->get_pages());
    }

    /**
     * The owner reference lives between the handles, not between the GObjects, so a subclass
     * that stores what it got from itself is an ordinary PHP cycle the collector can break -
     * a GObject-level back-reference would have been an uncollectable leak.
     */
    public function testAHandleThatStoresWhatItGotFromItselfIsCollectable(): void
    {
        DestructCountingStack::$destructed = 0;
        $stack = new DestructCountingStack();
        $stack->kept = $stack->get_pages();   // stack -> pages -> stack

        unset($stack);
        gc_collect_cycles();

        self::assertSame(1, DestructCountingStack::$destructed);
    }
}
