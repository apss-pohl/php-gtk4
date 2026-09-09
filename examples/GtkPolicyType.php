<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkPolicyType;
use Gtk4\GtkScrolledWindow;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkPolicyType - when a scrolled window shows its scrollbars.
 *
 * Always shows the bar even when the child fits, Automatic only when it does
 * not, Never hides it (the child is still scrollable by wheel), External leaves
 * scrolling to someone else and lets the child ask for its full size. The page
 * cycles the vertical policy on a scrolled window every second.
 *
 *   bin/php-gtk4 examples/demo.php GtkPolicyType
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkPolicyType',
    'when a scrolled window shows its scrollbars',
    function (GtkWindow $win): GtkWidget {
        $scrolled = new GtkScrolledWindow();
        $scrolled->set_vexpand(true);
        $scrolled->set_has_frame(true);

        $column = new GtkBox(GtkOrientation::Vertical, 4);
        for ($i = 1; $i <= 25; $i++) {
            $line = new GtkLabel(sprintf('line %02d', $i));
            $line->set_halign(GtkAlign::Start);
            $line->add_css_class('card');
            $column->append($line);
        }
        $scrolled->set_child($column);

        $cases = GtkPolicyType::cases();
        $step = 0;
        $status = Demo::label();
        $show = function () use ($scrolled, $cases, $status, &$step): void {
            $policy = $cases[$step % count($cases)];
            $scrolled->set_policy(GtkPolicyType::Never, $policy);
            [$h, $v] = $scrolled->get_policy();
            $status->set_markup(sprintf(
                "vertical <b>%s</b> <small>(%d)</small>  ·  horizontal %s\n<small>%s</small>",
                $v->name,
                $v->value,
                $h->name,
                implode(' · ', array_map(static fn(GtkPolicyType $p): string => $p->name, $cases)),
            ));
        };

        $show();
        GLib::timeout_add(1200, function () use ($show, &$step): bool {
            $step++;
            $show();
            return true;
        });

        Demo::status(GtkPolicyType::from(1)->name . ' = GtkPolicyType::from(1)');

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($status);
        $page->append($scrolled);
        return $page;
    },
    480,
    400,
);
