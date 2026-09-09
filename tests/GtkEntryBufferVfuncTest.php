<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GtkEntry;
use PhpGtk4\Tests\Subclass\RecordingEntryBuffer;

/**
 * The GtkEntryBuffer class-struct slots. Unlike most vfuncs these are not key bindings: the
 * public methods call straight into them, and a PHP subclass is what an application writes to
 * validate or transform text before the entry ever sees it.
 */
final class GtkEntryBufferVfuncTest extends GtkTestCase
{
    public function testInsertAndDeleteRunThroughThePhpSlots(): void
    {
        $buffer = new RecordingEntryBuffer(null, 0);
        $buffer->calls = [];

        self::assertSame(5, $buffer->insert_text(0, 'hello', 5));
        self::assertSame(2, $buffer->delete_text(0, 2));

        self::assertSame('llo', $buffer->get_text(), 'chaining to parent:: still edits the buffer');
        self::assertContains('insert_text(0,hello)', $buffer->calls);
        self::assertContains('inserted_text(0,hello)', $buffer->calls);
        self::assertContains('delete_text(0,2)', $buffer->calls);
        self::assertContains('deleted_text(0,2)', $buffer->calls);
    }

    public function testGetLengthRunsThroughThePhpSlot(): void
    {
        $buffer = new RecordingEntryBuffer('abc', 3);
        $buffer->calls = [];

        self::assertSame(3, $buffer->get_length());
        self::assertContains('get_length', $buffer->calls);
    }

    public function testAnEntryDrivesThePhpBuffer(): void
    {
        $buffer = new RecordingEntryBuffer(null, 0);
        $entry = new GtkEntry();
        $entry->set_buffer($buffer);
        $buffer->calls = [];

        $entry->set_text('typed');

        self::assertSame('typed', $buffer->get_text());
        self::assertContains('insert_text(0,typed)', $buffer->calls, 'the widget went through PHP');
    }
}
