<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GAsyncResult;
use Gtk4\GdkColorspace;
use Gtk4\GdkPixbuf;
use Gtk4\GError;
use Gtk4\GInputStream;
use Gtk4\GLib;
use Gtk4\GMemoryInputStream;

/**
 * The read side of GIO: a PHP string as a stream, and what GTK will decode from one.
 *
 * `GInputStream` is abstract - `GMemoryInputStream` is the one PHP can build - and binding it is
 * what makes `GdkPixbuf::new_from_stream()` and the other stream constructors reachable, so the
 * decode path is tested here too rather than in the pixbuf's own test.
 */
final class GInputStreamTest extends GtkTestCase
{
    private const TEXT = 'stream me, byte by byte';

    public function testReadBytesTakesTheStreamApartAndEndsWithAnEmptyString(): void
    {
        $stream = GMemoryInputStream::new_from_bytes(self::TEXT);

        self::assertSame('stream', $stream->read_bytes(6, null));
        self::assertSame(' me, ', $stream->read_bytes(5, null));
        $rest = '';
        while (($chunk = $stream->read_bytes(4, null)) !== '') {
            $rest .= $chunk;
        }
        self::assertSame('byte by byte', $rest);
        // At the end read_bytes() keeps answering with nothing rather than failing.
        self::assertSame('', $stream->read_bytes(4, null));
    }

    /** A GBytes is binary: an embedded NUL is data here, not the end of a string. */
    public function testBytesAreBinary(): void
    {
        $stream = GMemoryInputStream::new_from_bytes("a\0b");

        self::assertSame("a\0b", $stream->read_bytes(16, null));
    }

    public function testAddBytesAppendsToWhatIsLeft(): void
    {
        $stream = GMemoryInputStream::new_from_bytes('one ');
        $stream->add_bytes('two');

        self::assertSame('one two', $stream->read_bytes(32, null));
    }

    public function testSkipPassesOverBytesWithoutReadingThem(): void
    {
        $stream = GMemoryInputStream::new_from_bytes(self::TEXT);

        self::assertSame(7, $stream->skip(7, null));
        self::assertSame('me,', $stream->read_bytes(3, null));
    }

    public function testClosingIsVisibleAndIdempotent(): void
    {
        $stream = GMemoryInputStream::new_from_bytes(self::TEXT);
        self::assertFalse($stream->is_closed());
        self::assertFalse($stream->has_pending(), 'nothing is in flight on a fresh stream');

        self::assertTrue($stream->close(null));
        self::assertTrue($stream->is_closed());
        self::assertTrue($stream->close(null), 'closing twice is not an error');
    }

    /** Reading a closed stream is a GError, which the binding raises as an exception. */
    public function testReadingAClosedStreamThrows(): void
    {
        $stream = GMemoryInputStream::new_from_bytes(self::TEXT);
        $stream->close(null);

        $this->expectException(GError::class);
        $stream->read_bytes(4, null);
    }

    /** The async pair, driven by the main context the way an application's loop would. */
    public function testReadBytesAsyncAnswersThroughTheCallback(): void
    {
        $stream = GMemoryInputStream::new_from_bytes(self::TEXT);
        $got = null;

        $stream->read_bytes_async(6, 0, null, static function (
            GInputStream $source,
            GAsyncResult $result,
        ) use (&$got): void {
            $got = $source->read_bytes_finish($result);
        });

        $deadline = microtime(true) + 5.0;
        while ($got === null && microtime(true) < $deadline) {
            GLib::main_context_iteration(false);
        }
        self::assertSame('stream', $got);
    }

    /** GInputStream is abstract in GIO: the native class refuses `new`, a PHP subclass does not. */
    public function testTheBaseClassCannotBeConstructed(): void
    {
        $this->expectException(\Error::class);
        new GInputStream();
    }

    /**
     * What binding the stream was for: GdkPixbuf decoding from one. The bytes are a PNG this
     * test encodes itself, so nothing depends on a file being there.
     */
    public function testAPixbufDecodesFromAStream(): void
    {
        $source = new GdkPixbuf(GdkColorspace::Rgb, true, 8, 12, 8);
        $source->fill(0x3584e4ff);
        $png = $source->save_to_bufferv('png');

        $decoded = GdkPixbuf::new_from_stream(GMemoryInputStream::new_from_bytes($png));
        self::assertSame(12, $decoded->get_width());
        self::assertSame(8, $decoded->get_height());

        $scaled = GdkPixbuf::new_from_stream_at_scale(
            GMemoryInputStream::new_from_bytes($png),
            24,
            16,
            true,
        );
        self::assertSame(24, $scaled->get_width());
        self::assertSame(16, $scaled->get_height());
    }

    /** Bytes that are not an image are a GError from the decoder, not a crash. */
    public function testDecodingRubbishFromAStreamThrows(): void
    {
        $this->expectException(GError::class);
        GdkPixbuf::new_from_stream(GMemoryInputStream::new_from_bytes('not a picture'));
    }
}
