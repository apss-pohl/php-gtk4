<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GdkTexture;
use Gtk4\GError;
use Gtk4\GObject;

/** GdkTexture, and through it GError -> exception and GBytes <-> string. */
final class TextureTest extends GtkTestCase
{
    private static function png(): string
    {
        return TestPng::red(2, 1);
    }

    public function testFromBytesAndBack(): void
    {
        $t = GdkTexture::new_from_bytes(self::png());
        self::assertInstanceOf(GObject::class, $t);
        self::assertSame(2, $t->get_width());
        self::assertSame(1, $t->get_height());
        self::assertSame(2, $t->width, '@property');

        $png = $t->save_to_png_bytes();
        self::assertStringStartsWith("\x89PNG", $png, 'GBytes came back as a PHP string');
        $again = GdkTexture::new_from_bytes($png);
        self::assertSame([2, 1], [$again->get_width(), $again->get_height()]);
    }

    public function testFromFilenameAndSave(): void
    {
        $dir = sys_get_temp_dir() . '/php-gtk4-' . getmypid();
        @mkdir($dir);
        $in = "$dir/in.png";
        $out = "$dir/out.png";
        file_put_contents($in, self::png());
        try {
            $t = GdkTexture::new_from_filename($in);
            self::assertSame(2, $t->get_width());
            $t->save_to_png($out);
            self::assertFileExists($out);
            self::assertSame(1, GdkTexture::new_from_filename($out)->get_height());
        } finally {
            @unlink($in);
            @unlink($out);
            @rmdir($dir);
        }
    }

    public function testMissingFileIsAGError(): void
    {
        try {
            GdkTexture::new_from_filename('/nonexistent/php-gtk4.png');
            self::fail('expected GError');
        } catch (GError $e) {
            self::assertInstanceOf(\RuntimeException::class, $e);
            self::assertNotSame('', $e->getDomain());
            self::assertStringContainsString('php-gtk4.png', $e->getMessage());
        }
    }

    public function testGarbageBytesIsAGError(): void
    {
        $this->expectException(GError::class);
        GdkTexture::new_from_bytes('definitely not an image');
    }

    public function testUnwritablePathIsAGError(): void
    {
        $t = GdkTexture::new_from_bytes(self::png());
        $this->expectException(GError::class);
        $t->save_to_png('/nonexistent-dir/x.png');
    }

    public function testGErrorIsARegularException(): void
    {
        $e = new GError('manual');
        self::assertSame('', $e->getDomain());
        self::assertSame('manual', $e->getMessage());
    }
}
