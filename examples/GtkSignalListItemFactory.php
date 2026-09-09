<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkLabel;
use Gtk4\GtkListItem;
use Gtk4\GtkListView;
use Gtk4\GtkOrientation;
use Gtk4\GtkPolicyType;
use Gtk4\GtkScrolledWindow;
use Gtk4\GtkSignalListItemFactory;
use Gtk4\GtkSingleSelection;
use Gtk4\GtkStringList;
use Gtk4\GtkStringObject;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkSignalListItemFactory - rows built by four PHP handlers.
 *
 * `setup` creates the widget of a row, `bind` fills it in for the item it is
 * about to show, `unbind` clears what bind put there, `teardown` drops the
 * widget. GTK recycles row widgets while scrolling, so setup runs a handful of
 * times and bind runs on every scroll - the counters below show exactly that on
 * a list of 300 items.
 *
 *   bin/php-gtk4 examples/demo.php GtkSignalListItemFactory
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkSignalListItemFactory',
    'rows built by four PHP handlers: setup, bind, unbind, teardown',
    function (GtkWindow $win): GtkWidget {
        $words = [];
        for ($i = 1; $i <= 300; $i++) {
            $words[] = 'row ' . $i;
        }

        $counts = ['setup' => 0, 'bind' => 0, 'unbind' => 0, 'teardown' => 0];
        $tally = Demo::label();
        $report = function () use (&$counts, $tally): void {
            $tally->set_markup(sprintf(
                "<b>scroll the list</b>\n<tt>setup %4d    bind %5d\nunbind %3d    teardown %2d</tt>\n"
                . '<small>setup counts widgets, bind counts rows shown</small>',
                $counts['setup'],
                $counts['bind'],
                $counts['unbind'],
                $counts['teardown'],
            ));
        };

        $factory = new GtkSignalListItemFactory();
        $factory->connect('setup', function (
            GtkSignalListItemFactory $f,
            GtkListItem $item,
        ) use (
            &$counts,
            $report
        ): void {
            $counts['setup']++;
            $label = new GtkLabel();
            $label->set_halign(GtkAlign::Start);
            $item->set_child($label);
            $report();
        });
        $factory->connect('bind', function (
            GtkSignalListItemFactory $f,
            GtkListItem $item,
        ) use (
            &$counts,
            $report
        ): void {
            $counts['bind']++;
            $label = $item->get_child();
            $object = $item->get_item();
            if ($label instanceof GtkLabel && $object instanceof GtkStringObject) {
                $label->set_text($object->get_string());
            }
            $report();
        });
        $factory->connect('unbind', function (
            GtkSignalListItemFactory $f,
            GtkListItem $item,
        ) use (
            &$counts,
            $report
        ): void {
            $counts['unbind']++;
            $label = $item->get_child();
            if ($label instanceof GtkLabel) {
                $label->set_text('');   // whatever bind wrote is gone before the next item
            }
            $report();
        });
        $factory->connect('teardown', function (
            GtkSignalListItemFactory $f,
            GtkListItem $item,
        ) use (
            &$counts,
            $report
        ): void {
            $counts['teardown']++;
            $item->set_child(null);
            $report();
        });

        $view = new GtkListView(new GtkSingleSelection(new GtkStringList($words)), $factory);
        $scroller = new GtkScrolledWindow();
        $scroller->set_policy(GtkPolicyType::Never, GtkPolicyType::Automatic);
        $scroller->set_child($view);
        $scroller->set_vexpand(true);

        $report();
        $page = new GtkBox(GtkOrientation::Vertical, 8);
        $page->append($tally);
        $page->append($scroller);
        return $page;
    },
    440,
    420,
);
