<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkLabel - text, Pango markup and selections.
 *
 * The label is selectable: drag across it and the window title reports
 * get_selection_bounds() live, which is a boolean C function whose out
 * parameters come back as a list (or null when nothing is selected).
 *
 *   bin/php-gtk4 examples/GtkLabel.php
 */

require __DIR__ . '/bootstrap.php';

Demo::run('GtkLabel', function (GtkWindow $win): GtkWidget {
    $label = new GtkLabel('plain text, replaced by markup below');
    $label->set_markup(
        "<span size=\"xx-large\" foreground=\"" . Demo::ACCENT . "\">Gtk4\\GtkLabel</span>\n\n"
        . "<b>bold</b> · <i>italic</i> · <u>underline</u> · <s>strike</s> · <tt>monospace</tt>\n"
        . "<span foreground=\"" . Demo::WARN . "\">coloured</span> · <sub>sub</sub> · <sup>sup</sup>\n\n"
        . 'This paragraph is long enough to wrap, because wrap is on and the window is narrow.',
    );
    $label->set_selectable(true);
    $label->wrap = true;
    $label->use_markup = true;

    $label->select_region(0, 14);   // pre-select the heading

    // Poll the selection instead of rewriting the label - rewriting it would
    // clear the very selection we are reporting.
    GLib::timeout_add(250, function () use ($label, $win): bool {
        $bounds = $label->get_selection_bounds();
        $win->set_title($bounds === null
            ? 'php-gtk4 · GtkLabel — nothing selected'
            : sprintf('php-gtk4 · GtkLabel — selection %d..%d of %d', $bounds[0], $bounds[1], mb_strlen($label->get_text())));
        return true;
    });

    return $label;
}, 460, 320);
