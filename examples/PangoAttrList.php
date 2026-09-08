<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkButton;
use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\PangoAttrList;

/*
 * Gtk4\PangoAttrList - what styles a run of text, without markup in the text itself.
 *
 * A label can be given markup, or it can be given the text and an attribute list that says
 * which range gets which weight, colour or size. The list has a textual form of its own -
 * from_string() and to_string() - which is what this page walks through: same text, different
 * attributes, and the markup route at the end for comparison.
 *
 *   bin/php-gtk4 examples/demo.php PangoAttrList
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'PangoAttrList',
    'what styles a run of text, without markup in the text',
    function (GtkWindow $win): GtkWidget {
        $text = 'attributes over plain text';

        /** @var list<array{string, ?string}> $steps */
        $steps = [
            ['no attributes at all', null],
            ['0..10 bold', '0 10 weight bold'],
            ['0..10 bold, 11..15 in the accent colour', "0 10 weight bold\n11 15 foreground " . Demo::ACCENT],
            ['the whole run larger and italic', "0 -1 size 20pt\n0 -1 style italic"],
        ];

        $label = new GtkLabel($text);
        $description = Demo::label();
        $at = 0;
        $apply = function () use (&$at, $steps, $label, $description, $text): void {
            [$name, $spec] = $steps[$at % count($steps)];
            $list = $spec === null ? new PangoAttrList() : PangoAttrList::from_string($spec);
            $label->set_text($text);
            $label->set_attributes($list ?? new PangoAttrList());
            $description->set_markup(sprintf(
                "<small>%s</small>\n\n<tt>%s</tt>",
                htmlspecialchars($name),
                htmlspecialchars($spec ?? '(empty list)'),
            ));
            Demo::status(sprintf(
                'to_string() answers with %s',
                ($list?->to_string() ?? '') === '' ? 'nothing - the list is empty' : 'the same spec back',
            ));
        };

        $box = new \Gtk4\GtkBox(\Gtk4\GtkOrientation::Vertical, 14);
        $label->set_size_request(-1, 60);
        $box->append($label);
        $box->append($description);

        $button = new GtkButton();
        $button->set_child(Demo::label('next attributes'));
        $button->connect('clicked', function () use (&$at, $apply): void {
            $at++;
            $apply();
        });
        $box->append($button);
        $apply();
        return $box;
    },
    460,
    280,
);
