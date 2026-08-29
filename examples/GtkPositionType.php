<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkLabel;
use Gtk4\GtkNotebook;
use Gtk4\GtkPositionType;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkPositionType - Left, Right, Top, Bottom.
 *
 * A GEnum bound as a native PHP enum, used wherever GTK asks "which side":
 * GtkNotebook::set_tab_pos() among others. The notebook's tabs move around
 * every case in turn; the label on the page names the current one.
 *
 *   bin/php-gtk4 examples/demo.php GtkPositionType
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkPositionType',
    'Left, Right, Top, Bottom - the tabs of a notebook walk around every side',
    function (GtkWindow $win): GtkWidget {
        $notebook = new GtkNotebook();
        $notebook->set_vexpand(true);
        $face = Demo::label();
        $notebook->append_page($face, new GtkLabel('tab_pos'));
        $notebook->append_page(Demo::label('second page'), new GtkLabel('another'));

        $cases = GtkPositionType::cases();
        $step = 0;
        $show = function () use ($notebook, $face, $cases, &$step): void {
            $pos = $cases[$step % count($cases)];
            $notebook->set_tab_pos($pos);   // typed setter takes the enum, never an int
            $face->set_markup(sprintf(
                "tabs at <b>%s</b> <small>(%d)</small>\n\n<small>%s</small>",
                $notebook->get_tab_pos()->name,
                $pos->value,
                implode(' · ', array_map(static fn(GtkPositionType $p): string => $p->name, $cases)),
            ));
            Demo::status(sprintf('%s = GtkPositionType::from(%d)', $pos->name, $pos->value));
        };

        $show();
        GLib::timeout_add(1100, function () use ($show, &$step): bool {
            $step++;
            $show();
            return true;
        });

        return $notebook;
    },
    480,
    340,
);
