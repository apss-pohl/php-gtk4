<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GError;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkCssProvider;
use Gtk4\GtkCssSection;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkCssSection - where a stylesheet went wrong.
 *
 * GtkCssProvider's loaders never throw: CSS is parsed leniently, the good rules
 * are kept and every problem is reported through the `parsing-error` signal,
 * which hands the handler the section it happened in and a GError. That is the
 * only way to see a typo in your stylesheet, so a page that loads CSS should
 * connect it. This one loads deliberately broken CSS and prints the report.
 *
 *   bin/php-gtk4 examples/demo.php GtkCssSection
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkCssSection',
    'the part of a stylesheet a parsing error happened in',
    function (GtkWindow $win): GtkWidget {
        $broken = <<<'CSS'
            .good { color: green; }
            .bad  { color: nonsense-value; }
            @bogus;
            .also-good { font-weight: bold }
            CSS;

        $report = Demo::label();
        $provider = new GtkCssProvider();

        /** @var list<string> $errors */
        $errors = [];
        // The handler gets (provider, section, error). GTK keeps parsing afterwards,
        // so one load can report several problems.
        $provider->connect(
            'parsing-error',
            function (GtkCssProvider $self, GtkCssSection $section, GError $error) use (&$errors): void {
                $start = $section->get_start_location();
                $errors[] = sprintf(
                    "line %d, column %d: %s\n    section  %s\n    parent   %s",
                    $start['lines'] + 1,          // GtkCssLocation counts from 0
                    $start['line_chars'] + 1,
                    $error->getMessage(),
                    $section->to_string(),        // "&lt;data&gt;:2:9-23", as GTK prints it
                    $section->get_parent()?->to_string() ?? 'none (top level)',
                );
            },
        );

        $load = function () use ($provider, $broken, &$errors, $report): void {
            $errors = [];
            $provider->load_from_string($broken);
            $report->set_markup(sprintf(
                "<b>%d parsing error(s)</b>\n<tt>%s</tt>\n\n<small>the rules GTK kept:\n<tt>%s</tt></small>",
                count($errors),
                htmlspecialchars(implode("\n\n", $errors)),
                htmlspecialchars(trim($provider->to_string())),
            ));
            Demo::status(sprintf('%d parsing error(s)', count($errors)));
        };
        $load();

        $again = GtkButton::new_with_label('parse it again');
        $again->add_css_class('flat');
        $again->connect('clicked', $load);

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($report);
        $page->append($again);
        return $page;
    },
);
