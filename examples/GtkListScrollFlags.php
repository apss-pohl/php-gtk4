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
use Gtk4\GtkSignalListItemFactory;
use Gtk4\GtkSingleSelection;
use Gtk4\GtkStringList;
use Gtk4\GtkStringObject;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkListScrollFlags - what scroll_to() does besides scrolling.
 *
 * A flags bitfield, so the constants are plain ints and combine with `|`: NONE
 * only brings the row on screen, FOCUS moves the keyboard focus to it and
 * SELECT selects it. The button walks the three, always scrolling to row 250 of
 * 400 - watch the selection in the report line.
 *
 *   bin/php-gtk4 examples/demo.php GtkListScrollFlags
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkListScrollFlags',
    'what scroll_to() does besides scrolling',
    function (GtkWindow $win): GtkWidget {
        $rows = [];
        for ($i = 0; $i < 400; $i++) {
            $rows[] = 'row ' . $i;
        }
        $selection = new GtkSingleSelection(new GtkStringList($rows));
        $selection->set_autoselect(false);

        $factory = new GtkSignalListItemFactory();
        $factory->connect('setup', function (GtkSignalListItemFactory $f, GtkListItem $item): void {
            $label = new GtkLabel();
            $label->set_halign(GtkAlign::Start);
            $item->set_child($label);
        });
        $factory->connect('bind', function (GtkSignalListItemFactory $f, GtkListItem $item): void {
            $label = $item->get_child();
            $object = $item->get_item();
            if ($label instanceof GtkLabel && $object instanceof GtkStringObject) {
                $label->set_text($object->get_string());
            }
        });

        $view = new GtkListView($selection, $factory);
        $scroller = new GtkScrolledWindow();
        $scroller->set_policy(GtkPolicyType::Never, GtkPolicyType::Automatic);
        $scroller->set_child($view);
        $scroller->set_vexpand(true);

        $report = Demo::label();
        /** @var list<array{string, int}> $modes */
        $modes = [
            ['NONE', GtkListScrollFlags::NONE],
            ['FOCUS', GtkListScrollFlags::FOCUS],
            ['SELECT', GtkListScrollFlags::SELECT],
            ['FOCUS | SELECT', GtkListScrollFlags::FOCUS | GtkListScrollFlags::SELECT],
        ];
        $step = 0;
        $button = GtkButton::new_with_label('scroll_to(250, …)');
        $button->connect('clicked', function () use ($view, $selection, $modes, &$step, $report): void {
            [$name, $flags] = $modes[$step++ % count($modes)];
            $view->scroll_to(0, GtkListScrollFlags::NONE, null);   // back to the top first
            $view->scroll_to(250, $flags, null);
            $selected = $selection->get_selected();
            $report->set_markup(sprintf(
                "<tt>scroll_to(250, %s)  = %d</tt>\n<small>selected: %s</small>",
                $name,
                $flags,
                $selected === 0xFFFFFFFF ? 'nothing' : (string) $selected,
            ));
        });

        $report->set_markup("<tt>scroll_to(position, flags, ?GtkScrollInfo)</tt>\n<small>click below</small>");
        $page = new GtkBox(GtkOrientation::Vertical, 8);
        $page->append($report);
        $page->append($scroller);
        $page->append($button);
        return $page;
    },
    440,
    420,
);
