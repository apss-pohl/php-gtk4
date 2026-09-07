<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\JSCContext;
use Gtk4\JSCValue;

/*
 * Gtk4\JSCValue - a JavaScript value held from PHP (needs --enable-gtk4-webkit).
 *
 * Values are built from PHP (new_number(), new_string(), new_from_json(), new_array_from_strv())
 * or come out of a script; the predicates say what they are, the to_*() conversions bring them
 * back, and a function value is called with a PHP list of values (function_call()). Each click
 * runs the next step of a small round trip and shows what came out.
 *
 *   bin/php-gtk4 examples/demo.php JSCValue
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'JSCValue',
    'JavaScript values from PHP and back: numbers, strings, objects, arrays, functions.',
    function (GtkWindow $win): GtkWidget {
        $ctx = new JSCContext();
        $label = Demo::label();
        $label->set_size_request(460, -1);

        // The steps, in order; each one a line of the story.
        $steps = [
            'a number' => static function (JSCContext $ctx): string {
                $v = JSCValue::new_number($ctx, 6.5);
                return sprintf(
                    'new_number(6.5): is_number=%s, to_int32()=%d, to_string()=%s',
                    $v->is_number() ? 'true' : 'false',
                    $v->to_int32(),
                    $v->to_string(),
                );
            },
            'a string' => static function (JSCContext $ctx): string {
                $v = JSCValue::new_string($ctx, 'héllo wörld');
                $length = $ctx->evaluate('"héllo wörld".length', -1)->to_int32();
                return sprintf('new_string(): to_string()=%s, length via script: %d', $v->to_string(), $length);
            },
            'an array from a PHP list' => static function (JSCContext $ctx): string {
                $names = array_map(static fn(array $p): string => $p['name'], Demo::people());
                $v = JSCValue::new_array_from_strv($ctx, $names);
                return sprintf(
                    'new_array_from_strv(): is_array=%s, [1]=%s, to_json()=%s',
                    $v->is_array() ? 'true' : 'false',
                    $v->object_get_property_at_index(1)->to_string(),
                    $v->to_json(0),
                );
            },
            'an object from JSON' => static function (JSCContext $ctx): string {
                $v = JSCValue::new_from_json($ctx, '{"born": 1906, "name": "Grace"}');
                $v->object_set_property('language', JSCValue::new_string($ctx, 'COBOL'));
                return sprintf(
                    'new_from_json() + object_set_property(): %s; properties: %s',
                    $v->to_json(0),
                    implode(', ', $v->object_enumerate_properties()),
                );
            },
            'a function called from PHP' => static function (JSCContext $ctx): string {
                $fn = $ctx->evaluate('(function (a, b) { return a + " & " + b; })', -1);
                $r = $fn->function_call([JSCValue::new_string($ctx, 'PHP'), JSCValue::new_string($ctx, 'JavaScript')]);
                $isFunction = $fn->is_function() ? 'true' : 'false';
                return sprintf('function_call([…]): is_function=%s → %s', $isFunction, $r->to_string());
            },
            'a method invoked on an object' => static function (JSCContext $ctx): string {
                $o = $ctx->evaluate('({ total: 0, add(n) { this.total += n; return this; } })', -1);
                $o->object_invoke_method('add', [JSCValue::new_number($ctx, 40.0)])
                    ->object_invoke_method('add', [JSCValue::new_number($ctx, 2.0)]);
                $total = $o->object_get_property('total')->to_int32();
                return sprintf('object_invoke_method("add", […]) twice: total=%d', $total);
            },
            'null and undefined' => static function (JSCContext $ctx): string {
                return sprintf(
                    'new_null(): is_null=%s; new_undefined(): is_undefined=%s; typeof from a script: %s',
                    JSCValue::new_null($ctx)->is_null() ? 'true' : 'false',
                    JSCValue::new_undefined($ctx)->is_undefined() ? 'true' : 'false',
                    $ctx->evaluate('typeof undefined', -1)->to_string(),
                );
            },
        ];

        $lines = [];
        $at = 0;
        $names = array_keys($steps);
        $button = GtkButton::new_with_label('Next value ▶');
        $button->connect('clicked', static function () use ($ctx, $steps, $names, &$at, &$lines, $label): void {
            $name = $names[$at % count($names)];
            $result = $steps[$name]($ctx);
            $lines[] = sprintf("<b>%s</b>\n<small>%s</small>", htmlspecialchars($name), htmlspecialchars($result));
            $lines = array_slice($lines, -4);
            $label->set_markup(implode("\n\n", $lines));
            Demo::status(sprintf('step %d of %d', $at % count($names) + 1, count($names)));
            $at++;
        });
        $button->emit('clicked');

        $box = new GtkBox(GtkOrientation::Vertical, 12);
        $box->append($button);
        $box->append($label);
        return $box;
    },
    540,
    400,
);
