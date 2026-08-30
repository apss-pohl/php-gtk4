<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GAsyncResult;
use Gtk4\GError;
use Gtk4\GListStore;
use Gtk4\GObject;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkFileDialog;
use Gtk4\GtkFileFilter;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkFileDialog - GTK 4.10's file chooser: no run(), no widget to pack. open() returns
 * at once and the answer arrives in the callback, where open_finish() gives the file - as a
 * plain path string, because a GFile carries nothing else a PHP program can use (docs/PLAN.md
 * §2.7). Dismissing the dialog is a GError, not an empty answer.
 *
 *   bin/php-gtk4 examples/demo.php GtkFileDialog
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkFileDialog',
    'the async file chooser: open(), save(), select_folder()',
    function (GtkWindow $win): GtkWidget {
        $chosen = Demo::label('<i>nothing chosen yet</i>');

        $dialog = new GtkFileDialog();
        $dialog->set_title('Pick a file');
        $dialog->set_initial_folder(sys_get_temp_dir());

        // Filters are a GListModel of GtkFileFilter, and one of them can be preselected.
        $images = new GtkFileFilter();
        $images->set_name('Images');
        $images->add_suffix('png');
        $images->add_suffix('jpg');
        $any = new GtkFileFilter();
        $any->set_name('All files');
        $any->add_pattern('*');
        $filters = new GListStore('GtkFileFilter');
        $filters->append($images);
        $filters->append($any);
        $dialog->set_filters($filters);
        $dialog->set_default_filter($any);

        $buttons = new GtkBox(GtkOrientation::Horizontal, 6);
        foreach (['open', 'save', 'select_folder'] as $verb) {
            $button = GtkButton::new_with_label(str_replace('_', ' ', $verb));
            $button->add_css_class('flat');
            $button->connect('clicked', function () use ($dialog, $win, $verb, $chosen): void {
                $finish = $verb . '_finish';
                $dialog->{$verb}($win, null, function (
                    ?GObject $source,
                    GAsyncResult $result,
                ) use (
                    $dialog,
                    $finish,
                    $chosen,
                ): void {
                    try {
                        $path = (string) $dialog->{$finish}($result);
                        $chosen->set_markup('<tt>' . htmlspecialchars($path) . '</tt>');
                        Demo::status('chosen');
                    } catch (GError $e) {
                        // Dismissed, cancelled, or the portal said no.
                        $chosen->set_markup('<i>' . htmlspecialchars($e->getMessage()) . '</i>');
                        Demo::status('dismissed');
                    }
                });
            });
            $buttons->append($button);
        }

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($chosen);
        $page->append($buttons);
        $page->append(Demo::label(
            "<small>open_multiple() answers with a list; set_initial_file() is stored as\n"
            . 'initial-folder + initial-name, which is why it does not read back.</small>',
        ));
        return $page;
    },
);
