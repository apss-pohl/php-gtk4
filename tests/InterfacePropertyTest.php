<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GtkOrientable;
use Gtk4\GtkOrientation;
use PhpGtk4\Tests\Subclass\PhpOrientable;

/**
 * The properties of an interface a PHP class implements. GObject requires the class to
 * override every one of them (it warns at class_init about each it does not find), and
 * php-gtk4 routes them to the PHP accessors of the same name - a written value reaches
 * set_x(), a read is what get_x() answers. GtkOrientable is the interface that is nothing but
 * a property.
 */
final class InterfacePropertyTest extends GtkTestCase
{
    public function testThePhpWidgetIsOrientable(): void
    {
        self::assertInstanceOf(GtkOrientable::class, new PhpOrientable());
    }

    public function testAWriteFromCReachesTheSetter(): void
    {
        $widget = new PhpOrientable();

        $widget->set_property('orientation', GtkOrientation::Vertical);

        self::assertSame(['set_orientation'], $widget->calls);
        self::assertSame(GtkOrientation::Vertical, $widget->orientation);
    }

    public function testAReadFromCIsWhatTheGetterAnswers(): void
    {
        $widget = new PhpOrientable();
        $widget->orientation = GtkOrientation::Vertical;

        self::assertSame(GtkOrientation::Vertical, $widget->get_property('orientation'));
        self::assertSame(['get_orientation'], $widget->calls);
    }

    /** `$widget->orientation` is the PHP property, not the GObject one: no accessor runs. */
    public function testOnThePhpSideItIsAPlainProperty(): void
    {
        $widget = new PhpOrientable();

        $widget->orientation = GtkOrientation::Vertical;

        self::assertSame(GtkOrientation::Vertical, $widget->orientation);
        self::assertSame([], $widget->calls);
    }

    /** A native orientable keeps GObject property semantics: no PHP accessor is involved. */
    public function testANativeOrientableIsUntouched(): void
    {
        $box = new \Gtk4\GtkBox(GtkOrientation::Horizontal, 0);

        $box->orientation = GtkOrientation::Vertical;

        self::assertSame(GtkOrientation::Vertical, $box->get_orientation());
    }
}
