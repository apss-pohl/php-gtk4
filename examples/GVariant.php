<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\GVariant;

/*
 * Gtk4\GVariant - a GVariant with a type PHP spelled.
 *
 * GVariant values are plain PHP values everywhere (action states, D-Bus bodies, menu targets)
 * and where nothing declares a type it is inferred: a list is `as` or `av`, an int `i`, a
 * string `s`. That cannot spell a tuple inside a variant - what a dbusmenu GetLayout reply
 * carries for every menu item - nor a byte array from a string. A GVariant handle carries the
 * value already typed and is accepted wherever a plain value would be inferred.
 *
 * The page builds the same dbusmenu layout twice, with plain values and with typed handles,
 * and shows GLib's own text form of each; the button adds an item to both.
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GVariant',
    'a value with a type PHP spelled: tuples inside variants, byte arrays',
    function (GtkWindow $win): GtkWidget {
        $box = new GtkBox(GtkOrientation::Vertical, 12);
        $label = Demo::label();
        $items = [[1, 'Open'], [2, 'Quit']];

        $render = function () use ($label, &$items): void {
            $children = fn(callable $item): array => array_map(fn(array $i) => $item($i[0], $i[1]), $items);
            // inference: every child is a PHP list, which under a `v` becomes an `av`
            $plain = new GVariant('(u(ia{sv}av))', [1, [0, [], $children(
                fn(int $id, string $l) => [$id, ['label' => $l], []],
            )]]);
            // typed: every child is an (ia{sv}av), which is what a dbusmenu client reads
            $typed = new GVariant('(u(ia{sv}av))', [1, [0, [], $children(
                fn(int $id, string $l) => new GVariant('(ia{sv}av)', [$id, ['label' => $l], []]),
            )]]);
            $bytes = new GVariant('ay', "\x89PNG");
            $label->set_markup(sprintf(
                "<b>GVariant</b>\n\n<b>plain lists inside the v</b> - what inference spells\n<tt>%s</tt>\n\n"
                . "<b>typed handles inside the v</b> - <tt>new GVariant('(ia{sv}av)', ...)</tt>\n"
                . "<tt>%s</tt>\n\n"
                . "<b>bytes</b> - <tt>new GVariant('ay', \"\\x89PNG\")</tt>, a string would be an <tt>s</tt>\n"
                . "<tt>%s</tt>\n\n"
                . 'unpack() gives the plain value back: <tt>%s</tt>',
                htmlspecialchars(wordwrap($plain->print(true), 72, "\n", true)),
                htmlspecialchars(wordwrap($typed->print(true), 72, "\n", true)),
                htmlspecialchars($bytes->print(true)),
                htmlspecialchars(json_encode($typed->unpack(), JSON_THROW_ON_ERROR)),
            ));
            Demo::status(sprintf(
                'typed == clone: %s, typed == plain: %s',
                $typed == clone $typed ? 'true' : 'false',
                $typed == $plain ? 'true' : 'false',
            ));
        };

        $add = new GtkButton();
        $add->set_label('Add a menu item');
        $add->connect('clicked', function () use (&$items, $render): void {
            $items[] = [count($items) + 1, 'Item ' . (count($items) + 1)];
            $render();
        });

        $box->append($label);
        $box->append($add);
        $render();
        return $box;
    },
    720,
    520,
);
