<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkScrollablePolicy;
use Gtk4\GtkScrolledWindow;
use Gtk4\GtkViewport;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkScrollablePolicy - whether a scrollable asks for its minimum or its
 * natural size along an axis.
 *
 * With Minimum the wrapping labels shrink to their minimum width and wrap
 * inside the visible area; with Natural the viewport requests the unwrapped
 * width and a horizontal scrollbar appears. The page flips the horizontal
 * policy of a GtkViewport between the two cases.
 *
 *   bin/php-gtk4 examples/demo.php GtkScrollablePolicy
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkScrollablePolicy',
    'minimum or natural size request of a scrollable along an axis',
    function (GtkWindow $win): GtkWidget {
        $scrolled = new GtkScrolledWindow();
        $scrolled->set_vexpand(true);
        $scrolled->set_has_frame(true);

        $viewport = new GtkViewport();
        $column = new GtkBox(GtkOrientation::Vertical, 6);
        foreach ([3, 5, 8] as $n) {
            $line = new GtkLabel(str_repeat('a long sentence that wants to wrap ', $n));
            $line->wrap = true;
            $line->set_halign(GtkAlign::Start);
            $line->add_css_class('card');
            $column->append($line);
        }
        $viewport->set_child($column);
        $scrolled->set_child($viewport);

        $cases = GtkScrollablePolicy::cases();
        $step = 0;
        $status = Demo::label();
        $show = function () use ($viewport, $cases, $status, &$step): void {
            $policy = $cases[$step % count($cases)];
            $viewport->set_hscroll_policy($policy);
            $status->set_markup(sprintf(
                "hscroll_policy <b>%s</b> <small>(%d)</small>  ·  vscroll_policy %s\n<small>%s</small>",
                $viewport->get_hscroll_policy()->name,
                $policy->value,
                $viewport->get_vscroll_policy()->name,
                implode(' · ', array_map(static fn(GtkScrollablePolicy $p): string => $p->name, $cases)),
            ));
        };

        $show();
        GLib::timeout_add(1500, function () use ($show, &$step): bool {
            $step++;
            $show();
            return true;
        });

        Demo::status(GtkScrollablePolicy::from(0)->name . ' = GtkScrollablePolicy::from(0)');

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($status);
        $page->append($scrolled);
        return $page;
    },
    480,
    400,
);
