<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkLabel;
use Gtk4\GtkListItem;
use Gtk4\GtkListScrollFlags;
use Gtk4\GtkListView;
use Gtk4\GtkOrientation;
use Gtk4\GtkPolicyType;
use Gtk4\GtkScrolledWindow;
use Gtk4\GtkScrollInfo;
use Gtk4\GtkSignalListItemFactory;
use Gtk4\GtkSingleSelection;
use Gtk4\GtkStringList;
use Gtk4\GtkStringObject;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkScrollInfo - which axes a scroll_to() is allowed to move.
 *
 * The optional last argument of GtkListView / GtkGridView / GtkColumnView /
 * GtkViewport scroll_to(): a small refcounted record with one flag per axis,
 * both on by default. GTK takes it over when you pass it (transfer full), and
 * the PHP handle keeps its own reference - so the same info can be reused for
 * the next call, as the button below does.
 *
 *   bin/php-gtk4 examples/demo.php GtkScrollInfo
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkScrollInfo',
    'which axes a scroll_to() is allowed to move',
    function (GtkWindow $win): GtkWidget {
        $rows = [];
        for ($i = 0; $i < 300; $i++) {
            $rows[] = str_repeat('wide ', 12) . 'row ' . $i;
        }
        $view = new GtkListView(
            new GtkSingleSelection(new GtkStringList($rows)),
            (function (): GtkSignalListItemFactory {
                $factory = new GtkSignalListItemFactory();
                $factory->connect('setup', function (GtkSignalListItemFactory $f, GtkListItem $i): void {
                    $label = new GtkLabel();
                    $label->set_halign(GtkAlign::Start);
                    $i->set_child($label);
                });
                $factory->connect('bind', function (GtkSignalListItemFactory $f, GtkListItem $i): void {
                    $label = $i->get_child();
                    $object = $i->get_item();
                    if ($label instanceof GtkLabel && $object instanceof GtkStringObject) {
                        $label->set_text($object->get_string());
                    }
                });
                return $factory;
            })(),
        );

        $scroller = new GtkScrolledWindow();
        $scroller->set_policy(GtkPolicyType::Automatic, GtkPolicyType::Automatic);
        $scroller->set_child($view);
        $scroller->set_vexpand(true);

        // One record, reused for every call: GTK consumes a reference each time.
        $info = new GtkScrollInfo();
        $report = Demo::label();
        $target = 0;
        $button = GtkButton::new_with_label('scroll with this GtkScrollInfo');
        $button->connect('clicked', function () use ($view, $info, $report, &$target): void {
            $info->set_enable_horizontal(!$info->get_enable_horizontal());
            $target = $target === 0 ? 220 : 0;
            $view->scroll_to($target, GtkListScrollFlags::NONE, $info);
            $report->set_markup(sprintf(
                "<tt>scroll_to(%d, NONE, info)\nenable-horizontal  %s\nenable-vertical    %s</tt>",
                $target,
                $info->get_enable_horizontal() ? 'true' : 'false',
                $info->get_enable_vertical() ? 'true' : 'false',
            ));
        });

        $report->set_markup(
            "<tt>new GtkScrollInfo()\nenable-horizontal  true\nenable-vertical    true</tt>",
        );
        $page = new GtkBox(GtkOrientation::Vertical, 8);
        $page->append($report);
        $page->append($scroller);
        $page->append($button);
        return $page;
    },
    460,
    420,
);
