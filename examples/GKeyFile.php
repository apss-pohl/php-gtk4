<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GKeyFile;
use Gtk4\GtkButton;
use Gtk4\GtkPageSetup;
use Gtk4\GtkUnit;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GKeyFile - GLib's .ini file, and how GTK stores a page setup.
 *
 * Groups, keys and typed values, loaded from and saved to a file. GTK's print settings, page
 * setup and paper size all know how to write themselves into one, which is how an application
 * remembers what the user chose last time. The page builds a setup, writes it to a key file and
 * reads it back into a fresh object.
 *
 *   bin/php-gtk4 examples/demo.php GKeyFile
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GKeyFile',
    "GLib's .ini file, and how a page setup is stored in one",
    function (GtkWindow $win): GtkWidget {
        $label = Demo::label();
        $margin = 10.0;

        $render = function () use ($label, &$margin): void {
            $setup = new GtkPageSetup();
            $setup->set_top_margin($margin, GtkUnit::Mm);
            $setup->set_bottom_margin($margin, GtkUnit::Mm);

            $file = new GKeyFile();
            $file->set_string('App', 'name', 'php-gtk4');
            $file->set_integer('App', 'runs', 3);
            $file->set_boolean('App', 'first-time', false);
            $setup->to_key_file($file, 'Page Setup');

            // ...and back out into an object that knew nothing about it.
            $back = GtkPageSetup::new_from_key_file($file, 'Page Setup');

            $label->set_markup(sprintf(
                "<span size=\"x-large\"><b>%s</b></span>\n"
                . "<small>runs: %d · first time: %s</small>\n\n"
                . "<small>the page setup round-tripped: top margin <b>%.1f mm</b>, paper <b>%s</b></small>\n\n"
                . '<small>click to change the margin and write it again</small>',
                htmlspecialchars($file->get_string('App', 'name')),
                $file->get_integer('App', 'runs'),
                $file->get_boolean('App', 'first-time') ? 'yes' : 'no',
                $back->get_top_margin(GtkUnit::Mm),
                htmlspecialchars($back->get_paper_size()->get_display_name()),
            ));
            Demo::status(sprintf(
                'the file has a start group of "%s"; has_key("App", "runs") is %s',
                $file->get_start_group() ?? '-',
                $file->has_key('App', 'runs') ? 'true' : 'false',
            ));
        };

        $button = new GtkButton();
        $button->set_child($label);
        $button->connect('clicked', function () use (&$margin, $render): void {
            $margin = $margin >= 30.0 ? 5.0 : $margin + 5.0;
            $render();
        });
        $render();
        return $button;
    },
    480,
    260,
);
