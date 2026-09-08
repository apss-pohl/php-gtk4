<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GdkDevice;
use Gtk4\GdkDisplay;
use Gtk4\GdkInputSource;
use Gtk4\GdkSeat;

/**
 * The input devices behind a display: a seat is the set of them a user sits at, and a device is
 * one pointer or keyboard in it.
 *
 * Both are abstract in GDK and belong to the backend, so PHP never builds one - it asks the
 * display. Under Xvfb there is exactly one seat with a pointer and a keyboard.
 */
final class DeviceTest extends GtkTestCase
{
    private function display(): GdkDisplay
    {
        return $this->window()->get_display();
    }

    public function testADisplayHasASeat(): void
    {
        $seat = $this->display()->get_default_seat();

        self::assertInstanceOf(GdkSeat::class, $seat);
        self::assertSame($this->display(), $seat->get_display());
    }

    public function testASeatHasAPointerAndAKeyboard(): void
    {
        $seat = $this->display()->get_default_seat() ?? self::fail('no seat');

        $pointer = $seat->get_pointer();
        self::assertInstanceOf(GdkDevice::class, $pointer);
        self::assertInstanceOf(GdkDevice::class, $seat->get_keyboard());
        self::assertSame($seat, $pointer->get_seat(), 'the device knows the seat it belongs to');
    }

    /** What a device can say about itself, none of which PHP could invent. */
    public function testADeviceDescribesItself(): void
    {
        $seat = $this->display()->get_default_seat() ?? self::fail('no seat');
        $pointer = $seat->get_pointer() ?? self::fail('no pointer');

        self::assertNotSame('', $pointer->get_name());
        self::assertSame($this->display(), $pointer->get_display());
        self::assertGreaterThanOrEqual(0, $pointer->get_num_touches());
    }

    /** A device knows what kind of thing it is: the X11 core devices are a mouse and a keyboard. */
    public function testTheSourceSaysWhatKindOfDeviceItIs(): void
    {
        $seat = $this->display()->get_default_seat() ?? self::fail('no seat');

        self::assertSame(GdkInputSource::Mouse, $seat->get_pointer()?->get_source());
        self::assertSame(GdkInputSource::Keyboard, $seat->get_keyboard()?->get_source());
    }

    /** The lock states are a keyboard's business; nothing has pressed a lock key here. */
    public function testTheKeyboardReportsItsLockStates(): void
    {
        $seat = $this->display()->get_default_seat() ?? self::fail('no seat');
        $keyboard = $seat->get_keyboard() ?? self::fail('no keyboard');

        self::assertFalse($keyboard->get_caps_lock_state());
        self::assertFalse($keyboard->get_num_lock_state());
        self::assertSame(0, $keyboard->get_modifier_state(), 'no modifier is held down');
    }

    /** Neither class can be built from PHP: the backend owns them. */
    public function testNeitherCanBeConstructed(): void
    {
        foreach ([GdkDevice::class, GdkSeat::class] as $class) {
            self::assertFalse(
                new \ReflectionMethod($class, '__construct')->isPublic(),
                "$class::__construct() is private: GDK hands them out",
            );
        }
    }
}
