<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GdkDisplay;
use Gtk4\GdkMonitor;
use Gtk4\GdkRectangle;

/**
 * GdkMonitor: the items of GdkDisplay::get_monitors() wrap to real monitors (the backend
 * subclass has no PHP class of its own), geometry is a GdkRectangle, and a GtkWindow can
 * go fullscreen on one.
 */
final class GdkMonitorTest extends GtkTestCase
{
    private function monitor(): GdkMonitor
    {
        $display = GdkDisplay::get_default();
        self::assertInstanceOf(GdkDisplay::class, $display);
        $monitor = $display->get_monitors()->get_item(0);
        self::assertInstanceOf(GdkMonitor::class, $monitor);
        return $monitor;
    }

    public function testMonitorsComeFromTheDisplayList(): void
    {
        $monitor = $this->monitor();
        self::assertTrue($monitor->is_valid());
        self::assertSame(GdkDisplay::get_default(), $monitor->get_display());
    }

    public function testGeometryIsARectangleWithArea(): void
    {
        $geometry = $this->monitor()->get_geometry();
        self::assertInstanceOf(GdkRectangle::class, $geometry);
        self::assertGreaterThan(0, $geometry->width);
        self::assertGreaterThan(0, $geometry->height);
    }

    public function testIdentityGettersAnswerOrAdmitTheyCannot(): void
    {
        $monitor = $this->monitor();
        // Under Xvfb most identity strings are null; a driver that has one never answers
        // with the empty string, which is what a lost NULL would look like.
        foreach (
            [$monitor->get_connector(), $monitor->get_model(), $monitor->get_manufacturer(),
                $monitor->get_description()] as $s
        ) {
            self::assertNotSame('', $s);
        }
        self::assertGreaterThanOrEqual(1, $monitor->get_scale_factor());
        self::assertGreaterThan(0.0, $monitor->get_scale());
        // Xvfb reports no physical size and no refresh rate: 0, never a negative.
        self::assertGreaterThanOrEqual(0, $monitor->get_refresh_rate());
        self::assertGreaterThanOrEqual(0, $monitor->get_width_mm());
        self::assertGreaterThanOrEqual(0, $monitor->get_height_mm());
    }

    public function testNewIsRefused(): void
    {
        $this->expectException(\Error::class);
        /** @phpstan-ignore new.privateConstructor (the refusal is the point) */
        new GdkMonitor();
    }

    public function testWindowGoesFullscreenOnAMonitor(): void
    {
        $win = $this->window();
        self::assertFalse($win->is_fullscreen());
        $win->fullscreen_on_monitor($this->monitor());
        // The request is recorded on the window at once; leaving fullscreen again waits for
        // the surface state a window manager sends, and Xvfb has none.
        self::assertTrue($win->is_fullscreen());
        $win->present();
        $win->unfullscreen();
    }
}
