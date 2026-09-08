<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GdkColorspace;
use Gtk4\GdkPixbuf;
use Gtk4\GMemoryInputStream;
use Gtk4\GMemoryOutputStream;

/**
 * The write side of GIO, the counterpart of {@see GInputStreamTest}: a stream that collects
 * what is written into it, which is what makes GdkPixbuf's stream encoders reachable.
 */
final class OutputStreamTest extends GtkTestCase
{
    public function testWhatIsWrittenCanBeTakenBackOut(): void
    {
        $stream = GMemoryOutputStream::new_resizable();
        $stream->write_bytes('hello ', null);
        $stream->write_bytes('world', null);

        self::assertSame(11, $stream->get_data_size());
        $stream->close(null);
        self::assertSame('hello world', $stream->steal_as_bytes());
    }

    /** The buffer belongs to the stream until it is closed. */
    public function testTakingTheBytesOfAnOpenStreamIsRefused(): void
    {
        $stream = GMemoryOutputStream::new_resizable();
        $stream->write_bytes('x', null);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('close() it before taking its bytes');
        $stream->steal_as_bytes();
    }

    public function testClosingIsVisibleAndIdempotent(): void
    {
        $stream = GMemoryOutputStream::new_resizable();
        self::assertFalse($stream->is_closed());

        self::assertTrue($stream->close(null));
        self::assertTrue($stream->is_closed());
        self::assertTrue($stream->close(null), 'closing twice is not an error');
    }

    /** What binding it was for: a pixbuf encoded straight into a stream. */
    public function testAPixbufSavesItselfIntoAStream(): void
    {
        $pixbuf = new GdkPixbuf(GdkColorspace::Rgb, true, 8, 8, 8);
        $pixbuf->fill(0x3584e4ff);

        $stream = GMemoryOutputStream::new_resizable();
        $pixbuf->save_to_streamv($stream, 'png', null, null, null);
        $stream->close(null);

        $png = $stream->steal_as_bytes();
        self::assertStringStartsWith("\x89PNG", $png, 'a PNG signature came out');

        // ...and it decodes again through the read side.
        $back = GdkPixbuf::new_from_stream(GMemoryInputStream::new_from_bytes($png));
        self::assertSame(8, $back->get_width());
    }
}
