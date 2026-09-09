<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GrapheneVec2;
use Gtk4\GtkButton;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GrapheneVec2 - a vector of 2 components.
 *
 * Opaque, like the matrix: alloc() hands out an uninitialised one and init() fills it in.
 * Arithmetic answers with a new vector, so the operands are left as they were.
 *
 *   bin/php-gtk4 examples/demo.php GrapheneVec2
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GrapheneVec2',
    'a vector of 2 components',
    function (GtkWindow $win): GtkWidget {
        $label = Demo::label();
        $at = 0;
        $render = function () use (&$at, $label): void {
            $a = GrapheneVec2::alloc();
            $b = GrapheneVec2::alloc();
            $a->init(1.0 + $at, 2.0);
            $b->init(3.0, 4.0);
            $sum = $a->add($b);
            $label->set_markup(sprintf(
                "<span size=\"x-large\"><b>|a| = %.2f</b></span>\n\n"
                . "<small>a + b has length %.2f · a is still %.2f long</small>\n"
                . '<small>a · b = %.2f</small>',
                $a->length(),
                $sum->length(),
                $a->length(),
                $a->dot($b),
            ));
            Demo::status('click for another pair');
        };

        $button = new GtkButton();
        $button->set_child($label);
        $button->connect('clicked', function () use (&$at, $render): void {
            $at++;
            $render();
        });
        $render();
        return $button;
    },
    460,
    200,
);
