<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GObject;
use Gtk4\GParamSpec;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GParamSpec - the metadata of a GObject property.
 *
 * Every notify handler is handed one. Four window properties are written below and
 * the table shows what their GParamSpec says about them: value type, nick, flags,
 * readability and the property's default.
 *
 *   bin/php-gtk4 examples/GParamSpec.php
 */

require __DIR__ . '/bootstrap.php';

Demo::run('GParamSpec', function (GtkWindow $win): GtkWidget {
    /** @var array<string, string> $rows */
    $rows = [];
    $label = Demo::label('writing properties...');

    $describe = static function (GParamSpec $spec): string {
        return sprintf(
            '%-15s %-12s %s%s flags=%-3d %-18s default=%s',
            $spec->get_name(),
            $spec->get_value_type(),
            $spec->is_readable() ? 'r' : '-',
            $spec->is_writable() ? 'w' : '-',
            $spec->get_flags(),
            '"' . ($spec->get_nick() ?? '') . '"',
            var_export($spec->get_default_value(), true),
        );
    };

    foreach (['title', 'default-width', 'resizable', 'modal'] as $property) {
        $win->connect('notify::' . $property, function (GObject $obj, GParamSpec $spec) use (
            &$rows,
            $label,
            $describe,
        ): void {
            $rows[$spec->get_name()] = $describe($spec);
            ksort($rows);
            $label->set_markup('<tt>' . htmlspecialchars(implode("\n", $rows)) . '</tt>');
        });
    }

    $win->title = 'GParamSpec';
    $win->default_width = 640;
    $win->resizable = false;
    $win->modal = true;

    return $label;
}, 660, 220);
