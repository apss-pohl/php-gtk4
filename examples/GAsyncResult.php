<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GAsyncResult;
use Gtk4\GCancellable;
use Gtk4\GError;
use Gtk4\GObject;
use Gtk4\GtkAlertDialog;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GAsyncResult - what an async callback receives: the handle a *_finish() call
 * turns into the outcome. Its source object is the object the operation ran on - the
 * very same PHP handle, as get_source_object() shows.
 *
 *   bin/php-gtk4 examples/demo.php GAsyncResult
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GAsyncResult',
    'the handle an async callback receives',
    function (GtkWindow $win): GtkWidget {
        $box = new GtkBox(GtkOrientation::Vertical, 12);
        $info = new GtkLabel('press the button');
        $info->set_use_markup(true);
        $button = GtkButton::new_with_label('run and cancel an operation');
        $button->connect('clicked', function () use ($win, $info): void {
            $dialog = new GtkAlertDialog();
            $dialog->set_message('will be cancelled right away');
            $cancellable = new GCancellable();
            $done = function (?GObject $source, GAsyncResult $result) use ($dialog, $info): void {
                $same = $result->get_source_object() === $dialog ? 'the same handle' : 'another handle';
                try {
                    $dialog->choose_finish($result);
                    $outcome = 'finished';
                } catch (GError $e) {
                    $outcome = 'threw <i>' . htmlspecialchars($e->getMessage()) . '</i>';
                }
                $info->set_markup('<b>' . $result::class . "</b>: source is $same, finish() $outcome");
            };
            $dialog->choose($win, $cancellable, $done);
            $cancellable->cancel();
        });
        $box->append($button);
        $box->append($info);

        return $box;
    },
);
