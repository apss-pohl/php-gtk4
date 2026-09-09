<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\CairoContext;
use Gtk4\CairoSurface;
use Gtk4\GdkTexture;
use Gtk4\GtkDrawingArea;

/**
 * Painting an image into a cairo context. GTK 3 had gdk_cairo_set_source_pixbuf(); GTK 4 has
 * neither that call nor GdkPixbuf, so GdkTexture::download() (GDK writes ARGB32, which is
 * cairo's image layout) plus CairoContext::set_source_surface() is the whole path.
 */
final class CairoSurfaceTest extends GtkTestCase
{
    /** An 8x8 checkerboard PNG built by hand, so the test needs no fixture file and no GD. */
    private static function checkerPng(int $size = 8): string
    {
        $raw = '';
        for ($y = 0; $y < $size; $y++) {
            $raw .= "\x00";
            for ($x = 0; $x < $size; $x++) {
                $raw .= (($x + $y) % 2) === 0 ? "\x35\x84\xe4\xff" : "\xfd\xfd\xfd\xff";
            }
        }
        $chunk = static fn(string $type, string $data): string
        => pack('N', strlen($data)) . $type . $data . pack('N', crc32($type . $data));

        return "\x89PNG\r\n\x1a\n"
            . $chunk('IHDR', pack('NNCCCCC', $size, $size, 8, 6, 0, 0, 0))
            . $chunk('IDAT', (string) gzcompress($raw, 9))
            . $chunk('IEND', '');
    }

    public function testDownloadAnswersWithASurfaceOfTheTexturesSize(): void
    {
        $texture = GdkTexture::new_from_bytes(self::checkerPng());
        $surface = $texture->download();
        self::assertInstanceOf(CairoSurface::class, $surface);
        self::assertSame($texture->get_width(), $surface->get_width());
        self::assertSame($texture->get_height(), $surface->get_height());
    }

    public function testEachDownloadIsItsOwnSurface(): void
    {
        $texture = GdkTexture::new_from_bytes(self::checkerPng());
        self::assertNotSame($texture->download(), $texture->download());
    }

    public function testTheSurfaceSurvivesTheTextureBeingDropped(): void
    {
        $texture = GdkTexture::new_from_bytes(self::checkerPng());
        $surface = $texture->download();
        unset($texture);
        self::assertSame(8, $surface->get_width());  // the handle owns its own reference
    }

    public function testWriteToPngRoundTripsThroughGdkTexture(): void
    {
        $path = sys_get_temp_dir() . '/php-gtk4-surface-' . getmypid() . '.png';
        try {
            GdkTexture::new_from_bytes(self::checkerPng())->download()->write_to_png($path);
            self::assertFileExists($path);
            $back = GdkTexture::new_from_filename($path);
            self::assertSame(8, $back->get_width());
            self::assertSame(8, $back->get_height());
        } finally {
            @unlink($path);
        }
    }

    public function testWriteToPngRefusesAnUnwritablePath(): void
    {
        $surface = GdkTexture::new_from_bytes(self::checkerPng())->download();
        $this->expectException(\Error::class);
        $surface->write_to_png('/nonexistent-directory-php-gtk4/out.png');
    }

    public function testWriteToPngRefusesAFilenameWithANulByte(): void
    {
        $surface = GdkTexture::new_from_bytes(self::checkerPng())->download();
        $this->expectException(\ValueError::class);
        $surface->write_to_png("/tmp/a\0b.png");
    }

    public function testADrawFuncCanPaintTheSurface(): void
    {
        $surface = GdkTexture::new_from_bytes(self::checkerPng())->download();
        $area = new GtkDrawingArea();
        $area->set_content_width(40);
        $area->set_content_height(40);
        $painted = false;
        $area->set_draw_func(function (
            GtkDrawingArea $self,
            CairoContext $cr,
            int $width,
            int $height,
        ) use (
            $surface,
            &$painted
        ): void {
            $cr->set_source_surface($surface, 0.0, 0.0);
            $cr->rectangle(0, 0, $surface->get_width(), $surface->get_height());
            $cr->fill();
            $painted = true;
        });

        $win = $this->window();
        $win->set_child($area);
        $win->present();
        for ($i = 0; $i < 200 && !$painted; $i++) {
            \Gtk4\GLib::main_context_iteration(false);
        }
        self::assertTrue($painted, 'the draw func ran and set_source_surface() did not abort');
    }

    public function testSetSourceSurfaceRejectsSomethingElse(): void
    {
        $area = new GtkDrawingArea();
        $caught = null;
        /** @var callable(): CairoSurface $wrong */
        $wrong = self::opaque(static fn(): \stdClass => new \stdClass());
        $area->set_draw_func(function (
            GtkDrawingArea $self,
            CairoContext $cr,
            int $width,
            int $height,
        ) use (
            &$caught,
            $wrong
        ): void {
            try {
                $cr->set_source_surface($wrong());
            } catch (\TypeError $e) {
                $caught = $e;
            }
        });
        $win = $this->window();
        $win->set_child($area);
        $win->present();
        for ($i = 0; $i < 200 && $caught === null; $i++) {
            \Gtk4\GLib::main_context_iteration(false);
        }
        self::assertInstanceOf(\TypeError::class, $caught);
    }

    public function testTheHandleIsNotCloneable(): void
    {
        // A cairo surface is not a value type (core/fundamental clears clone_obj).
        $surface = GdkTexture::new_from_bytes(self::checkerPng())->download();
        $this->expectException(\Error::class);
        $clone = clone $surface;
        unset($clone);
    }
}
