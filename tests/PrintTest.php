<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GtkPageOrientation;
use Gtk4\GtkPageSetup;
use Gtk4\GtkPaperSize;
use Gtk4\GtkPrintContext;
use Gtk4\GtkPrintDialog;
use Gtk4\GtkPrintOperation;
use Gtk4\GtkPrintOperationAction;
use Gtk4\GtkPrintOperationResult;
use Gtk4\GtkPrintPages;
use Gtk4\GtkPrintSettings;
use Gtk4\GtkPrintStatus;
use Gtk4\GtkUnit;

/**
 * The printing subsystem: settings, page setup and paper sizes as values, and GtkPrintOperation
 * driven to a PDF with ACTION_EXPORT - the one action that needs no dialog and no printer, so
 * the whole pipeline (begin-print, draw-page with its GtkPrintContext and cairo context,
 * end-print, done) runs headless. GtkPrintDialog is GTK 4.14's async dialog; only its state is
 * testable without a display server the user answers on.
 */
final class PrintTest extends GtkTestCase
{
    private string $file = '';

    protected function setUp(): void
    {
        parent::setUp();
        $this->file = sys_get_temp_dir() . '/php-gtk4-print-' . getmypid() . '-' . uniqid() . '.pdf';
    }

    protected function tearDown(): void
    {
        if (is_file($this->file)) {
            unlink($this->file);
        }
        parent::tearDown();
    }

    public function testPaperSizes(): void
    {
        $a4 = new GtkPaperSize('iso_a4');
        self::assertSame('iso_a4', $a4->get_name());
        self::assertSame('A4', $a4->get_display_name());
        self::assertEqualsWithDelta(210.0, $a4->get_width(GtkUnit::Mm), 0.01);
        self::assertEqualsWithDelta(297.0, $a4->get_height(GtkUnit::Mm), 0.01);
        self::assertFalse($a4->is_custom());
        self::assertTrue($a4->is_equal(new GtkPaperSize('iso_a4')));
        self::assertFalse($a4->is_equal(new GtkPaperSize('na_letter')));

        $custom = GtkPaperSize::new_custom('ticket', 'Ticket', 80.0, 200.0, GtkUnit::Mm);
        self::assertTrue($custom->is_custom());
        self::assertEqualsWithDelta(80.0, $custom->get_width(GtkUnit::Mm), 0.01);
        $custom->set_size(90.0, 200.0, GtkUnit::Mm);
        self::assertEqualsWithDelta(90.0, $custom->get_width(GtkUnit::Mm), 0.01);

        self::assertNotSame('', GtkPaperSize::get_default(), 'the locale has a default paper');
        self::assertSame(GtkPaperSize::get_default(), new GtkPaperSize(null)->get_name(), 'null means the default');
        $all = GtkPaperSize::get_paper_sizes(false);
        self::assertGreaterThan(10, count($all));
    }

    public function testPrintSettingsAreAValueBag(): void
    {
        $settings = new GtkPrintSettings();
        $settings->set_n_copies(3);
        self::assertSame(3, $settings->get_n_copies());
        $settings->set_orientation(GtkPageOrientation::Landscape);
        self::assertSame(GtkPageOrientation::Landscape, $settings->get_orientation());
        $settings->set_paper_size(new GtkPaperSize('iso_a5'));
        self::assertSame('iso_a5', $settings->get_paper_size()?->get_name());
        $settings->set('custom-key', 'custom value');
        self::assertSame('custom value', $settings->get('custom-key'));
        self::assertTrue($settings->has_key('custom-key'));
        self::assertNull($settings->get('no-such-key'));

        $settings->set_print_pages(GtkPrintPages::Ranges);
        $settings->set_page_ranges([[0, 1], [4, 4]]);
        self::assertSame([[0, 1], [4, 4]], $settings->get_page_ranges());
        self::assertSame(GtkPrintPages::Ranges, $settings->get_print_pages());
        try {
            $settings->set_page_ranges([[3, 1]]);
            self::fail('end before start');
        } catch (\ValueError $e) {
            self::assertStringContainsString('item 0 must run from a page number >= 0', $e->getMessage());
        }

        // round trip through a key file on disk
        $ini = sys_get_temp_dir() . '/php-gtk4-print-settings-' . getmypid() . '.ini';
        try {
            self::assertTrue($settings->to_file($ini));
            $again = GtkPrintSettings::new_from_file($ini);
            self::assertSame(3, $again->get_n_copies());
            self::assertSame('custom value', $again->get('custom-key'));
            self::assertSame([[0, 1], [4, 4]], $again->get_page_ranges());
        } finally {
            @unlink($ini);
        }
    }

    public function testPageSetup(): void
    {
        $setup = new GtkPageSetup();
        $setup->set_paper_size_and_default_margins(new GtkPaperSize('iso_a4'));
        $setup->set_orientation(GtkPageOrientation::Landscape);
        self::assertSame(GtkPageOrientation::Landscape, $setup->get_orientation());
        self::assertEqualsWithDelta(297.0, $setup->get_paper_width(GtkUnit::Mm), 0.01, 'landscape swaps them');
        $setup->set_left_margin(20.0, GtkUnit::Mm);
        self::assertEqualsWithDelta(20.0, $setup->get_left_margin(GtkUnit::Mm), 0.01);
        self::assertLessThan(
            $setup->get_paper_width(GtkUnit::Mm),
            $setup->get_page_width(GtkUnit::Mm),
            'the page is the paper minus the margins',
        );
        $copy = $setup->copy();
        self::assertNotSame($setup, $copy);
        self::assertSame(GtkPageOrientation::Landscape, $copy->get_orientation());
    }

    public function testExportingAPdfRunsTheWholePipeline(): void
    {
        $op = new GtkPrintOperation();
        $op->set_n_pages(2);
        $op->set_unit(GtkUnit::Points);
        $op->set_export_filename($this->file);
        $op->set_job_name('php-gtk4 test');
        $pages = [];
        $events = [];
        $op->connect('begin-print', function (
            GtkPrintOperation $o,
            GtkPrintContext $context,
        ) use (&$events): void {
            $events[] = 'begin';
            self::assertGreaterThan(0.0, $context->get_width());
            self::assertGreaterThan(0.0, $context->get_dpi_x());
        });
        $op->connect('draw-page', function (
            GtkPrintOperation $o,
            GtkPrintContext $context,
            int $page,
        ) use (&$pages): void {
            $pages[] = $page;
            $cr = $context->get_cairo_context();
            $cr->set_source_rgb(0.2, 0.4, 0.8);
            $cr->rectangle(10.0, 10.0, 100.0, 50.0 * ($page + 1));
            $cr->fill();
            self::assertSame($context->get_page_setup()->get_orientation(), GtkPageOrientation::Portrait);
        });
        $op->connect('end-print', function () use (&$events): void {
            $events[] = 'end';
        });
        $op->connect('done', function (GtkPrintOperation $o, GtkPrintOperationResult $result) use (&$events): void {
            $events[] = 'done:' . $result->name;
        });

        $result = $op->run(GtkPrintOperationAction::Export, null);
        self::assertSame(GtkPrintOperationResult::Apply, $result);
        self::assertSame([0, 1], $pages, 'both pages drawn, in order');
        self::assertSame(['begin', 'end', 'done:Apply'], $events);
        self::assertNotSame(GtkPrintStatus::Initial, $op->get_status(), 'the operation ran');
        self::assertNotSame('', $op->get_status_string());
        self::assertFileExists($this->file);
        self::assertStringStartsWith('%PDF', (string) file_get_contents($this->file, false, null, 0, 4));
        self::assertSame(2, $op->get_n_pages_to_print());
    }

    public function testAnExportWithoutAFilenameIsAnError(): void
    {
        $op = new GtkPrintOperation();
        $op->set_n_pages(1);
        // GTK would only complain (a CRITICAL) and answer Error; the state is refused before that.
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('ACTION_EXPORT needs set_export_filename() first');
        $op->run(GtkPrintOperationAction::Export, null);
    }

    public function testThePrintDialogHoldsItsState(): void
    {
        $dialog = new GtkPrintDialog();
        $dialog->set_title('Print the thing');
        self::assertSame('Print the thing', $dialog->get_title());
        $dialog->set_accept_label('_Export');
        self::assertSame('_Export', $dialog->get_accept_label());
        $settings = new GtkPrintSettings();
        $settings->set_n_copies(2);
        $dialog->set_print_settings($settings);
        self::assertSame(2, $dialog->get_print_settings()?->get_n_copies());
        $setup = new GtkPageSetup();
        $dialog->set_page_setup($setup);
        self::assertSame($setup, $dialog->get_page_setup());
        $dialog->set_modal(false);
        self::assertFalse($dialog->get_modal());
    }
}
