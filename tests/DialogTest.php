<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GdkRGBA;
use Gtk4\GListStore;
use Gtk4\GtkAboutDialog;
use Gtk4\GtkColorDialog;
use Gtk4\GtkFileDialog;
use Gtk4\GtkFileFilter;
use Gtk4\GtkFilterMatch;
use Gtk4\GtkFontDialog;
use Gtk4\GtkLicense;
use Gtk4\GtkPicture;
use Gtk4\PangoFontDescription;
use Gtk4\PangoStyle;
use Gtk4\PangoWeight;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Wave 5 (gen/README.md): GTK 4.10's async dialogs. What a headless suite can hold them to
 * is everything up to the point a human would click - the configuration, the values that go
 * in and come back out, and the error paths - plus the two mappings the wave introduced:
 * a GFile is a path string (docs/DESIGN.md) and a font is a PangoFontDescription.
 *
 * The `choose`/`open` round trip itself needs a real dialog and belongs to the example pages.
 */
final class DialogTest extends GtkTestCase
{
    // ---------------------------------------------------------------- GtkFileDialog

    public function testFileDialogConfiguration(): void
    {
        $dialog = new GtkFileDialog();
        $dialog->set_title('Pick one');
        $dialog->set_accept_label('Take it');
        $dialog->set_modal(true);

        self::assertSame('Pick one', $dialog->get_title());
        self::assertSame('Take it', $dialog->get_accept_label());
        self::assertTrue($dialog->get_modal());
    }

    /** A GFile parameter is a path, and what comes back is the absolute one. */
    public function testFileDialogTakesPathsAndNormalisesThem(): void
    {
        $dialog = new GtkFileDialog();
        $dialog->set_initial_folder(sys_get_temp_dir());
        self::assertSame(sys_get_temp_dir(), $dialog->get_initial_folder());

        $dialog->set_initial_folder('.');
        self::assertSame(getcwd(), $dialog->get_initial_folder(), 'a relative path is resolved');
    }

    /**
     * GTK stores an initial *file* as folder + name and leaves the initial-file property NULL,
     * so the getter answers null however the setter was called - GTK's behaviour, not ours.
     */
    public function testFileDialogSplitsAnInitialFile(): void
    {
        // Split with a real directory and the platform's own separator: GLib uses
        // g_path_get_dirname(), which answers `\etc` for `/etc/hostname` on Windows, so a
        // hard-coded POSIX path fails there for a reason that has nothing to do with the binding.
        $folder = sys_get_temp_dir();
        $dialog = new GtkFileDialog();
        $dialog->set_initial_file($folder . DIRECTORY_SEPARATOR . 'hostname');

        self::assertNull($dialog->get_initial_file());
        self::assertSame($folder, $dialog->get_initial_folder());
        self::assertSame('hostname', $dialog->get_initial_name());
    }

    public function testFileDialogFiltersAreAListModelOfFilters(): void
    {
        $images = new GtkFileFilter();
        $images->set_name('Images');
        $images->add_suffix('png');

        $filters = new GListStore('GtkFileFilter');
        $filters->append($images);

        $dialog = new GtkFileDialog();
        $dialog->set_filters($filters);
        $dialog->set_default_filter($images);

        self::assertSame($images, $dialog->get_default_filter(), 'handles are identities');
        self::assertSame(1, $dialog->get_filters()?->get_n_items());
    }

    /** *_finish() without a real answer is an argument error, never a crash. */
    public function testFinishRejectsSomethingThatIsNotAnAsyncResult(): void
    {
        $dialog = new GtkFileDialog();
        $this->expectException(\TypeError::class);
        $finish = self::opaque($dialog->open_finish(...));
        $finish(new GtkFileFilter());
    }

    // ---------------------------------------------------------------- GtkFileFilter

    public function testFileFilterCollectsItsAttributes(): void
    {
        $filter = new GtkFileFilter();
        $filter->set_name('Images');
        $filter->add_suffix('png');
        $filter->add_mime_type('image/jpeg');

        self::assertSame('Images', $filter->get_name());
        self::assertContains('standard::content-type', $filter->get_attributes());
        // It is a GtkFilter: the list machinery can use it directly.
        self::assertSame(GtkFilterMatch::Some, $filter->get_strictness());
    }

    public function testFileFilterSurvivesAGVariantRoundTrip(): void
    {
        $filter = new GtkFileFilter();
        $filter->set_name('Markdown');
        $filter->add_suffix('md');

        $again = GtkFileFilter::new_from_gvariant($filter->to_gvariant());
        self::assertSame('Markdown', $again->get_name());
    }

    // ---------------------------------------------------------------- GtkColorDialog

    public function testColorDialogConfiguration(): void
    {
        $dialog = new GtkColorDialog();
        $dialog->set_title('Pick a colour');
        $dialog->set_with_alpha(false);

        self::assertSame('Pick a colour', $dialog->get_title());
        self::assertFalse($dialog->get_with_alpha());
    }

    /**
     * choose_rgba() is not called here on purpose: it opens a real chooser on the shared
     * display, and a dialog holding focus swallows the synthetic input EventControllerTest
     * sends (eight of its tests failed the first time this suite started one). The async round
     * trip belongs to examples/GtkColorDialog.php, where a human is looking at it.
     */
    public function testColorDialogTakesItsInitialColourByValue(): void
    {
        $colour = new GdkRGBA('#3584e4');
        self::assertSame('rgb(53,132,228)', $colour->to_string());
        self::assertTrue($colour->equal(new GdkRGBA('#3584e4')), 'a boxed value compares by value');
    }

    // ---------------------------------------------------------------- GtkFontDialog

    public function testFontDialogConfiguration(): void
    {
        $dialog = new GtkFontDialog();
        $dialog->set_title('Pick a font');
        self::assertSame('Pick a font', $dialog->get_title());
        self::assertNull($dialog->get_filter());
    }

    public function testFontDescriptionIsAValue(): void
    {
        $font = PangoFontDescription::from_string('Cantarell Bold Italic 12');

        self::assertSame('Cantarell', $font->get_family());
        self::assertSame(PangoWeight::Bold, $font->get_weight());
        self::assertSame(PangoStyle::Italic, $font->get_style());
        self::assertSame(12 * 1024, $font->get_size(), 'points * PANGO_SCALE');
        self::assertStringContainsString('Cantarell', $font->to_string());

        $copy = clone $font;
        self::assertNotSame($font, $copy, 'a boxed handle clones the value');
        self::assertTrue($font->equal($copy));

        $copy->set_family('monospace');
        self::assertFalse($font->equal($copy), 'the copy is independent');
        self::assertSame('Cantarell', $font->get_family());
    }

    // ---------------------------------------------------------------- GtkAboutDialog

    public function testAboutDialogHoldsWhatItIsGiven(): void
    {
        $about = new GtkAboutDialog();
        $about->set_program_name('php-gtk4');
        $about->set_version('0.1.0');
        $about->set_license_type(GtkLicense::MitX11);
        $about->set_authors(['Sven Pohl', 'Contributors']);
        $about->set_website('https://example.invalid/');

        self::assertSame('php-gtk4', $about->get_program_name());
        self::assertSame('0.1.0', $about->get_version());
        self::assertSame(GtkLicense::MitX11, $about->get_license_type());
        self::assertSame(['Sven Pohl', 'Contributors'], $about->get_authors());
        self::assertSame('https://example.invalid/', $about->get_website());

        $about->destroy();
    }

    /** GTK_LICENSE_0BSD cannot be a PHP case name as it is spelled; it reads like its sibling. */
    public function testLicenseCasesAreValidIdentifiers(): void
    {
        self::assertSame(18, GtkLicense::Bsd0->value);
        self::assertSame(15, GtkLicense::Bsd3->value);
    }

    // ---------------------------------------------------------------- the GFile mapping

    /** The same mapping outside the dialogs: GtkPicture takes and answers with a path. */
    public function testGFileParametersAreStringsEverywhere(): void
    {
        $file = tempnam(sys_get_temp_dir(), 'phpgtk-picture-');
        self::assertIsString($file);
        file_put_contents($file, PngFixture::red(2, 2));
        try {
            $picture = new GtkPicture();
            $picture->set_filename($file);
            self::assertSame($file, $picture->get_file());
        } finally {
            unlink($file);
        }
    }

    // ---------------------------------------------------------------- the async choosers

    /**
     * The argument guards of the nine async choosers, which RobustnessTest cannot sweep: all of
     * their parameters are nullable, so the generic sweep's "valid value in every other position"
     * builds an all-null call - a legal one, which opens a chooser nothing will close.
     *
     * This puts a `stdClass` in one position at a time and leaves the rest null. That can never
     * be a legal call, so nothing opens, and the parameter under test is the one that has to
     * refuse: a wrong type must be a TypeError from argument parsing, raised before GTK is
     * reached. The list is RobustnessTest's own, so a chooser added to the exclusion is covered
     * here without a second edit.
     */
    #[DataProvider('asyncChoosers')]
    public function testTheAsyncChoosersRefuseAWrongTypedArgument(string $class, string $method): void
    {
        $rm = new \ReflectionMethod($class, $method);
        $dialog = new $class();

        foreach (array_keys($rm->getParameters()) as $position) {
            $args = array_fill(0, $rm->getNumberOfParameters(), null);
            $args[$position] = new \stdClass();
            try {
                $rm->invokeArgs($dialog, $args);
                self::fail("$class::$method() accepted a stdClass in position " . ($position + 1));
            } catch (\TypeError $e) {
                self::assertStringContainsString(
                    'Argument #' . ($position + 1),
                    $e->getMessage(),
                    "$class::$method() must name the argument it refused",
                );
            }
        }
    }

    /** @return iterable<string, array{class-string, string}> */
    public static function asyncChoosers(): iterable
    {
        foreach (RobustnessTest::ASYNC_CHOOSERS as $key) {
            [$class, $method] = explode('::', $key, 2);
            /** @var class-string $class */
            yield $key => [$class, $method];
        }
    }
}
