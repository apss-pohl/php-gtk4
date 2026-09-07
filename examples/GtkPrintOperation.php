<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\CairoContext;
use Gtk4\GdkRGBA;
use Gtk4\GtkButton;
use Gtk4\GtkDrawingArea;
use Gtk4\GtkPrintContext;
use Gtk4\GtkPrintOperation;
use Gtk4\GtkPrintOperationAction;
use Gtk4\GtkUnit;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkPrintOperation - printing: GTK asks for every page and you draw it with cairo.
 *
 * A print operation is a small state machine: begin-print (how many pages?), draw-page once per
 * page with a GtkPrintContext whose get_cairo_context() is the page, end-print, done. The same
 * drawing routine paints the preview on this page and the pages of the PDF - ACTION_EXPORT
 * writes a file without any dialog, which is what the button does (into the temp directory);
 * ACTION_PRINT_DIALOG would show the system's print dialog instead.
 *
 *   bin/php-gtk4 examples/demo.php GtkPrintOperation
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkPrintOperation',
    'printing: GTK asks for every page and you draw it with cairo',
    function (GtkWindow $win): GtkWidget {
        $pages = 3;
        // One routine for the preview and the PDF: a title, a page number and a bar chart.
        $paint = static function (CairoContext $cr, float $width, float $height, int $page): void {
            $cr->set_source_color(new GdkRGBA(Demo::INK));
            $cr->set_font_size($height / 14);
            $cr->move_to($width / 12, $height / 8);
            $cr->show_text(sprintf('php-gtk4 report, page %d of 3', $page + 1));
            $bars = [0.6, 0.9, 0.4, 0.75, 0.55];
            $slot = $width * 10 / 12 / count($bars);
            foreach ($bars as $i => $h) {
                $h = min(1.0, $h * (1 + $page / 4));
                $cr->set_source_color(new GdkRGBA($i % 2 === 0 ? Demo::ACCENT : Demo::GOOD));
                $bar = $height * 0.55 * $h;
                $cr->rectangle($width / 12 + $i * $slot + $slot / 6, $height * 0.8 - $bar, $slot * 2 / 3, $bar);
                $cr->fill();
            }
        };

        $preview = Demo::canvas(300, 220, static function (
            GtkDrawingArea $area,
            CairoContext $cr,
            int $width,
            int $height,
        ) use ($paint): void {
            Demo::sheet($cr);
            $paint($cr, $width, $height, 0);
        });

        $export = GtkButton::new_with_label('export a 3-page PDF (ACTION_EXPORT)');
        $export->connect('clicked', static function () use ($pages, $paint): void {
            $file = sys_get_temp_dir() . '/php-gtk4-report.pdf';
            $op = new GtkPrintOperation();
            $op->set_n_pages($pages);
            $op->set_unit(GtkUnit::Points);
            $op->set_export_filename($file);
            $op->set_job_name('php-gtk4 report');
            $op->connect('draw-page', static function (
                GtkPrintOperation $o,
                GtkPrintContext $ctx,
                int $page,
            ) use ($paint): void {
                $paint($ctx->get_cairo_context(), $ctx->get_width(), $ctx->get_height(), $page);
            });
            $result = $op->run(GtkPrintOperationAction::Export, null);
            Demo::status(sprintf(
                '%s → %s (%s, %d bytes)',
                $result->name,
                $file,
                $op->get_status()->name,
                (int) @filesize($file),
            ));
        });

        $page = new \Gtk4\GtkBox(\Gtk4\GtkOrientation::Vertical, 12);
        $page->append($preview);
        $page->append($export);
        Demo::status('the preview is page 1; the button writes all three pages');
        return $page;
    },
    360,
    320,
);
