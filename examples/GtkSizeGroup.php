<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkSizeGroup;
use Gtk4\GtkSizeGroupMode;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkSizeGroup - widgets that agree on a size.
 *
 * Not a widget: an object that makes its members request the same width, height
 * or both, however different their contents. Three buttons with labels of very
 * different length sit in a row; clicking a button removes it from the group (or
 * adds it back), and the fourth button cycles set_mode(). Watch them snap.
 *
 *   bin/php-gtk4 examples/demo.php GtkSizeGroup
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkSizeGroup',
    'widgets that agree on a width, a height or both',
    function (GtkWindow $win): GtkWidget {
        $group = new GtkSizeGroup(GtkSizeGroupMode::Horizontal);

        $row = new GtkBox(GtkOrientation::Horizontal, 6);
        $row->set_halign(GtkAlign::Start);
        $members = [];
        foreach (['ok', 'a longer label', 'the longest label of the three'] as $text) {
            $button = GtkButton::new_with_label($text);
            $group->add_widget($button);
            $row->append($button);
            $members[] = $button;
        }

        $status = Demo::label();
        $describe = function () use ($group, $members, $status): void {
            $inGroup = $group->get_widgets();
            $widths = array_map(
                static fn(GtkButton $b): string => sprintf(
                    '%s %dpx',
                    in_array($b, $inGroup, true) ? 'in ' : 'out',
                    $b->get_width(),
                ),
                $members,
            );
            $status->set_markup(sprintf(
                "<tt>mode     %s\nmembers  %d\n%s</tt>",
                $group->get_mode()->name,
                count($inGroup),
                htmlspecialchars(implode("\n", $widths)),
            ));
        };

        // Clicking a member toggles its membership: out of the group it shrinks.
        foreach ($members as $button) {
            $button->connect('clicked', function (GtkButton $self) use ($group, $describe): void {
                if (in_array($self, $group->get_widgets(), true)) {
                    $group->remove_widget($self);
                } else {
                    $group->add_widget($self);
                }
                // Widths update after the next layout pass.
                GLib::idle_add(function () use ($describe): bool {
                    $describe();
                    return false;
                });
            });
        }

        $modes = GtkSizeGroupMode::cases();
        $modeStep = 1;
        $mode = GtkButton::new_with_label('set_mode()');
        $mode->set_halign(GtkAlign::Start);
        $mode->connect('clicked', function () use ($group, $modes, &$modeStep, $describe): void {
            $group->set_mode($modes[++$modeStep % count($modes)]);
            GLib::idle_add(function () use ($describe): bool {
                $describe();
                return false;
            });
        });

        // Widths are only known once mapped - refresh once the window shows.
        $row->connect('map', function () use ($describe): void {
            GLib::idle_add(function () use ($describe): bool {
                $describe();
                return false;
            });
        });
        $describe();

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $status->set_halign(GtkAlign::Start);
        $page->append($status);
        $page->append($row);
        $page->append($mode);
        return $page;
    },
    560,
    320,
);
