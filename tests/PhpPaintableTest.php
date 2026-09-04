<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GdkPaintable;
use Gtk4\GtkBox;
use Gtk4\GtkOrientation;
use Gtk4\GtkPicture;
use Gtk4\GtkSnapshot;
use PhpGtk4\Tests\Subclass\PhpPaintable;

/**
 * A GdkPaintable written in PHP. Nothing here calls the interface methods directly - a
 * GtkPicture does, while it measures and draws - so what is under test is the generated
 * iface thunk that turns GDK's call into a PHP one.
 */
final class PhpPaintableTest extends GtkTestCase
{
    public function testThePhpObjectIsAPaintable(): void
    {
        $paintable = new PhpPaintable();

        self::assertInstanceOf(GdkPaintable::class, $paintable);
        self::assertSame([], $paintable->calls, 'nothing asked yet');
    }

    public function testAPictureAsksThePhpPaintableForItsSizeAndDrawing(): void
    {
        $paintable = new PhpPaintable();
        $picture = new GtkPicture();
        $picture->set_paintable($paintable);

        $box = new GtkBox(GtkOrientation::Vertical, 0);
        $box->append($picture);
        $window = $this->window();
        $window->set_child($box);
        $window->present();
        $box->allocate(80, 40);
        $box->snapshot_child($picture, new GtkSnapshot());

        self::assertContains('get_intrinsic_width', $paintable->calls);
        self::assertContains('get_intrinsic_height', $paintable->calls);
        self::assertContains('get_flags', $paintable->calls);
        self::assertContains('get_intrinsic_aspect_ratio', $paintable->calls, 'the picture sized it');
        self::assertContains('snapshot', $paintable->calls, 'GDK asked PHP to paint');
    }

    public function testCurrentImageGoesThroughThePhpSlot(): void
    {
        $paintable = new PhpPaintable();

        self::assertSame($paintable, $paintable->get_current_image());
        self::assertSame(['get_current_image'], $paintable->calls);
    }
}
