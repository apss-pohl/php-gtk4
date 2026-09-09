<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GAsyncResult;
use Gtk4\GCancellable;
use Gtk4\GError;
use Gtk4\GLib;
use Gtk4\GObject;
use Gtk4\GtkAlertDialog;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GCancellable - ends an async operation from the outside.
 *
 * The button opens an alert dialog and arms a three-second timeout that cancels it;
 * cancel() also fires the `cancelled` signal. Answer in time and the timeout finds
 * is_cancelled() false and the dialog gone.
 *
 *   bin/php-gtk4 examples/demo.php GCancellable
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GCancellable',
    'cancel an async operation from outside',
    function (GtkWindow $win): GtkWidget {
        $box = new GtkBox(GtkOrientation::Vertical, 12);
        $status = new GtkLabel('idle');
        $go = GtkButton::new_with_label('ask (3 s to answer)');
        $go->connect('clicked', function () use ($win, $status): void {
            $cancellable = new GCancellable();
            $cancellable->connect('cancelled', fn() => $status->set_text('cancelled signal'));
            $dialog = new GtkAlertDialog();
            $dialog->set_message('Quick!');
            $dialog->set_buttons(['Done']);
            $done = function (?GObject $source, GAsyncResult $result) use ($dialog, $status): void {
                try {
                    $dialog->choose_finish($result);
                    $status->set_text('answered in time');
                } catch (GError $e) {
                    $status->set_text('finish threw: ' . $e->getMessage());
                }
            };
            $dialog->choose($win, $cancellable, $done);
            GLib::timeout_add(3000, function () use ($cancellable): bool {
                if (!$cancellable->is_cancelled()) {
                    $cancellable->cancel();
                }

                return false;
            });
        });
        $box->append($go);
        $box->append($status);

        return $box;
    },
);
