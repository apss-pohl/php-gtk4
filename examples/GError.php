<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\CairoContext;
use Gtk4\GError;
use Gtk4\GdkTexture;
use Gtk4\GtkDrawingArea;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GError - GLib's GError surfaced as a PHP exception.
 *
 * Every method whose C counterpart takes a `GError **` throws this instead of
 * returning false or null. It extends RuntimeException, so ordinary catch blocks
 * work; getDomain() is the GLib error quark and getCode() the code within it.
 *
 *   bin/php-gtk4 examples/GError.php
 */

require __DIR__ . '/bootstrap.php';

Demo::run('GError', function (GtkWindow $win): GtkWidget {
    /** @var list<array{string, string}> $results */
    $results = [];

    $attempt = static function (string $what, callable $call) use (&$results): void {
        try {
            $call();
            $results[] = [$what, 'no error'];
        } catch (GError $e) {
            $results[] = [$what, sprintf(
                "%s\ndomain %s · code %d\n%s",
                $e::class,
                $e->getDomain(),
                $e->getCode(),
                $e->getMessage(),
            )];
        }
    };

    $attempt(
        "GdkTexture::new_from_filename('/does/not/exist.png')",
        static fn() => GdkTexture::new_from_filename('/does/not/exist.png'),
    );
    $attempt(
        "GdkTexture::new_from_bytes('this is not an image')",
        static fn() => GdkTexture::new_from_bytes('this is not an image'),
    );

    // It is a RuntimeException, so catching the parent works too.
    $asRuntime = 'not thrown';
    try {
        GdkTexture::new_from_filename('/does/not/exist.png');
    } catch (\RuntimeException $e) {
        $asRuntime = $e::class . ' caught as \RuntimeException';
    }

    return Demo::canvas(520, 280, static function (
        GtkDrawingArea $area,
        CairoContext $cr,
        int $width,
        int $height,
    ) use ($results, $asRuntime): void {
        Demo::sheet($cr);
        $y = 34.0;
        foreach ($results as [$what, $detail]) {
            Demo::text($cr, 18, $y, $what, Demo::INK, 12);
            $y += 18;
            foreach (explode("\n", $detail) as $line) {
                Demo::text($cr, 30, $y, $line, Demo::WARN, 11);
                $y += 15;
            }
            $y += 12;
        }
        Demo::text($cr, 18, $y, $asRuntime, Demo::MUTED, 12);
    });
}, 540, 320);
