<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkCornerType;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkPolicyType;
use Gtk4\GtkScrolledWindow;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkScrolledWindow - the container that makes its child scrollable.
 *
 * Forty labels in a box that would never fit; the scrolled window shows a slice
 * of them and adds scrollbars. The buttons walk through set_policy() (when the
 * bars appear) and set_placement() (which corner they sit in), and the status
 * line follows get_vadjustment()->get_value() while you scroll.
 *
 *   bin/php-gtk4 examples/demo.php GtkScrolledWindow
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkScrolledWindow',
    'the container that makes its child scrollable',
    function (GtkWindow $win): GtkWidget {
        $scrolled = new GtkScrolledWindow();
        $scrolled->set_vexpand(true);
        $scrolled->set_has_frame(true);
        $scrolled->set_min_content_height(160);

        // A child far taller than the window: that is the whole point.
        $column = new GtkBox(GtkOrientation::Vertical, 4);
        for ($i = 1; $i <= 40; $i++) {
            $row = new GtkLabel(sprintf('row %02d %s', $i, str_repeat('·', $i * 3)));
            $row->set_halign(GtkAlign::Start);
            $row->add_css_class('card');
            $column->append($row);
        }
        // A non-scrollable child is wrapped in a GtkViewport automatically.
        $scrolled->set_child($column);

        $status = Demo::label();
        $describe = function () use ($scrolled, $status): void {
            [$h, $v] = $scrolled->get_policy();
            $adj = $scrolled->get_vadjustment();
            $child = $scrolled->get_child();
            $status->set_markup(sprintf(
                "<tt>policy     %s / %s\nplacement  %s\nvadjust    %.0f of %.0f (page %.0f)\n"
                . 'child      %s</tt>',
                $h->name,
                $v->name,
                $scrolled->get_placement()->name,
                $adj->get_value(),
                $adj->get_upper(),
                $adj->get_page_size(),
                $child === null ? 'null' : $child::class,
            ));
        };
        $scrolled->get_vadjustment()->connect('value-changed', function () use ($describe): void {
            $describe();
        });

        $policies = GtkPolicyType::cases();
        $policyStep = 0;
        $policy = GtkButton::new_with_label('set_policy()');
        $policy->connect('clicked', function () use ($scrolled, $policies, &$policyStep, $describe): void {
            $next = $policies[++$policyStep % count($policies)];
            $scrolled->set_policy(GtkPolicyType::Never, $next);
            $describe();
        });

        $corners = GtkCornerType::cases();
        $cornerStep = 0;
        $placement = GtkButton::new_with_label('set_placement()');
        $placement->connect('clicked', function () use ($scrolled, $corners, &$cornerStep, $describe): void {
            $scrolled->set_placement($corners[++$cornerStep % count($corners)]);
            $describe();
        });

        $jump = GtkButton::new_with_label('scroll to end');
        $jump->connect('clicked', function () use ($scrolled): void {
            $adj = $scrolled->get_vadjustment();
            $adj->set_value($adj->get_value() > 0.0 ? 0.0 : $adj->get_upper());
        });

        $scrolled->set_policy(GtkPolicyType::Never, GtkPolicyType::Automatic);
        $describe();

        $buttons = new GtkBox(GtkOrientation::Horizontal, 6);
        foreach ([$policy, $placement, $jump] as $button) {
            $buttons->append($button);
        }
        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $status->set_halign(GtkAlign::Start);
        $page->append($status);
        $page->append($buttons);
        $page->append($scrolled);
        return $page;
    },
    520,
    420,
);
