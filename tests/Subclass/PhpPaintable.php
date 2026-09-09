<?php

declare(strict_types=1);

namespace PhpGtk4\Tests\Subclass;

use Gtk4\GdkPaintable;
use Gtk4\GdkRGBA;
use Gtk4\GdkSnapshot;
use Gtk4\GObject;
use Gtk4\GrapheneRect;
use Gtk4\GtkSnapshot;

/**
 * A GdkPaintable implemented in PHP: core/subtype adds the GDK interface to the class' GType
 * and routes every slot GTK asks about - size, flags, and the drawing itself - to these
 * methods. Drives tests/PhpPaintableTest.php.
 */
final class PhpPaintable extends GObject implements GdkPaintable
{
    /** @var list<string> slot names in call order */
    public array $calls = [];

    public function get_current_image(): GdkPaintable
    {
        $this->calls[] = 'get_current_image';

        return $this;   // already immutable: nothing here ever changes
    }

    public function get_flags(): int
    {
        $this->calls[] = 'get_flags';

        return 0;
    }

    public function get_intrinsic_aspect_ratio(): float
    {
        $this->calls[] = 'get_intrinsic_aspect_ratio';

        return 2.0;
    }

    public function get_intrinsic_height(): int
    {
        $this->calls[] = 'get_intrinsic_height';

        return 20;
    }

    public function get_intrinsic_width(): int
    {
        $this->calls[] = 'get_intrinsic_width';

        return 40;
    }

    public function snapshot(GdkSnapshot $snapshot, float $width, float $height): void
    {
        $this->calls[] = 'snapshot';
        if ($snapshot instanceof GtkSnapshot) {
            $snapshot->append_color(new GdkRGBA('#ff0000'), GrapheneRect::alloc()->init(0.0, 0.0, $width, $height));
        }
    }
}
