<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GtkTextTag;
use Gtk4\GtkTextTagTable;

/**
 * GtkTextTagTable behaviour: add/lookup/remove, the foreach callback (a `call`-scope
 * trampoline), and tag priorities which only exist inside a table.
 */
final class GtkTextTagTableTest extends GtkTestCase
{
    public function testAddLookupRemove(): void
    {
        $table = new GtkTextTagTable();
        self::assertSame(0, $table->get_size());

        $named = new GtkTextTag('bold');
        $anon = new GtkTextTag(null);
        self::assertNull($anon->name);
        self::assertTrue($table->add($named));
        self::assertTrue($table->add($anon));
        self::assertFalse($table->add($named), 'a tag can be in one table once');
        self::assertSame(2, $table->get_size());
        self::assertSame($named, $table->lookup('bold'));
        self::assertNull($table->lookup('absent'));

        $table->remove($named);
        self::assertSame(1, $table->get_size());
        self::assertNull($table->lookup('bold'));
    }

    public function testForeachVisitsEveryTag(): void
    {
        $table = new GtkTextTagTable();
        $table->add(new GtkTextTag('a'));
        $table->add(new GtkTextTag('b'));
        $seen = [];
        $table->foreach(function (GtkTextTag $tag) use (&$seen): void {
            $seen[] = $tag->name;
        });
        sort($seen);
        self::assertSame(['a', 'b'], $seen);
    }

    public function testForeachReportsAThrowingCallback(): void
    {
        $table = new GtkTextTagTable();
        $table->add(new GtkTextTag('x'));
        $seen = $this->captureHandlerException(function () use ($table): void {
            $table->foreach(function (): void {
                throw new \RuntimeException('from foreach');
            });
        });
        self::assertNotNull($seen, 'the exception reached the handler instead of unwinding GTK');
        self::assertSame('from foreach', $seen[0]);
        self::assertStringContainsString('foreach', $seen[1], 'the origin names the installing method');
    }

    public function testPriorityExistsOnlyInsideATable(): void
    {
        $table = new GtkTextTagTable();
        $first = new GtkTextTag('first');
        $second = new GtkTextTag('second');
        $table->add($first);
        $table->add($second);
        self::assertSame(0, $first->get_priority());
        self::assertSame(1, $second->get_priority());
        $second->set_priority(0);
        self::assertSame([1, 0], [$first->get_priority(), $second->get_priority()], 'priorities swapped');
    }
}
