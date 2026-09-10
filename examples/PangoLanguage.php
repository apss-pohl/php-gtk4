<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\PangoLanguage;

/*
 * Gtk4\PangoLanguage - an RFC-3066 language tag as an opaque value Pango interns. Its sample
 * string is the pangram Pango keeps for that language, which is what you measure a font with;
 * matches() answers the range question a font fallback asks. Click through the tags to see both.
 *
 *   bin/php-gtk4 examples/demo.php PangoLanguage
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'PangoLanguage',
    'a language tag, its pangram, and what it matches',
    function (GtkWindow $win): GtkWidget {
        $tags = ['de-DE', 'en-GB', 'ja-JP', 'el-GR', 'ru-RU'];
        $at = 0;

        $sample = new GtkLabel('');
        $sample->wrap = true;
        $facts = Demo::label();

        $show = function () use (&$at, $tags, $sample, $facts): void {
            $name = $tags[$at % count($tags)];
            // from_string() answers null only for a null tag; an unknown one is still a tag.
            $language = PangoLanguage::from_string($name) ?? PangoLanguage::get_default();

            $sample->set_markup(sprintf(
                '<span size="x-large">%s</span>',
                htmlspecialchars($language->get_sample_string()),
            ));
            $facts->set_markup(sprintf(
                "<tt>to_string()        %s\nmatches('%s')  %s\nmatches('de')      %s\n"
                . 'get_default()      %s</tt>',
                htmlspecialchars($language->to_string()),
                htmlspecialchars(substr($name, 0, 2)),
                $language->matches(substr($name, 0, 2)) ? 'true' : 'false',
                $language->matches('de') ? 'true' : 'false',
                htmlspecialchars(PangoLanguage::get_default()->to_string()),
            ));
            Demo::status($language->to_string());
        };
        $show();

        $next = GtkButton::new_with_label('next language');
        $next->connect('clicked', function () use (&$at, $show): void {
            $at++;
            $show();
        });

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($sample);
        $page->append($facts);
        $page->append($next);
        return $page;
    },
);
