<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GObject;
use Gtk4\GtkButton;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\PhpValue;

/*
 * Gtk4\PhpValue - a GObject carrying an arbitrary PHP value.
 *
 * The bridge for putting PHP data where GTK insists on GObjects, above all in a
 * GListStore. The value is held by reference, so arrays and objects keep their
 * identity; clicking mutates it through set_value() and the render below follows.
 *
 *   bin/php-gtk4 examples/PhpValue.php
 */

require __DIR__ . '/bootstrap.php';

Demo::run('PhpValue', function (GtkWindow $win): GtkWidget {
    $label = Demo::label();
    $button = new GtkButton();
    $button->set_child($label);

    $row = new PhpValue(['name' => 'Ada Lovelace', 'born' => 1815, 'tags' => ['analytical engine']]);
    $scalar = new PhpValue(42);
    $empty = new PhpValue();

    // Objects keep their identity through the round trip.
    $subject = new \ArrayObject(['kept' => 'by reference']);
    $wrapped = new PhpValue($subject);

    $render = function () use ($label, $row, $scalar, $empty, $wrapped, $subject): void {
        $label->set_markup(sprintf(
            "<b>PhpValue</b> <small>(a GObject holding a PHP value)</small>\n\n<tt>%s</tt>\n\n"
            . "<small>click to append a tag through set_value()</small>",
            htmlspecialchars(sprintf(
                "array   %s\nint     %s\nnull    %s\nobject  %s (same instance: %s)\n\n"
                . 'instanceof GObject: %s',
                json_encode($row->get_value()),
                var_export($scalar->get_value(), true),
                var_export($empty->get_value(), true),
                get_debug_type($wrapped->get_value()),
                $wrapped->get_value() === $subject ? 'yes' : 'no',
                $row instanceof GObject ? 'yes - it is one' : 'no',
            )),
        ));
    };

    $tags = 0;
    $button->connect('clicked', function () use ($row, &$tags, $render): void {
        $tags++;
        $value = $row->get_value();
        if (is_array($value) && is_array($value['tags'] ?? null)) {
            $value['tags'][] = 'tag ' . $tags;
            $row->set_value($value);
        }
        $render();
    });

    $render();
    return $button;
}, 560, 320);
