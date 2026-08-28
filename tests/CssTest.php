<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GdkDisplay;
use Gtk4\GError;
use Gtk4\Gtk;
use Gtk4\GtkButton;
use Gtk4\GtkCssProvider;
use Gtk4\GtkCssSection;
use Gtk4\GtkLabel;
use Gtk4\GtkStyleProvider;
use Gtk4\GtkStyleProviderPriority;

/**
 * The CSS stack: GtkCssProvider (the stylesheet), GtkStyleProvider (what a display
 * accepts), Gtk::add/remove_provider_for_display() (attaching it), GtkCssSection +
 * the parsing-error signal (what went wrong), GdkDisplay (what it is attached to).
 *
 * Whether a rule actually paints is GTK's business and needs a frame; what is
 * testable from here is that the stylesheet round-trips, that attaching and
 * detaching work on a real display, and that a broken stylesheet reports itself.
 */
final class CssTest extends GtkTestCase
{
    private const CSS = '.phpgtk-test { color: rgb(255,0,0); font-size: 21px; }';

    private function display(): GdkDisplay
    {
        $display = GdkDisplay::get_default();
        self::assertInstanceOf(GdkDisplay::class, $display, 'the test suite runs with a display');
        return $display;
    }

    public function testProviderIsAStyleProvider(): void
    {
        $provider = new GtkCssProvider();
        self::assertInstanceOf(GtkStyleProvider::class, $provider);
    }

    public function testLoadFromStringRoundTripsThroughToString(): void
    {
        $provider = new GtkCssProvider();
        self::assertSame('', trim($provider->to_string()), 'a fresh provider is empty');

        $provider->load_from_string(self::CSS);
        $css = $provider->to_string();
        self::assertStringContainsString('.phpgtk-test', $css);
        self::assertStringContainsString('color: rgb(255,0,0)', $css);
        self::assertStringContainsString('font-size: 21px', $css);
    }

    public function testLoadFromStringReplacesWhatWasLoadedBefore(): void
    {
        $provider = new GtkCssProvider();
        $provider->load_from_string(self::CSS);
        $provider->load_from_string('.other { color: rgb(0,0,255); }');
        self::assertStringNotContainsString('.phpgtk-test', $provider->to_string());
        self::assertStringContainsString('.other', $provider->to_string());
    }

    public function testLoadFromBytesTakesTheSameCss(): void
    {
        $provider = new GtkCssProvider();
        $provider->load_from_bytes(self::CSS);
        self::assertStringContainsString('.phpgtk-test', $provider->to_string());
    }

    public function testLoadFromPathReadsAFile(): void
    {
        $file = tempnam(sys_get_temp_dir(), 'phpgtk-css-');
        self::assertIsString($file);
        file_put_contents($file, self::CSS);
        try {
            $provider = new GtkCssProvider();
            $provider->load_from_path($file);
            self::assertStringContainsString('.phpgtk-test', $provider->to_string());
        } finally {
            unlink($file);
        }
    }

    /** A missing file is a parsing error like any other, not an exception. */
    public function testLoadFromPathOfAMissingFileReportsAParsingError(): void
    {
        $provider = new GtkCssProvider();
        $seen = 0;
        $provider->connect('parsing-error', function () use (&$seen): void {
            $seen++;
        });
        $provider->load_from_path(sys_get_temp_dir() . '/phpgtk-does-not-exist.css');
        self::assertGreaterThan(0, $seen);
        self::assertSame('', trim($provider->to_string()));
    }

    public function testLoadNamedLoadsAnInstalledTheme(): void
    {
        $provider = new GtkCssProvider();
        $provider->load_named('Adwaita', null);
        self::assertNotSame('', trim($provider->to_string()), 'Adwaita ships with GTK');
    }

    public function testParsingErrorHandsOverTheSectionAndTheError(): void
    {
        $provider = new GtkCssProvider();
        /** @var list<array{GtkCssSection, GError}> $reports */
        $reports = [];
        $provider->connect(
            'parsing-error',
            function (GtkCssProvider $self, GtkCssSection $section, GError $error) use (&$reports): void {
                $reports[] = [$section, $error];
            },
        );
        $provider->load_from_string(".ok { color: green; }\n.bad { color: nonsense-value; }");

        self::assertCount(1, $reports, 'one broken declaration, one report');
        [$section, $error] = $reports[0];
        self::assertInstanceOf(GtkCssSection::class, $section);
        self::assertInstanceOf(GError::class, $error);
        self::assertStringContainsString('nonsense-value', $error->getMessage());
        // Good rules survive a bad one: GTK's CSS parser is lenient by design.
        self::assertStringContainsString('.ok', $provider->to_string());
    }

    public function testCssSectionLocatesTheError(): void
    {
        $provider = new GtkCssProvider();
        $section = null;
        $provider->connect(
            'parsing-error',
            function (GtkCssProvider $self, GtkCssSection $s) use (&$section): void {
                $section ??= $s;
            },
        );
        $provider->load_from_string(".a { color: green; }\n.b { color: nonsense-value; }");

        self::assertInstanceOf(GtkCssSection::class, $section);
        // "<data>:2:13-27": file, 1-based start line:column, end column (end line if it differs).
        self::assertMatchesRegularExpression('/:2:\d+-(?:2:)?\d+$/', $section->to_string());

        $start = $section->get_start_location();
        $end = $section->get_end_location();
        self::assertSame(1, $start['lines'], 'GtkCssLocation counts lines from 0');
        self::assertSame(1, $end['lines']);
        self::assertGreaterThan($start['chars'], $end['chars']);
        self::assertGreaterThanOrEqual(0, $start['bytes']);
        self::assertGreaterThanOrEqual(0, $start['line_bytes']);
        self::assertGreaterThan(0, $start['line_chars']);
        // GTK 4.14 reports parse errors as flat sections - even inside an @import - so
        // get_parent() is null here. The accessor exists because the C API has it.
        self::assertNull($section->get_parent());
    }

    /** Sections come from the parser: a fundamental handle refuses `new` in its constructor slot. */
    public function testSectionsAreNotConstructibleFromPhp(): void
    {
        $this->expectException(\Error::class);
        $this->expectExceptionMessage('created by the extension, not with new');
        self::fail('unreachable: ' . new GtkCssSection()::class);
    }

    /** Displays come from GDK: a generated class says so with a private constructor. */
    public function testDisplaysAreNotConstructibleFromPhp(): void
    {
        $ctor = new \ReflectionClass(GdkDisplay::class)->getConstructor();
        self::assertNotNull($ctor);
        self::assertTrue($ctor->isPrivate(), 'GdkDisplay instances come from GDK, never from new');
    }

    public function testAddAndRemoveProviderForDisplay(): void
    {
        $display = $this->display();
        $provider = new GtkCssProvider();
        $provider->load_from_string(self::CSS);

        // GTK has no "which providers are attached" query, so what is asserted is that
        // both calls accept a real display and provider and leave the process standing.
        Gtk::add_provider_for_display($display, $provider, GtkStyleProviderPriority::APPLICATION);
        Gtk::add_provider_for_display($display, $provider);   // default priority = APPLICATION
        Gtk::remove_provider_for_display($display, $provider);
        Gtk::remove_provider_for_display($display, $provider);  // removing twice is not an error

        $label = new GtkLabel('styled');
        $label->add_css_class('phpgtk-test');
        self::assertTrue($label->has_css_class('phpgtk-test'));
    }

    public function testAddProviderRejectsANegativePriority(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('must be greater than or equal to 0');
        Gtk::add_provider_for_display($this->display(), new GtkCssProvider(), -1);
    }

    public function testAddProviderRejectsSomethingThatIsNotAProvider(): void
    {
        $this->expectException(\TypeError::class);
        $add = self::opaque(Gtk::add_provider_for_display(...));
        $add($this->display(), new GtkButton());
    }

    public function testEveryPriorityConstantIsTheGtkValue(): void
    {
        self::assertSame(1, GtkStyleProviderPriority::FALLBACK);
        self::assertSame(200, GtkStyleProviderPriority::THEME);
        self::assertSame(400, GtkStyleProviderPriority::SETTINGS);
        self::assertSame(600, GtkStyleProviderPriority::APPLICATION);
        self::assertSame(800, GtkStyleProviderPriority::USER);
    }

    public function testWidgetsAndWindowsReportTheDisplayTheyAreOn(): void
    {
        $display = $this->display();
        $window = $this->window();
        self::assertSame($display, $window->get_display(), 'handles are identities');

        $label = new GtkLabel('on a display');
        $window->set_child($label);
        self::assertSame($display, $label->get_display());
    }

    public function testDisplayFacts(): void
    {
        $display = $this->display();
        self::assertNotSame('', $display->get_name());
        self::assertFalse($display->is_closed());
        self::assertGreaterThan(0, $display->get_monitors()->get_n_items());
        // The values depend on the compositor behind Xvfb, so only the calls are the point.
        $display->is_composited();
        $display->is_rgba();
        $display->supports_input_shapes();
        $display->supports_shadow_width();
        $display->flush();
        $display->sync();
        $display->beep();
    }

    /**
     * The one GError path on GdkDisplay: under Xvfb with GDK_DEBUG=gl-disable it fails,
     * on a real GPU session it succeeds. Both are correct - what is asserted is that a
     * failure arrives as a Gtk4\GError and never as a false return.
     */
    public function testPrepareGlEitherSucceedsOrThrowsAGError(): void
    {
        $display = $this->display();
        try {
            self::assertTrue($display->prepare_gl());
        } catch (GError $e) {
            self::assertNotSame('', $e->getMessage());
        }
    }

    public function testOpeningAnUnknownDisplayReturnsNull(): void
    {
        self::assertNull(GdkDisplay::open('phpgtk-no-such-display:99'));
    }
}
