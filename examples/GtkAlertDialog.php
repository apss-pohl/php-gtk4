<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GAsyncResult;
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
 * Gtk4\GtkAlertDialog - GTK 4's async question: choose() returns at once, the answer
 * arrives in the callback, choose_finish() turns the GAsyncResult into the button index
 * (or throws a GError when the dialog was dismissed).
 *
 *   bin/php-gtk4 examples/demo.php GtkAlertDialog
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkAlertDialog',
    'an async question with buttons',
    function (GtkWindow $win): GtkWidget {
        $box = new GtkBox(GtkOrientation::Vertical, 12);
        $answer = new GtkLabel('no answer yet');
        $ask = GtkButton::new_with_label('ask');
        $ask->connect('clicked', function () use ($win, $answer): void {
            $dialog = new GtkAlertDialog();
            $dialog->set_message('Keep going?');
            $dialog->set_detail('choose() returned already; this runs from the callback.');
            $dialog->set_buttons(['No', 'Yes']);
            $dialog->set_default_button(1);
            $dialog->set_cancel_button(0);
            $dialog->choose($win, null, function (?GObject $source, GAsyncResult $result) use ($dialog, $answer): void {
                try {
                    $index = $dialog->choose_finish($result);
                    $answer->set_text('button ' . $index . ' (' . $dialog->get_buttons()[$index] . ')');
                } catch (GError $e) {
                    $answer->set_text('dismissed: ' . $e->getMessage());
                }
            });
        });
        $box->append($ask);
        $box->append($answer);

        return $box;
    },
);
