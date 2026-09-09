<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkRoot;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkRoot - the interface of toplevels: the focus widget lives here.
 *
 * GtkWindow implements GtkRoot. get_focus()/set_focus() belong to the root, not to
 * the window: a timer moves the focus across three buttons through the root API
 * and reports which widget has it.
 *
 *   bin/php-gtk4 examples/demo.php GtkRoot
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkRoot',
    'the toplevel interface: who has the focus',
    function (GtkWindow $win): GtkWidget {
        $row = new GtkBox(GtkOrientation::Horizontal, 8);
        $buttons = [];
        foreach (['alpha', 'beta', 'gamma'] as $name) {
            $button = GtkButton::new_with_label($name);
            $button->set_name($name);
            $row->append($button);
            $buttons[] = $button;
        }
        $step = 0;
        GLib::timeout_add(900, function () use ($win, $buttons, &$step): bool {
            $root = $buttons[0]->get_root();                  // the window, typed as GtkRoot
            if (!$root instanceof GtkRoot) {
                Demo::status('not mounted in a window yet');
                return true;
            }
            $root->set_focus($buttons[$step++ % count($buttons)]);
            $focus = $root->get_focus();
            Demo::status(sprintf(
                '%s is the GtkRoot; get_focus() = %s',
                $win::class,
                $focus instanceof GtkButton ? $focus->get_name() : 'null',
            ));
            return true;
        });
        return $row;
    },
    480,
    340,
);
