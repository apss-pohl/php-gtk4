<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use ReflectionExtension;

final class ExtensionTest extends GtkTestCase
{
    public function testConstantsAreNamespaced(): void
    {
        self::assertSame(phpversion('gtk4'), \Gtk4\VERSION, 'stub constant and module version must agree');
        self::assertMatchesRegularExpression('/^\d+\.\d+\.\d+$/', \Gtk4\VERSION);
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
