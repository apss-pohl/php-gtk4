<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use ReflectionExtension;

final class ExtensionTest extends GtkTestCase
{
    public function testConstantsAreNamespaced(): void
    {
        self::assertSame('0.1.0', \Gtk4\PHPGTK_VERSION);
        self::assertStringContainsString('git', \Gtk4\PHPGTK_BUILD_INFO);
        self::assertMatchesRegularExpression('/^webkit=(yes|no)$/', \Gtk4\PHPGTK_FEATURES);
        self::assertFalse(defined('PHPGTK_VERSION'), 'no global (gtk3-style) constants');
    }

    public function testIniDirectivesExposeBuildInfo(): void
    {
        self::assertSame(\Gtk4\PHPGTK_BUILD_INFO, ini_get('gtk4.build_info'));
        self::assertSame(\Gtk4\PHPGTK_FEATURES, ini_get('gtk4.features'));
    }

    public function testEveryClassIsInTheGtk4Namespace(): void
    {
        foreach (new ReflectionExtension('gtk4')->getClasses() as $class) {
            if (str_starts_with($class->getName(), 'PhpCpp')) {
                continue;  // PHP-CPP internals
            }
            self::assertStringStartsWith('Gtk4\\', $class->getName());
        }
    }

    public function testClassHierarchyMirrorsGType(): void
    {
        $w = $this->window();
        self::assertInstanceOf(\Gtk4\GObject::class, $w);
        self::assertSame(\Gtk4\GObject::class, get_parent_class($w));
    }
}
