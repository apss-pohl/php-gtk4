<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GAsyncResult;
use Gtk4\GCancellable;
use Gtk4\GError;
use Gtk4\GObject;
use Gtk4\GTask;
use Gtk4\GtkAlertDialog;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GTask - the GAsyncResult implementation GTK hands to async callbacks. The page
 * runs a dialog operation, cancels it, and shows what the callback received: a GTask
 * (had_error(), get_cancellable(), the propagated GError of *_finish()).
 *
 *   bin/php-gtk4 examples/demo.php GTask
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GTask',
    'the GAsyncResult behind every async callback',
    function (GtkWindow $win): GtkWidget {
        $box = new GtkBox(GtkOrientation::Vertical, 12);
        $info = new GtkLabel('press the button');
        $info->set_use_markup(true);
        $button = GtkButton::new_with_label('run a cancelled operation');
        $button->connect('clicked', function () use ($win, $info): void {
            $dialog = new GtkAlertDialog();
            $dialog->set_message('cancelled right away');
            $cancellable = new GCancellable();
            $done = function (?GObject $source, GAsyncResult $result) use ($dialog, $cancellable, $info): void {
                $task = $result instanceof GTask ? $result : null;
                $facts = [];
                $facts[] = 'class ' . $result::class;
                if ($task !== null) {
                    $facts[] = 'had_error() ' . ($task->had_error() ? 'true' : 'false');
                    $facts[] = 'cancellable is ' . ($task->get_cancellable() === $cancellable ? 'ours' : 'another');
                }
                try {
                    $dialog->choose_finish($result);
                    $facts[] = 'finish() returned';
                } catch (GError $e) {
                    $facts[] = 'finish() threw <i>' . htmlspecialchars($e->getMessage()) . '</i>';
                }
                $info->set_markup(implode("\n", $facts));
            };
            $dialog->choose($win, $cancellable, $done);
            $cancellable->cancel();
        });
        $box->append($button);
        $box->append($info);

        return $box;
    },
);
