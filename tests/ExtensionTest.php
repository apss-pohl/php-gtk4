<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\Gtk;
use ReflectionExtension;

final class ExtensionTest extends GtkTestCase
{
    public function testConstantsAreNamespaced(): void
    {
        self::assertSame(phpversion('gtk4'), \Gtk4\VERSION, 'stub constant and module version must agree');
        // ./VERSION is the single source of truth for the release version
        // (docs/RELEASING.md); everything else mirrors it, including this binary.
        $file = trim((string) file_get_contents(__DIR__ . '/../VERSION'));
        self::assertSame($file, \Gtk4\VERSION, './VERSION and the built module must agree');
        self::assertMatchesRegularExpression('/^\d+\.\d+\.\d+(-dev)?$/', \Gtk4\VERSION);
        $build = \Gtk4\BUILD_INFO;
        $features = \Gtk4\FEATURES;
        self::assertStringContainsString('git', $build);
        self::assertMatchesRegularExpression('/^webkit=(yes|no)$/', $features);
        self::assertFalse(defined('PHPGTK_VERSION'), 'no global (gtk3-style) constants');
    }

    public function testIniDirectivesExposeBuildInfo(): void
    {
        self::assertSame(\Gtk4\BUILD_INFO, ini_get('gtk4.build_info'));
        self::assertSame(\Gtk4\FEATURES, ini_get('gtk4.features'));
    }

    /**
     * The version of the GTK *library in use*, which is not the one the extension was compiled
     * against: a distribution can ship either, and 4.22 enforces preconditions 4.14 did not.
     */
    public function testTheRuntimeGtkVersionIsReadable(): void
    {
        self::assertSame(4, Gtk::get_major_version(), 'php-gtk4 binds GTK 4');
        // config.m4 refuses anything below 4.14, so the running library cannot be older either.
        self::assertGreaterThanOrEqual(14, Gtk::get_minor_version());
        self::assertGreaterThanOrEqual(0, Gtk::get_micro_version());
    }

    /** gtk_check_version() answers null for "new enough", a sentence for anything else. */
    public function testCheckVersionAnswersNullOnlyForACompatibleGtk(): void
    {
        $minor = Gtk::get_minor_version();
        self::assertNull(Gtk::check_version(4, 14, 0), 'the supported floor is always satisfied');
        self::assertNull(Gtk::check_version(4, $minor, Gtk::get_micro_version()));
        self::assertIsString(Gtk::check_version(4, $minor + 1, 0), 'a later minor is not satisfied');
        self::assertIsString(Gtk::check_version(5, 0, 0), 'another major is never compatible');
    }

    /** The components cross as an unsigned int, so a negative one is refused, not wrapped. */
    public function testCheckVersionRefusesANegativeComponent(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('Argument #1 ($required_major)');
        Gtk::check_version(-1, 0, 0);
    }

    public function testEveryClassIsInTheGtk4Namespace(): void
    {
        foreach (new ReflectionExtension('gtk4')->getClasses() as $class) {
            self::assertStringStartsWith('Gtk4\\', $class->getName());
        }
    }

    public function testClassHierarchyMirrorsGType(): void
    {
        $w = $this->window();
        self::assertInstanceOf(\Gtk4\GObject::class, $w);
        self::assertSame(\Gtk4\GtkWidget::class, get_parent_class($w));
        self::assertSame(\Gtk4\GObject::class, get_parent_class(\Gtk4\GtkWidget::class));
    }
}
