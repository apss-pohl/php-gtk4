<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GCancellable;

/** src/Gio/GCancellable.cpp (generated): cancel / is_cancelled / reset + the `cancelled` signal. */
final class GCancellableTest extends GtkTestCase
{
    public function testCancelAndReset(): void
    {
        $c = new GCancellable();
        self::assertFalse($c->is_cancelled());
        $c->connect('cancelled', $this->latch());
        $c->cancel();
        self::assertTrue($c->is_cancelled());
        self::assertTrue($this->latched(), 'cancel() emits `cancelled`');
        $c->reset();
        self::assertFalse($c->is_cancelled());
    }

    public function testGetCurrentIsNullOutsideAnOperation(): void
    {
        self::assertNull(GCancellable::get_current());
    }
}
