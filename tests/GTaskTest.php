<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GError;
use Gtk4\GTask;

/** GTask: the result handle behind every *_finish() - propagation of values and errors. */
final class GTaskTest extends GtkTestCase
{
    public function testPropagateIntReturnsTheResult(): void
    {
        $task = new GTask();
        $task->return_int(42);
        self::assertSame(42, $task->propagate_int());
    }

    public function testPropagateIntThrowsTheTaskError(): void
    {
        // A `throws` function with a scalar return has no failure value of its own (a failed
        // g_task_propagate_int() is 0, a dismissed choose_finish() is -1): the GError decides.
        $task = new GTask();
        $task->return_error(new GError('nope', 7));
        try {
            $task->propagate_int();
            self::fail('the task error must surface');
        } catch (GError $e) {
            self::assertSame('nope', $e->getMessage());
            self::assertSame(7, $e->getCode());
            self::assertSame('php-gtk4-error-quark', $e->getDomain(), 'a PHP-made GError has no GLib domain');
        }
    }

    public function testPropagateBooleanThrowsTheTaskError(): void
    {
        $task = new GTask();
        $task->return_error(new GError('still nope'));
        $this->expectException(GError::class);
        $task->propagate_boolean();
    }
}
