<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GtkLabel;
use Gtk4\GtkRequisition;

/** src/Gtk/GtkRequisition.cpp: the first boxed record generated from GIR (core/boxed). */
final class GtkRequisitionTest extends GtkTestCase
{
    public function testFieldsAreProperties(): void
    {
        $r = new GtkRequisition();
        self::assertSame(0, $r->width);
        $r->width = 12;
        $r->height = 34;
        self::assertSame([12, 34], [$r->width, $r->height]);
    }

    public function testValueSemantics(): void
    {
        $a = new GtkRequisition();
        $a->width = 5;
        $b = clone $a;
        $b->width = 6;
        self::assertSame(5, $a->width, 'clone copies the struct');
        $c = new GtkRequisition();
        $c->width = 5;
        self::assertTrue($a == $c, 'compared by value');
        self::assertFalse($a == $b);
    }

    public function testCallerAllocatedOutsAreReturned(): void
    {
        // gtk_widget_get_preferred_size(widget, &minimum, &natural): two caller-allocated
        // records come back as a list, never through by-reference parameters.
        $label = new GtkLabel('preferred size');
        [$min, $nat] = $label->get_preferred_size();
        self::assertInstanceOf(GtkRequisition::class, $min);
        self::assertInstanceOf(GtkRequisition::class, $nat);
        self::assertGreaterThan(0, $nat->width);
        self::assertGreaterThanOrEqual($min->width, $nat->width);
    }
}
