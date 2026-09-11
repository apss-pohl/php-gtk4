<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GdkDisplay;
use Gtk4\GdkMemoryFormat;
use Gtk4\GdkMemoryTexture;
use Gtk4\GdkSurface;
use Gtk4\GdkTexture;
use Gtk4\GdkToplevel;
use Gtk4\GdkToplevelLayout;
use Gtk4\GdkToplevelObject;
use Gtk4\GdkToplevelState;
use Gtk4\GLib;
use Gtk4\GtkWindow;

/**
 * GdkToplevel: the interface a window's surface implements through a backend-private class,
 * and the first user of INTERFACE_FALLBACK_BASE - wrap() refines a GdkSurface that is a
 * toplevel to GdkToplevelObject, which extends GdkSurface (core/object.cpp refine()).
 */
final class GdkToplevelTest extends GtkTestCase
{
    private ?GtkWindow $win = null;

    private function realized(): GdkToplevelObject
    {
        $win = $this->win = $this->window();
        $win->set_default_size(120, 80);
        $win->present();
        for ($i = 0; $i < 20; $i++) {
            GLib::main_context_iteration(false);
        }
        $surface = $win->get_surface();
        self::assertInstanceOf(GdkToplevelObject::class, $surface);
        return $surface;
    }

    public function testAWindowsSurfaceIsAToplevelAndStillASurface(): void
    {
        $surface = $this->realized();
        self::assertInstanceOf(GdkToplevel::class, $surface);
        self::assertInstanceOf(GdkSurface::class, $surface);
        self::assertGreaterThan(0, $surface->get_width(), 'GdkSurface methods are inherited');
        self::assertSame($surface, $this->win?->get_surface(), 'one handle per surface');
    }

    public function testStateIsTheFlagsIntBothWays(): void
    {
        $surface = $this->realized();
        self::assertSame($surface->get_state(), $surface->state, '@property-read state');
        // No window manager under Xvfb: nothing minimizes or focuses the window, so the flags
        // stay clear. The constant is what a caller masks with.
        self::assertSame(0, $surface->get_state() & GdkToplevelState::MINIMIZED);
        self::assertSame(1, GdkToplevelState::MINIMIZED);
    }

    public function testTheToplevelOnlyCallsAnswer(): void
    {
        if (getenv('GDK_BACKEND') !== 'x11') {
            // What a window-manager request answers is the backend's (Win32 said false to the
            // first of them). This pins X11, which tests/run.sh forces.
            self::markTestSkipped('the answers below are X11\'s (tests/run.sh forces GDK_BACKEND=x11)');
        }
        $surface = $this->realized();
        // Both are requests to the window manager; X11 sends them and reports "sent".
        self::assertTrue($surface->minimize());
        self::assertTrue($surface->lower());
        self::assertFalse($surface->supports_edge_constraints(), 'X11 without a WM that advertises them');
        // The title setter is one-way on X11: the backend forwards it to the window manager
        // and does not keep it, so the property reads back empty. decorated is kept.
        $surface->set_title('toplevel title');
        self::assertSame('', $surface->title);
        $surface->set_decorated(false);
        self::assertFalse($surface->decorated);
        $surface->restore_system_shortcuts();  // a no-op when nothing was inhibited
    }

    public function testStandaloneToplevelsAreRefinedToo(): void
    {
        $display = GdkDisplay::get_default();
        self::assertNotNull($display);
        $surface = GdkSurface::new_toplevel($display);
        self::assertInstanceOf(GdkToplevelObject::class, $surface);
        self::assertFalse($surface->is_destroyed());
        // destroy() consumes the creator's reference, which here was the handle's own: the
        // override keeps it, so the destroyed surface is still a valid object to ask - the
        // freed-under-the-handle version corrupted the heap for whatever came next
        // (GtkAlertDialogTest crashed in gdk_x11_monitor_get_workarea()).
        $surface->destroy();
        self::assertTrue($surface->is_destroyed());
        self::assertFalse($surface->get_mapped());
        unset($surface);
        // and a window after it presents fine
        $this->realized();
    }

    public function testPresentTakesALayout(): void
    {
        $surface = $this->realized();
        $layout = new GdkToplevelLayout();
        self::assertNull($layout->get_maximized(), 'unspecified until set');
        self::assertNull($layout->get_fullscreen());
        self::assertTrue($layout->get_resizable());
        $layout->set_maximized(true);
        $layout->set_fullscreen(false, null);
        $layout->set_resizable(false);
        self::assertTrue($layout->get_maximized());
        self::assertFalse($layout->get_fullscreen());
        self::assertNull($layout->get_fullscreen_monitor());
        self::assertFalse($layout->get_resizable());
        self::assertFalse($layout->equal(new GdkToplevelLayout()));
        self::assertTrue($layout->equal(clone $layout));
        $surface->present($layout);
        for ($i = 0; $i < 20; $i++) {
            GLib::main_context_iteration(false);
        }
        self::assertTrue($surface->get_mapped());
    }

    public function testTheIconListIsAListOfTextures(): void
    {
        $surface = $this->realized();
        $pixel = str_repeat(pack('C4', 0x35, 0x84, 0xe4, 0xff), 16 * 16);
        $icon = new GdkMemoryTexture(16, 16, GdkMemoryFormat::R8g8b8a8, $pixel, 16 * 4);
        $surface->set_icon_list([$icon, GdkTexture::new_from_bytes(PngFixture::red(2, 1))]);
        $surface->set_icon_list([]);  // unsets

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('must be a list of GdkTexture instances');
        $surface->set_icon_list([$icon, 'not a texture']);
    }
}
