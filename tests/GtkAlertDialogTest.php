<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GAsyncResult;
use Gtk4\GCancellable;
use Gtk4\GError;
use Gtk4\GLib;
use Gtk4\GObject;
use Gtk4\GtkAlertDialog;

/**
 * src/Gtk/GtkAlertDialog.cpp (generated): the async API shape - choose() with a generated
 * GAsyncReadyCallback trampoline (scope async), choose_finish() taking the GAsyncResult, and a
 * GCancellable ending the operation so the callback runs without a user.
 */
final class GtkAlertDialogTest extends GtkTestCase
{
    public function testProperties(): void
    {
        $d = new GtkAlertDialog();
        $d->set_message('Delete?');
        $d->set_detail('This cannot be undone.');
        $d->set_buttons(['Cancel', 'Delete']);
        $d->set_default_button(1);
        $d->set_cancel_button(0);
        self::assertSame('Delete?', $d->get_message());
        self::assertSame(['Cancel', 'Delete'], $d->get_buttons());
        self::assertSame(1, $d->get_default_button());
    }

    public function testCancelledChooseRunsTheCallbackWithoutAUser(): void
    {
        $d = new GtkAlertDialog();
        $d->set_message('async');
        $d->set_buttons(['A', 'B']);
        $cancellable = new GCancellable();
        $seen = null;
        $outcome = null;
        $callback = function (?GObject $source, GAsyncResult $result) use ($d, &$seen, &$outcome): void {
            $seen = $source;
            try {
                $outcome = $d->choose_finish($result);  // GTK 4.14: -1 (the cancel button)
            } catch (GError $e) {
                $outcome = $e;  // newer GTK reports the cancellation as a GError
            }
        };
        $d->choose(null, $cancellable, $callback);
        $cancellable->cancel();
        $deadline = microtime(true) + 10;  // sanitizer/coverage builds are slow to dispatch
        while ($outcome === null && microtime(true) < $deadline) {
            GLib::main_context_iteration(false);
            usleep(2000);
        }
        self::assertNotNull($outcome, 'cancelling runs the callback through the generated trampoline');
        self::assertTrue($outcome === -1 || $outcome instanceof GError, 'cancelled: -1 or a GError');
        // GTK's task carries no source object here (NULL -> null); when it does, it is the handle
        self::assertTrue($seen === null || $seen === $d);
    }

    public function testNullCallbackIsAccepted(): void
    {
        $d = new GtkAlertDialog();
        $d->set_message('no callback');
        $c = new GCancellable();
        $d->choose(null, $c, null);
        $c->cancel();
        for ($i = 0; $i < 5; $i++) {
            GLib::main_context_iteration(false);
        }
        self::assertTrue($c->is_cancelled(), 'the operation was cancelled without a callback');
    }
}
