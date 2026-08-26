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
 * Every notify handler is handed one. Four properties are written below and the
 * table shows what their GParamSpec says about them: value type, nick, flags,
 * readability and the property's default.
 *
 * The properties are written on a window of this page's own, never on the one you
 * are reading this in - `modal` and `resizable` on the application's window would
 * be a nasty surprise.
 *
 *   bin/php-gtk4 examples/demo.php GParamSpec
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GParamSpec',
    'the metadata of a GObject property',
    function (GtkWindow $win): GtkWidget {
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

        // Never realised, never shown: a GObject is enough to notify about.
        $subject = new GtkWindow();

        foreach (['title', 'default-width', 'resizable', 'modal'] as $property) {
            $subject->connect('notify::' . $property, function (
                GObject $obj,
                GParamSpec $spec,
            ) use (
                &$rows,
                $label,
                $describe,
            ): void {
                $rows[$spec->get_name()] = $describe($spec);
                ksort($rows);
                $label->set_markup('<tt>' . htmlspecialchars(implode("\n", $rows)) . '</tt>');
            });
        }

        $subject->title = 'GParamSpec';
        $subject->default_width = 640;
        $subject->resizable = false;
        $subject->modal = true;

        return $label;
    },
    660,
    220,
);
