<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use PHPUnit\Framework\Assert;

/**
 * The optional features a build may lack, and which classes belong to them.
 *
 * `Gtk4\FEATURES` says what this module was configured with (`webkit=yes` after
 * `--enable-gtk4-webkit`). The classes of a feature only exist in a build that has it, while
 * the stub, the IDE stub, the example pages and the generated smoke tests are the same for
 * every build - so a test that compares "what the stub declares" with "what the extension
 * registers", or one that exercises a feature's class, asks here first. The namespaces are the
 * ones `CONDITIONAL_NAMESPACES` in gen/gir/config.php gates, named here by the class prefix the
 * GType names give them.
 */
final class Features
{
    /**
     * Class-name prefix -> the feature whose build the class needs.
     *
     * @var array<string, string>
     */
    public const array GATED = [
        'WebKit' => 'webkit',
        'JSC' => 'webkit',
    ];

    /** Whether this module was built with $feature (`webkit`). */
    public static function enabled(string $feature): bool
    {
        return str_contains(',' . \Gtk4\FEATURES . ',', ",$feature=yes,");
    }

    /** The feature a class needs, by its short (or qualified) name; null for one every build has. */
    public static function gate(string $class): ?string
    {
        $short = substr($class, (int) strrpos($class, '\\') + (str_contains($class, '\\') ? 1 : 0));
        foreach (self::GATED as $prefix => $feature) {
            if (str_starts_with($short, $prefix)) {
                return $feature;
            }
        }
        return null;
    }

    /** Whether a class named in the stub exists in this build. */
    public static function available(string $class): bool
    {
        $feature = self::gate($class);
        return $feature === null || self::enabled($feature);
    }

    /** Skip the calling test unless this build has $feature. */
    public static function requires(string $feature): void
    {
        if (!self::enabled($feature)) {
            Assert::markTestSkipped("built without $feature (configure --enable-gtk4-$feature)");
        }
    }
}
