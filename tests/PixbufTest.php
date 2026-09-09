<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GdkColorspace;
use Gtk4\GdkInterpType;
use Gtk4\GdkMemoryFormat;
use Gtk4\GdkMemoryTexture;
use Gtk4\GdkPixbuf;
use Gtk4\GdkPixbufFormat;
use Gtk4\GdkPixbufLoader;
use Gtk4\GdkPixbufRotation;
use Gtk4\GdkTexture;
use Gtk4\GdkTextureDownloader;
use Gtk4\GError;

/**
 * GdkPixbuf, the image library GTK 4 still ships: decoding any format through a loader, pixel
 * access as bytes, scaling, rotating, compositing, encoding. The direct pixbuf <-> texture
 * bridge is deprecated in GTK 4.12 and not bound; pixels cross through GdkMemoryTexture (bytes
 * in) and GdkTextureDownloader (bytes out), which this test drives both ways.
 */
final class PixbufTest extends GtkTestCase
{
    private static function red(int $width, int $height): GdkPixbuf
    {
        $loader = new GdkPixbufLoader();
        $loader->write_bytes(PngFixture::red($width, $height));
        $loader->close();
        return $loader->get_pixbuf() ?? throw new \RuntimeException('the fixture is a PNG');
    }

    public function testLoaderDecodesBytesAndKnowsTheFormat(): void
    {
        $loader = new GdkPixbufLoader();
        $loader->write_bytes(PngFixture::red(4, 3));
        $loader->close();
        $pixbuf = $loader->get_pixbuf();
        self::assertInstanceOf(GdkPixbuf::class, $pixbuf);
        self::assertSame([4, 3], [$pixbuf->get_width(), $pixbuf->get_height()]);
        self::assertTrue($pixbuf->get_has_alpha());
        self::assertSame(4, $pixbuf->get_n_channels());
        self::assertSame(GdkColorspace::Rgb, $pixbuf->get_colorspace());
        self::assertSame(8, $pixbuf->get_bits_per_sample());
        $format = $loader->get_format();
        self::assertInstanceOf(GdkPixbufFormat::class, $format);
        self::assertSame('png', $format->get_name());
        self::assertTrue($format->is_writable());
        self::assertContains('image/png', $format->get_mime_types());
    }

    public function testPixelsAreBytes(): void
    {
        $pixbuf = self::red(2, 2);
        $bytes = $pixbuf->read_pixel_bytes();
        self::assertSame(2 * 2 * 4, strlen($bytes));
        self::assertSame("\xff\x00\x00\xff", substr($bytes, 0, 4), 'RGBA, red, opaque');
        self::assertSame(8, $pixbuf->get_rowstride());

        $blank = new GdkPixbuf(GdkColorspace::Rgb, false, 8, 3, 2);
        $blank->fill(0x00ff00ff);
        self::assertSame("\x00\xff\x00", substr($blank->read_pixel_bytes(), 0, 3), 'fill() takes 0xRRGGBBAA');
        $again = GdkPixbuf::new_from_bytes($bytes, GdkColorspace::Rgb, true, 8, 2, 2, 8);
        self::assertSame($bytes, $again->read_pixel_bytes());
    }

    public function testTransformsAnswerWithNewPixbufs(): void
    {
        $pixbuf = self::red(4, 2);
        $scaled = $pixbuf->scale_simple(8, 4, GdkInterpType::Bilinear);
        self::assertNotNull($scaled);
        self::assertSame([8, 4], [$scaled->get_width(), $scaled->get_height()]);
        self::assertSame(4, $pixbuf->get_width(), 'the original is untouched');
        $rotated = $pixbuf->rotate_simple(GdkPixbufRotation::Counterclockwise);
        self::assertNotNull($rotated);
        self::assertSame([2, 4], [$rotated->get_width(), $rotated->get_height()]);
        $flipped = $pixbuf->flip(true);
        self::assertNotNull($flipped);
        self::assertSame($pixbuf->read_pixel_bytes(), $flipped->read_pixel_bytes(), 'a solid colour flips onto itself');
        $sub = $pixbuf->new_subpixbuf(1, 0, 2, 2);
        self::assertSame([2, 2], [$sub->get_width(), $sub->get_height()]);
        $copy = $pixbuf->copy();
        self::assertNotNull($copy);
        self::assertNotSame($pixbuf, $copy);
    }

    public function testEncodingTakesOptionsAsAMap(): void
    {
        $pixbuf = self::red(4, 3);
        $png = $pixbuf->save_to_bufferv('png');
        self::assertStringStartsWith("\x89PNG", $png);
        $jpeg = $pixbuf->save_to_bufferv('jpeg', ['quality' => '80']);
        self::assertStringStartsWith("\xff\xd8\xff", $jpeg);
        $small = $pixbuf->save_to_bufferv('jpeg', ['quality' => '10']);
        self::assertLessThanOrEqual(strlen($jpeg), strlen($small), 'the option reached the encoder');
        try {
            $pixbuf->save_to_bufferv('no-such-format');
            self::fail('unknown format');
        } catch (GError $e) {
            self::assertStringContainsString('not supported', $e->getMessage());
        }
        try {
            $pixbuf->save_to_bufferv('png', ['compression' => 9]);
            self::fail('a non-string option value');
        } catch (\ValueError $e) {
            self::assertStringContainsString('must map option names to string values', $e->getMessage());
        }

        $file = sys_get_temp_dir() . '/php-gtk4-pixbuf-' . getmypid() . '.png';
        try {
            self::assertTrue($pixbuf->savev($file, 'png', ['compression' => '1']));
            [$format, $width, $height] = GdkPixbuf::get_file_info($file) ?? [null, 0, 0];
            self::assertInstanceOf(GdkPixbufFormat::class, $format);
            self::assertSame(['png', 4, 3], [$format->get_name(), $width, $height]);
            $loaded = GdkPixbuf::new_from_file_at_scale($file, 40, 40, true);
            self::assertSame([40, 30], [$loaded->get_width(), $loaded->get_height()], 'aspect kept');
            self::assertSame(['original-width' => '4', 'original-height' => '3'], $loaded->get_options());
        } finally {
            @unlink($file);
        }
        self::assertNull(GdkPixbuf::get_file_info(__FILE__), 'PHP source is not an image');
        self::assertGreaterThan(5, count(GdkPixbuf::get_formats()));
    }

    public function testPixelsCrossIntoATextureAndBack(): void
    {
        $pixbuf = self::red(4, 3);
        $format = $pixbuf->get_has_alpha() ? GdkMemoryFormat::R8g8b8a8 : GdkMemoryFormat::R8g8b8;
        $texture = new GdkMemoryTexture(
            $pixbuf->get_width(),
            $pixbuf->get_height(),
            $format,
            $pixbuf->read_pixel_bytes(),
            $pixbuf->get_rowstride(),
        );
        self::assertInstanceOf(GdkTexture::class, $texture);
        self::assertSame([4, 3], [$texture->get_width(), $texture->get_height()]);

        $downloader = new GdkTextureDownloader($texture);
        $downloader->set_format(GdkMemoryFormat::R8g8b8a8);
        self::assertSame(GdkMemoryFormat::R8g8b8a8, $downloader->get_format());
        self::assertSame($texture, $downloader->get_texture());
        [$bytes, $stride] = $downloader->download_bytes();
        self::assertSame(16, $stride);
        self::assertSame($pixbuf->read_pixel_bytes(), $bytes, 'the same pixels came back');
        $back = GdkPixbuf::new_from_bytes($bytes, GdkColorspace::Rgb, true, 8, 4, 3, $stride);
        self::assertSame($pixbuf->save_to_bufferv('png'), $back->save_to_bufferv('png'));
    }

    public function testAMemoryTextureRefusesTooFewBytes(): void
    {
        // GTK would only assert, and the texture would then read past the buffer.
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('Argument #4 ($bytes) must hold every row of the image');
        new GdkMemoryTexture(4, 4, GdkMemoryFormat::R8g8b8a8, 'short', 16);
    }

    public function testAMemoryTextureRefusesAStrideBelowTheRow(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('Argument #5 ($stride) must be at least the width times');
        new GdkMemoryTexture(4, 1, GdkMemoryFormat::R8g8b8a8, str_repeat("\0", 16), 8);
    }
}
