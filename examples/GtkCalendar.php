<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkCalendar;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkCalendar - one month of a Gregorian calendar, with a selected day.
 *
 * Months are 0-based (January = 0), days 1-based. The page marks a few days,
 * reports the selection whenever `day-selected` fires, cycles the three show_*
 * toggles so heading, day names and week numbers appear and disappear, and the
 * buttons move the month and toggle a mark on the selected day.
 *
 *   bin/php-gtk4 examples/demo.php GtkCalendar
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkCalendar',
    'one month of a Gregorian calendar, with a selected day',
    function (GtkWindow $win): GtkWidget {
        $calendar = new GtkCalendar();
        $calendar->set_vexpand(true);
        $calendar->set_show_week_numbers(true);
        foreach ([1, 15, 28] as $day) {
            $calendar->mark_day($day);
        }

        $status = Demo::label();
        $describe = function () use ($calendar, $status): void {
            $marked = array_filter(range(1, 28), fn(int $d): bool => $calendar->get_day_is_marked($d));
            $status->set_markup(sprintf(
                "<tt>date        %04d-%02d-%02d  (get_month() = %d, 0-based)\nmarked      %s\n"
                . 'heading %s · day names %s · week numbers %s</tt>',
                $calendar->get_year(),
                $calendar->get_month() + 1,
                $calendar->get_day(),
                $calendar->get_month(),
                implode(', ', $marked) ?: 'none',
                $calendar->get_show_heading() ? 'on ' : 'off',
                $calendar->get_show_day_names() ? 'on ' : 'off',
                $calendar->get_show_week_numbers() ? 'on ' : 'off',
            ));
        };

        // Clicking a day fires day-selected; the handler gets the calendar itself.
        $calendar->connect('day-selected', function (GtkCalendar $self) use ($describe): void {
            Demo::status(sprintf(
                'day-selected: %04d-%02d-%02d',
                $self->get_year(),
                $self->get_month() + 1,
                $self->get_day(),
            ));
            $describe();
        });

        $previous = GtkButton::new_with_label('month -1');
        $previous->connect('clicked', function () use ($calendar, $describe): void {
            $calendar->set_month(($calendar->get_month() + 11) % 12);
            $describe();
        });
        $next = GtkButton::new_with_label('month +1');
        $next->connect('clicked', function () use ($calendar, $describe): void {
            $calendar->set_month(($calendar->get_month() + 1) % 12);
            $describe();
        });
        $mark = GtkButton::new_with_label('toggle mark');
        $mark->connect('clicked', function () use ($calendar, $describe): void {
            $day = $calendar->get_day();
            $calendar->get_day_is_marked($day) ? $calendar->unmark_day($day) : $calendar->mark_day($day);
            $describe();
        });
        $reset = GtkButton::new_with_label('clear_marks()');
        $reset->connect('clicked', function () use ($calendar, $describe): void {
            $calendar->clear_marks();
            $describe();
        });

        // Cycle the three show_* toggles so the chrome comes and goes.
        $step = 0;
        GLib::timeout_add(1500, function () use ($calendar, $describe, &$step): bool {
            match ($step++ % 4) {
                0 => $calendar->set_show_heading(false),
                1 => $calendar->set_show_day_names(false),
                2 => $calendar->set_show_week_numbers(false),
                default => (function () use ($calendar): void {
                    $calendar->set_show_heading(true);
                    $calendar->set_show_day_names(true);
                    $calendar->set_show_week_numbers(true);
                })(),
            };
            $describe();
            return true;
        });

        $describe();

        $buttons = new GtkBox(GtkOrientation::Horizontal, 6);
        $buttons->set_halign(GtkAlign::Center);
        foreach ([$previous, $next, $mark, $reset] as $button) {
            $buttons->append($button);
        }

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $status->set_halign(GtkAlign::Start);
        $page->append($status);
        $page->append($calendar);
        $page->append($buttons);
        return $page;
    },
    520,
    460,
);
