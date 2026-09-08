<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GrapheneMatrix;
use Gtk4\GraphenePoint3D;
use Gtk4\GtkButton;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GrapheneMatrix - the 4x4 matrix a transform is made of.
 *
 * GskTransform and GtkSnapshot both take one, which is how an arbitrary transform reaches GTK.
 * It is opaque - `new` is private and alloc() hands out an uninitialised one, which is then
 * init_*()ed - and every operation answers with a new matrix rather than changing this one.
 *
 *   bin/php-gtk4 examples/demo.php GrapheneMatrix
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GrapheneMatrix',
    'the 4x4 matrix a transform is made of',
    function (GtkWindow $win): GtkWidget {
        /** @var list<array{string, callable(): GrapheneMatrix}> $steps */
        $steps = [
            ['init_identity()', static function (): GrapheneMatrix {
                $m = GrapheneMatrix::alloc();
                $m->init_identity();
                return $m;
            }],
            ['init_scale(2, 3, 1)', static function (): GrapheneMatrix {
                $m = GrapheneMatrix::alloc();
                $m->init_scale(2.0, 3.0, 1.0);
                return $m;
            }],
            ['init_translate(10, 20, 0)', static function (): GrapheneMatrix {
                $m = GrapheneMatrix::alloc();
                $p = new GraphenePoint3D();
                $p->init(10.0, 20.0, 0.0);
                $m->init_translate($p);
                return $m;
            }],
            ['init_rotate(30 degrees about z)', static function (): GrapheneMatrix {
                $m = GrapheneMatrix::alloc();
                $axis = \Gtk4\GrapheneVec3::alloc();
                $axis->init(0.0, 0.0, 1.0);
                $m->init_rotate(30.0, $axis);
                return $m;
            }],
        ];

        $label = Demo::label();
        $at = 0;
        $render = function () use (&$at, $steps, $label): void {
            [$name, $build] = $steps[$at % count($steps)];
            $m = $build();
            $rows = [];
            for ($row = 0; $row < 4; $row++) {
                $cells = [];
                for ($col = 0; $col < 4; $col++) {
                    $cells[] = sprintf('%6.2f', $m->get_value($row, $col));
                }
                $rows[] = implode(' ', $cells);
            }
            $label->set_markup(sprintf(
                "<span size=\"large\"><b>%s</b></span>\n<tt>%s</tt>",
                htmlspecialchars($name),
                htmlspecialchars(implode("\n", $rows)),
            ));
            Demo::status(sprintf(
                'identity: %s · 2D: %s · determinant %.2f · x scale %.2f',
                $m->is_identity() ? 'yes' : 'no',
                $m->is_2d() ? 'yes' : 'no',
                $m->determinant(),
                $m->get_x_scale(),
            ));
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
    280,
);
