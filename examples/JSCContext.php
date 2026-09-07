<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkBox;
use Gtk4\GtkEntry;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\JSCContext;
use Gtk4\JSCValue;

/*
 * Gtk4\JSCContext - JavaScript without a web page (needs --enable-gtk4-webkit).
 *
 * JavaScriptCore's context evaluates a script and answers with a JSCValue; a value set from PHP
 * (set_value()) is a global the next script can read, and an exception a script throws stays in
 * the context (get_exception()) instead of crossing into PHP. Type an expression and press
 * Enter; `php` is an object PHP put there.
 *
 *   bin/php-gtk4 examples/demo.php JSCContext
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'JSCContext',
    'A JavaScript engine in the process: evaluate expressions, share globals, catch exceptions.',
    function (GtkWindow $win): GtkWidget {
        $ctx = new JSCContext();
        $ctx->set_value('php', JSCValue::new_from_json($ctx, json_encode([
            'version' => PHP_VERSION,
            'gtk4' => \Gtk4\VERSION,
            'people' => array_map(static fn(array $p): string => $p['name'], Demo::people()),
        ], JSON_THROW_ON_ERROR)));

        $out = Demo::label();
        $out->set_size_request(440, -1);
        $out->set_selectable(true);
        $history = [];
        // What kind of JavaScript value this is, by its predicates.
        $kind = static fn(JSCValue $value): string => match (true) {
            $value->is_undefined() => 'undefined',
            $value->is_null() => 'null',
            $value->is_boolean() => 'boolean',
            $value->is_number() => 'number',
            $value->is_string() => 'string',
            $value->is_function() => 'function',
            $value->is_array() => 'array',
            default => 'object',
        };

        $entry = new GtkEntry();
        $entry->set_placeholder_text('an expression, e.g. php.people.map(p => p.split(" ")[0]).join(", ")');
        $entry->connect('activate', static function (GtkEntry $e) use ($ctx, $out, $kind, &$history): void {
            $code = $e->get_text();
            $value = $ctx->evaluate($code, -1);
            $exception = $ctx->get_exception();
            if ($exception !== null) {
                $line = sprintf(
                    '<span foreground="%s">%s: %s</span>',
                    Demo::WARN,
                    htmlspecialchars($exception->get_name()),
                    htmlspecialchars($exception->get_message()),
                );
                $ctx->clear_exception();
            } else {
                $line = sprintf(
                    '<b>%s</b>  <span foreground="%s">%s</span>',
                    htmlspecialchars($value->to_json(0) ?: $value->to_string()),
                    Demo::MUTED,
                    htmlspecialchars($kind($value)),
                );
            }
            $prompt = sprintf('<span foreground="%s">› %s</span>', Demo::ACCENT, htmlspecialchars($code));
            $history[] = $prompt . "\n" . $line;
            $history = array_slice($history, -8);
            $out->set_markup(implode("\n\n", $history));
            Demo::status(sprintf('%d expression(s) evaluated', count($history)));
        });

        $box = new GtkBox(GtkOrientation::Vertical, 10);
        $box->append($entry);
        $box->append($out);
        $entry->set_text('php.people.length + " people, PHP " + php.version');
        $entry->emit('activate');
        return $box;
    },
    520,
    420,
);
