<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use FFI;

/**
 * Fake X11 input for the test display (the XTEST extension through libXtst, via ext-ffi).
 *
 * GTK 4 has no public constructors for GdkEvent and no way to inject events from PHP, so the
 * only way to exercise event controllers, gestures and the GdkEvent getters is real input on the
 * Xvfb display tests/run.sh provides. Keys are addressed by X keysym (the GDK_KEY_* values),
 * buttons by X button number, the pointer by root-window coordinates. Tests call
 * {@see available()} and skip when ext-ffi or libXtst is missing (the Windows suite, a build
 * without FFI).
 */
final class XInput
{
    private static ?FFI $x = null;
    private static ?FFI\CData $display = null;
    private static ?string $unavailable = null;

    /** Why input cannot be faked here, or null when it can. */
    public static function unavailable(): ?string
    {
        if (self::$unavailable !== null || self::$x !== null) {
            return self::$unavailable;
        }
        if (!extension_loaded('ffi')) {
            return self::$unavailable = 'ext-ffi is not loaded';
        }
        if (PHP_OS_FAMILY !== 'Linux' || (getenv('DISPLAY') ?: '') === '') {
            return self::$unavailable = 'no X11 display';
        }
        try {
            $x = FFI::cdef(<<<'C'
                typedef struct _XDisplay Display;
                typedef unsigned long KeySym;
                typedef unsigned char KeyCode;
                Display *XOpenDisplay(const char *name);
                int XCloseDisplay(Display *display);
                int XFlush(Display *display);
                int XSync(Display *display, int discard);
                KeyCode XKeysymToKeycode(Display *display, KeySym keysym);
                int XTestFakeKeyEvent(Display *display, unsigned int keycode, int is_press, unsigned long delay);
                int XTestFakeButtonEvent(Display *display, unsigned int button, int is_press, unsigned long delay);
                int XTestFakeMotionEvent(Display *display, int screen, int x, int y, unsigned long delay);
                int XTestQueryExtension(Display *display, int *event_base, int *error_base, int *major, int *minor);
                C, 'libXtst.so.6');
            // libXtst depends on libX11; the X* symbols resolve through it once libXtst is loaded.
            self::$x = $x;
            $display = self::call('XOpenDisplay', null);
        } catch (\Throwable $e) {
            return self::$unavailable = 'libXtst/libX11 unavailable: ' . $e->getMessage();
        }
        if (!$display instanceof FFI\CData) {
            self::$x = null;
            return self::$unavailable = 'XOpenDisplay failed';
        }
        $eb = $x->new('int');
        $er = $x->new('int');
        $ma = $x->new('int');
        $mi = $x->new('int');
        $ok = self::call(
            'XTestQueryExtension',
            $display,
            FFI::addr($eb),
            FFI::addr($er),
            FFI::addr($ma),
            FFI::addr($mi),
        );
        if ($ok === 0) {
            self::$x = null;
            return self::$unavailable = 'the X server has no XTEST extension';
        }
        self::$display = $display;
        return null;
    }

    public static function available(): bool
    {
        return self::unavailable() === null;
    }

    /** Press and release the key for an X keysym (a GDK_KEY_* value). */
    public static function key(int $keysym): void
    {
        $code = self::call('XKeysymToKeycode', self::display(), $keysym);
        if ($code === 0) {
            throw new \RuntimeException(sprintf('keysym 0x%x has no keycode on this display', $keysym));
        }
        self::call('XTestFakeKeyEvent', self::display(), $code, 1, 0);
        self::call('XTestFakeKeyEvent', self::display(), $code, 0, 0);
        self::call('XFlush', self::display());
    }

    /** Move the pointer to root-window coordinates. */
    public static function move(int $x, int $y): void
    {
        self::call('XTestFakeMotionEvent', self::display(), -1, $x, $y, 0);
        self::call('XFlush', self::display());
    }

    /** Press and release an X button (1 = primary, 2 = middle, 3 = secondary, 4/5 = wheel). */
    public static function click(int $button = 1): void
    {
        self::press($button);
        self::release($button);
    }

    public static function press(int $button = 1): void
    {
        self::call('XTestFakeButtonEvent', self::display(), $button, 1, 0);
        self::call('XFlush', self::display());
    }

    public static function release(int $button = 1): void
    {
        self::call('XTestFakeButtonEvent', self::display(), $button, 0, 0);
        self::call('XFlush', self::display());
    }

    /** Wait for the server to have processed everything sent so far. */
    public static function sync(): void
    {
        self::call('XSync', self::display(), 0);
    }

    /** One of the C functions declared above (FFI resolves them by name at call time). */
    private static function call(string $function, mixed ...$args): mixed
    {
        if (self::$x === null) {
            throw new \RuntimeException('XInput::available() is false: ' . (self::unavailable() ?? '?'));
        }
        return self::$x->$function(...$args);
    }

    private static function display(): FFI\CData
    {
        if (self::$display === null) {
            throw new \RuntimeException('XInput::available() is false: ' . (self::unavailable() ?? '?'));
        }
        return self::$display;
    }
}
