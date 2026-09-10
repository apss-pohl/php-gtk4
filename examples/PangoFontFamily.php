<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkBox;
use Gtk4\GtkDropDown;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\PangoFontFamily;

/*
 * Gtk4\PangoFontFamily - one entry of the font map: a name, whether it is monospace or variable,
 * and its faces, which it lists as a GListModel of its own. The drop-down below is the font map
 * walked with get_item(); pick a family and the sample is set in it.
 *
 *   bin/php-gtk4 examples/demo.php PangoFontFamily
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'PangoFontFamily',
    'a name from the font map, its faces, and whether it is monospace',
    function (GtkWindow $win): GtkWidget {
        $map = $win->create_pango_context()->get_font_map();
        if ($map === null) {
            return Demo::label('<b>this context has no font map</b>');
        }

        /** @var list<PangoFontFamily> $families */
        $families = [];
        for ($i = 0; $i < $map->get_n_items(); $i++) {
            $family = $map->get_item($i);
            if ($family instanceof PangoFontFamily) {
                $families[] = $family;
            }
        }
        usort(
            $families,
            static fn(PangoFontFamily $a, PangoFontFamily $b): int => strcasecmp($a->get_name(), $b->get_name()),
        );

        $sample = new GtkLabel('');
        $facts = Demo::label();
        $show = function (PangoFontFamily $family) use ($sample, $facts): void {
            $sample->set_markup(sprintf(
                '<span face="%s" size="x-large">Hamburgefonstiv 0123</span>',
                htmlspecialchars($family->get_name()),
            ));
            $facts->set_markup(sprintf(
                "<tt>get_name()       %s\nis_monospace()   %s\nis_variable()    %s\n"
                . 'get_n_items()    %d faces</tt>',
                htmlspecialchars($family->get_name()),
                $family->is_monospace() ? 'true' : 'false',
                $family->is_variable() ? 'true' : 'false',
                $family->get_n_items(),
            ));
            Demo::status($family->get_name());
        };

        $pick = GtkDropDown::new_from_strings(
            array_map(static fn(PangoFontFamily $f): string => $f->get_name(), $families),
        );
        $pick->connect('notify::selected', function () use ($pick, $families, $show): void {
            $show($families[$pick->get_selected()]);
        });
        // Start on Sans, the alias family every map lists.
        foreach ($families as $i => $family) {
            if ($family->get_name() === 'Sans') {
                $pick->set_selected($i);
            }
        }
        $show($families[$pick->get_selected()]);

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($pick);
        $page->append($sample);
        $page->append($facts);
        return $page;
    },
);
