<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GObject;
use Gtk4\GtkWindow;

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
        // GdkDisplay has no PHP class yet -> falls back to Gtk4\GObject, not an exception.
        $display = $this->window()->get_property('display');
        self::assertInstanceOf(GObject::class, $display);
        self::assertSame(GObject::class, $display::class);
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
}
