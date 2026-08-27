<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\CairoContext;
use Gtk4\GdkRGBA;
use Gtk4\GtkDrawingArea;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkRGBA - a boxed colour value: cloneable, compared by value.
 *
 * Every swatch is parsed from a different CSS spelling and painted with
 * set_source_color(); the row below it shows to_string(), is_opaque() and what
 * equal() says about two independently parsed copies.
 *
 *   bin/php-gtk4 examples/demo.php GdkRGBA
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkRGBA',
    'a boxed colour value: cloneable, compared by value',
    function (GtkWindow $win): GtkWidget {
        /** @var list<array{string, string}> $inputs */
        $inputs = [
            ['#3584e4', 'hex'],
            ['rgb(46,194,126)', 'rgb()'],
            ['rgba(224,27,36,0.45)', 'rgba()'],
            ['goldenrod', 'name'],
        ];

        return Demo::canvas(520, 260, static function (
            GtkDrawingArea $area,
            CairoContext $cr,
            int $width,
            int $height,
        ) use ($inputs): void {
            Demo::sheet($cr);

            foreach ($inputs as $index => [$css, $kind]) {
                $color = new GdkRGBA($css);
                $x = 18.0 + $index * 124;

                // A checker behind the swatch, so a non-opaque alpha is visible.
                $cr->set_source_color(new GdkRGBA('#e0e0e0'));
                for ($i = 0; $i < 6; $i++) {
                    for ($j = 0; $j < 6; $j++) {
                        if ((($i + $j) % 2) === 0) {
                            $cr->rectangle($x + $i * 16, 30 + $j * 10, 16, 10);
                        }
                    }
                }
                $cr->fill();

                $cr->set_source_color($color);
                $cr->rectangle($x, 30, 96, 60);
                $cr->fill();

                Demo::text($cr, $x, 106, $kind, Demo::MUTED, 11);
                Demo::text($cr, $x, 122, $color->to_string(), Demo::INK, 11);
                Demo::text($cr, $x, 138, $color->is_opaque() ? 'opaque' : 'translucent', Demo::MUTED, 11);
            }

            // Value semantics: parsed twice, equal; mutated through a field, not equal.
            $a = new GdkRGBA('#3584e4');
            $b = new GdkRGBA('#3584e4');
            $c = clone $a;
            $c->alpha = 0.5;                      // fields are plain PHP properties
            Demo::text($cr, 18, 180, 'value semantics', Demo::MUTED, 12);
            $equal = sprintf('$a->equal($b)          %s', $a->equal($b) ? 'true' : 'false');
            Demo::text($cr, 18, 200, $equal, Demo::INK, 12);
            $cloned = sprintf('$c = clone $a; alpha=.5 -> equal  %s', $a->equal($c) ? 'true' : 'false');
            Demo::text($cr, 18, 218, $cloned, Demo::INK, 12);
            Demo::text(
                $cr,
                18,
                236,
                sprintf('$c = %s   red=%.2f green=%.2f blue=%.2f', $c->to_string(), $c->red, $c->green, $c->blue),
                Demo::INK,
                12,
            );

            // parse() returns false instead of throwing.
            $probe = new GdkRGBA();
            $parsed = sprintf('(new GdkRGBA)->parse("nope") -> %s', $probe->parse('nope') ? 'true' : 'false');
            Demo::text($cr, 18, 254, $parsed, Demo::MUTED, 12);
        });
    },
    540,
    300,
);
