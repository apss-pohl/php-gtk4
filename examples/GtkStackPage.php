<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkOrientation;
use Gtk4\GtkStack;
use Gtk4\GtkStackPage;
use Gtk4\GtkStackSwitcher;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkStackPage - the per-child record a GtkStack keeps.
 *
 * You never construct one: add_titled()/add_named() return it and get_page()
 * finds it again for a child. It carries what the stack knows about the
 * child - name, title, icon_name, needs_attention, visible - and the switcher
 * above the stack reflects every change. A timer edits one page at a time.
 *
 *   bin/php-gtk4 examples/demo.php GtkStackPage
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkStackPage',
    'the per-child record a stack keeps: name, title, icon, attention flag',
    function (GtkWindow $win): GtkWidget {
        $stack = new GtkStack();
        $stack->set_vexpand(true);

        /** @var list<GtkStackPage> $pages */
        $pages = [];
        $icons = [
            'inbox' => 'mail-unread-symbolic',
            'drafts' => 'document-edit-symbolic',
            'sent' => 'mail-send-symbolic',
        ];
        foreach ($icons as $name => $icon) {
            $face = Demo::label("<span size='x-large'>$name</span>");
            $face->add_css_class('card');
            $face->set_vexpand(true);
            // add_titled() hands the page back; get_page($face) would return the same object.
            $page = $stack->add_titled($face, $name, ucfirst($name));
            $page->set_icon_name($icon);
            $pages[] = $page;
        }

        $switcher = new GtkStackSwitcher();
        $switcher->set_stack($stack);
        $switcher->set_halign(GtkAlign::Center);

        $status = Demo::label();
        $status->set_halign(GtkAlign::Start);
        $describe = function () use ($stack, $status): void {
            $child = $stack->get_visible_child();
            $page = $child === null ? null : $stack->get_page($child);
            $status->set_markup($page === null ? '<tt>no visible child</tt>' : sprintf(
                "<tt>get_page(visible child)\n  name             %s\n  title            %s\n"
                . "  icon_name        %s\n  needs_attention  %s\n  visible          %s</tt>",
                $page->get_name() ?? 'null',
                htmlspecialchars($page->get_title() ?? 'null'),
                $page->get_icon_name() ?? 'null',
                $page->get_needs_attention() ? 'true' : 'false',
                $page->get_visible() ? 'true' : 'false',
            ));
        };

        // Toggle the flags on one page after another so the switcher shows them.
        $step = 0;
        GLib::timeout_add(1200, function () use ($pages, $describe, &$step): bool {
            $page = $pages[$step % count($pages)];
            match (intdiv($step, count($pages)) % 3) {
                0 => $page->set_needs_attention(!$page->get_needs_attention()),
                1 => $page->set_title(strtoupper((string) $page->get_name()) . ' (' . $step . ')'),
                default => $page->set_visible(!$page->get_visible()),
            };
            $step++;
            $describe();
            Demo::status(sprintf(
                '%s: needs_attention=%s',
                $page->get_name(),
                $page->get_needs_attention() ? 'yes' : 'no',
            ));
            return true;
        });
        $stack->connect('notify::visible-child', $describe);
        $describe();

        $box = new GtkBox(GtkOrientation::Vertical, 12);
        $box->append($switcher);
        $box->append($status);
        $box->append($stack);
        return $box;
    },
    520,
    380,
);
