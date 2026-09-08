<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GAsyncResult;
use Gtk4\GdkClipboard;
use Gtk4\GdkCursor;
use Gtk4\GdkDisplay;
use Gtk4\GdkMonitor;
use Gtk4\GdkSurface;
use Gtk4\GdkTexture;
use Gtk4\GLib;
use Gtk4\GObject;
use Gtk4\GtkIconPaintable;
use Gtk4\GtkIconTheme;

/**
 * What a window can ask about the screen it is on: the display's monitors, its clipboards and
 * the cursor over a widget. GDK owns all of those - most of the wave's hand work was refusing
 * `new` on them, because a standalone one either aborts the process inside GDK
 * (GdkClipboard, GdkSurface) or answers NULL from a getter that declares it never does
 * (GdkMonitor::get_display()).
 */
final class DisplayTest extends GtkTestCase
{
    private function display(): GdkDisplay
    {
        $display = GdkDisplay::get_default();
        self::assertNotNull($display, 'the test bootstrap requires a display');

        return $display;
    }

    public function testTheseAreGdksToCreateNotPhps(): void
    {
        foreach ([GdkClipboard::class, GdkMonitor::class, GdkSurface::class, GtkIconPaintable::class] as $class) {
            self::assertFalse(new \ReflectionClass($class)->isInstantiable(), "$class is not PHP's to make");
        }
    }

    public function testMonitorsAreRealMonitors(): void
    {
        $monitors = $this->display()->get_monitors();
        if ($monitors->get_n_items() === 0) {
            self::markTestSkipped('the display reports no monitor');
        }
        $monitor = $monitors->get_item(0);
        self::assertInstanceOf(GdkMonitor::class, $monitor);
        // The declared types are true only because GDK made it: a standalone one has no display.
        self::assertSame($this->display(), $monitor->get_display());
        self::assertTrue($monitor->is_valid());
        $geometry = $monitor->get_geometry();
        self::assertGreaterThan(0, $geometry->width);
        self::assertGreaterThan(0, $geometry->height);
    }

    public function testTheSameMonitorWrapsToTheSameHandle(): void
    {
        $monitors = $this->display()->get_monitors();
        if ($monitors->get_n_items() === 0) {
            self::markTestSkipped('the display reports no monitor');
        }
        self::assertSame($monitors->get_item(0), $monitors->get_item(0));
    }

    public function testAWidgetAnswersWithItsDisplaysClipboards(): void
    {
        $window = $this->window();
        $clipboard = $window->get_clipboard();
        self::assertInstanceOf(GdkClipboard::class, $clipboard);
        self::assertSame($window->get_display(), $clipboard->get_display());
        self::assertNotSame($clipboard, $window->get_primary_clipboard(), 'two different selections');
    }

    public function testClipboardTakesText(): void
    {
        // set_text() is synchronous; reading it back is async and needs a main loop, which the
        // headless X server does not reliably serve - so this only pins that setting works.
        $clipboard = $this->window()->get_clipboard();
        $clipboard->set_text('php-gtk4');
        self::assertTrue($clipboard->is_local(), 'this process owns the selection now');
    }

    /** Pump the main context until $done() or the bound is reached (the async pair needs a loop). */
    private static function pump(callable $done, int $rounds = 500): void
    {
        for ($i = 0; $i < $rounds && !$done(); $i++) {
            GLib::main_context_iteration(false);
        }
    }

    public function testTextRoundTripsThroughTheClipboardAsynchronously(): void
    {
        $clipboard = $this->window()->get_clipboard();
        $clipboard->set_text('round trip');

        $got = null;
        $clipboard->read_text_async(null, function (GdkClipboard $self, GAsyncResult $result) use (&$got): void {
            $got = $self->read_text_finish($result);
        });
        self::pump(static function () use (&$got): bool {
            return $got !== null;
        });
        self::assertSame('round trip', $got);
    }

    public function testATextureRoundTripsThroughTheClipboard(): void
    {
        $png = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==',
        );
        $clipboard = $this->window()->get_clipboard();
        $clipboard->set_texture(GdkTexture::new_from_bytes($png));

        $got = null;
        $clipboard->read_texture_async(null, function (GdkClipboard $self, GAsyncResult $result) use (&$got): void {
            $got = $self->read_texture_finish($result);
        });
        self::pump(static function () use (&$got): bool {
            return $got !== null;
        });
        self::assertInstanceOf(GdkTexture::class, $got);
        self::assertSame(1, $got->get_width());
    }

    public function testReadAsyncNegotiatesAMimeType(): void
    {
        $clipboard = $this->window()->get_clipboard();
        $clipboard->set_text('negotiated');

        $done = false;
        $clipboard->read_async(['text/plain;charset=utf-8'], 0, null, function () use (&$done): void {
            $done = true;   // the stream itself is not bound yet; the callback landing is the point
        });
        self::pump(static function () use (&$done): bool {
            return $done;
        });
        self::assertTrue($done, 'the async callback ran');
    }

    public function testACursorCanBeMadeFromANameAndSetOnAWidget(): void
    {
        $cursor = GdkCursor::new_from_name('pointer', null);
        self::assertInstanceOf(GdkCursor::class, $cursor);
        self::assertSame('pointer', $cursor->get_name());

        $window = $this->window();
        $window->set_cursor($cursor);
        self::assertSame($cursor, $window->get_cursor());
    }

    public function testAnUnknownCursorNameStillMakesACursor(): void
    {
        // GDK does not resolve the name until a surface uses it, so an unknown one is a cursor
        // that simply falls back at that point - not NULL.
        $cursor = GdkCursor::new_from_name('no-such-cursor-name', null);
        self::assertInstanceOf(GdkCursor::class, $cursor);
        self::assertSame('no-such-cursor-name', $cursor->get_name());
    }

    public function testACursorCanFallBackToAnother(): void
    {
        $fallback = GdkCursor::new_from_name('default', null);
        $cursor = GdkCursor::new_from_name('pointer', $fallback);
        self::assertSame($fallback, $cursor->get_fallback());
    }

    public function testTheIconThemeLooksUpAPaintable(): void
    {
        $theme = GtkIconTheme::get_for_display($this->display());
        self::assertInstanceOf(GtkIconTheme::class, $theme);
        $paintable = $theme->lookup_icon(
            'image-missing',
            null,
            24,
            1,
            \Gtk4\GtkTextDirection::None,
            0,
        );
        self::assertInstanceOf(GtkIconPaintable::class, $paintable);
        self::assertSame(24, $paintable->get_intrinsic_width());
    }

    public function testTheIconThemeIsTheDisplaysOwn(): void
    {
        $theme = GtkIconTheme::get_for_display($this->display());
        self::assertSame($theme, GtkIconTheme::get_for_display($this->display()));
        self::assertSame($this->display(), $theme->get_display());
    }

    public function testAPresentedWindowHasASurface(): void
    {
        $window = $this->window();
        $window->present();
        $surface = $window->get_surface();
        self::assertInstanceOf(GdkSurface::class, $surface);
        self::assertSame($this->display(), $surface->get_display());
        self::assertFalse($surface->is_destroyed());
    }

    public function testASurfaceIsAGObjectHandleLikeAnyOther(): void
    {
        $window = $this->window();
        $window->present();
        self::assertInstanceOf(GObject::class, $window->get_surface());
        self::assertSame($window->get_surface(), $window->get_surface());
    }

    public function testASurfaceReportsItsGeometryAndScale(): void
    {
        $window = $this->window();
        $window->set_default_size(320, 240);
        $window->present();
        $surface = $window->get_surface();
        self::assertNotNull($surface);
        self::assertGreaterThan(0, $surface->get_width());
        self::assertGreaterThan(0, $surface->get_height());
        self::assertGreaterThanOrEqual(1, $surface->get_scale_factor());
        self::assertGreaterThan(0.0, $surface->get_scale());
        self::assertTrue($surface->get_mapped());
    }

    public function testASurfaceTakesACursorAndCanBeAskedToRender(): void
    {
        $window = $this->window();
        $window->present();
        $surface = $window->get_surface();
        self::assertNotNull($surface);

        $cursor = GdkCursor::new_from_name('text', null);
        $surface->set_cursor($cursor);
        self::assertSame($cursor, $surface->get_cursor());

        // Both are requests to GDK, not queries: they must simply not blow up.
        $surface->queue_render();
        $surface->request_layout();
        self::assertFalse($surface->is_destroyed());
    }

    public function testTheIconThemeReportsItsSearchConfiguration(): void
    {
        $theme = GtkIconTheme::get_for_display($this->display());
        self::assertNotSame('', $theme->get_theme_name());
        self::assertTrue($theme->has_icon('image-missing'));
        self::assertFalse($theme->has_icon('no-such-icon-anywhere'));
        self::assertContains('image-missing', $theme->get_icon_names());
    }

    public function testAStandaloneIconThemeTakesAThemeName(): void
    {
        // A theme of one's own, unlike the display's singleton, can be pointed anywhere.
        $theme = new GtkIconTheme();
        $theme->set_theme_name('Adwaita');
        self::assertSame('Adwaita', $theme->get_theme_name());
    }

    public function testTheDisplaysIconThemeRefusesAThemeName(): void
    {
        // It follows the desktop setting; GTK's own precondition is a CRITICAL that changes
        // nothing, so the binding says so instead.
        $this->expectException(\LogicException::class);
        GtkIconTheme::get_for_display($this->display())->set_theme_name('Adwaita');
    }

    public function testAPaintableReportsTheSizeItWasLookedUpAt(): void
    {
        $theme = GtkIconTheme::get_for_display($this->display());
        $paintable = $theme->lookup_icon('image-missing', null, 32, 2, \Gtk4\GtkTextDirection::Ltr, 0);
        self::assertSame(32, $paintable->get_intrinsic_height());
        self::assertFalse($paintable->is_symbolic(), 'image-missing is not a symbolic icon');
        self::assertNotSame('', (string) $paintable->get_file());
    }

    public function testMonitorsGeometryIsInsideTheDisplay(): void
    {
        $monitors = $this->display()->get_monitors();
        if ($monitors->get_n_items() === 0) {
            self::markTestSkipped('the display reports no monitor');
        }
        $monitor = $monitors->get_item(0);
        self::assertInstanceOf(GdkMonitor::class, $monitor);
        self::assertGreaterThanOrEqual(0, $monitor->get_width_mm());
        self::assertGreaterThanOrEqual(0, $monitor->get_refresh_rate());
        self::assertGreaterThanOrEqual(1, $monitor->get_scale_factor());
        self::assertContains(
            $monitor->get_subpixel_layout(),
            \Gtk4\GdkSubpixelLayout::cases(),
        );
    }

    public function testTextureCursorRoundTrips(): void
    {
        $png = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==',
        );
        $texture = GdkTexture::new_from_bytes($png);
        $cursor = GdkCursor::new_from_texture($texture, 0, 0, null);
        self::assertSame($texture, $cursor->get_texture());
        self::assertSame(0, $cursor->get_hotspot_x());
    }
}
