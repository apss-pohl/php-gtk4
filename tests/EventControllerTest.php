<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GdkButtonEvent;
use Gtk4\GdkDisplay;
use Gtk4\GdkEvent;
use Gtk4\GdkEventType;
use Gtk4\GdkKeyEvent;
use Gtk4\GdkKeyMatch;
use Gtk4\GdkModifierType;
use Gtk4\GdkRectangle;
use Gtk4\GdkScrollDirection;
use Gtk4\GdkScrollEvent;
use Gtk4\GLib;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkEventController;
use Gtk4\GtkEventControllerFocus;
use Gtk4\GtkEventControllerKey;
use Gtk4\GtkEventControllerLegacy;
use Gtk4\GtkEventControllerMotion;
use Gtk4\GtkEventControllerScroll;
use Gtk4\GtkEventControllerScrollFlags;
use Gtk4\GtkEventSequenceState;
use Gtk4\GtkGestureClick;
use Gtk4\GtkGestureDrag;
use Gtk4\GtkGestureLongPress;
use Gtk4\GtkGesturePan;
use Gtk4\GtkGestureRotate;
use Gtk4\GtkGestureSingle;
use Gtk4\GtkGestureSwipe;
use Gtk4\GtkGestureZoom;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkPanDirection;
use Gtk4\GtkPropagationLimit;
use Gtk4\GtkPropagationPhase;
use Gtk4\GtkWindow;

/**
 * Wave 3 (event controllers and the GdkEvent family). GTK 4 offers no way to construct events,
 * so the input is real: {@see XInput} fakes keys, pointer motion and buttons on the Xvfb display
 * (skipped without ext-ffi/libXtst or off the X11 backend) and the controllers attached to a
 * presented window receive them.
 */
final class EventControllerTest extends GtkTestCase
{
    private const int KEY_A = 0x61;         // GDK_KEY_a
    private const int KEY_ESCAPE = 0xff1b;  // GDK_KEY_Escape

    private GtkWindow $win;
    private GtkLabel $area;
    private GtkButton $button;
    private GtkButton $other;

    protected function setUp(): void
    {
        parent::setUp();
        if (getenv('GDK_BACKEND') !== 'x11') {
            self::markTestSkipped('fake input needs the X11 backend (tests/run.sh forces it)');
        }
        if (!XInput::available()) {
            self::markTestSkipped('fake input unavailable: ' . XInput::unavailable());
        }
    }

    /** A presented 200x200 window whose child fills it and can take focus. */
    private function show(): void
    {
        $this->win = $this->window();
        $this->win->set_default_size(200, 200);
        // The label takes the pointer (it fills the window); the button is what can take focus.
        $this->area = new GtkLabel('area');
        $this->area->set_hexpand(true);
        $this->area->set_vexpand(true);
        $this->button = GtkButton::new_with_label('focus');
        $this->other = GtkButton::new_with_label('other');
        $box = new GtkBox(GtkOrientation::Vertical, 0);
        $box->append($this->area);
        $box->append($this->button);
        $box->append($this->other);
        $this->win->set_child($box);
        $this->win->present();
        self::pump(0.25);
        self::assertTrue($this->win->get_mapped());
    }

    /** Iterate the main context for $seconds (or until $until() says so). */
    private static function pump(float $seconds, ?callable $until = null): void
    {
        $deadline = microtime(true) + $seconds;
        while (microtime(true) < $deadline) {
            GLib::main_context_iteration(false);
            if ($until !== null && $until()) {
                return;
            }
            usleep(2000);
        }
    }

    public function testKeyControllerAndKeyEvent(): void
    {
        $this->show();
        $key = new GtkEventControllerKey();
        $seen = [];
        $onKey = function (GtkEventControllerKey $c, int $keyval, int $keycode, int $state) use (&$seen): bool {
            $event = $c->get_current_event();
            $seen[] = [$keyval, $keycode, $state, $event, $c->get_current_event_state(), $c->get_current_event_time()];
            return true;
        };
        $key->connect('key-pressed', $onKey);
        $released = $this->latch();
        $key->connect('key-released', $released);
        $this->win->add_controller($key);
        self::assertSame($this->win, $key->get_widget());
        self::assertNull(
            $key->get_current_event(),
            'no event outside a dispatch',
        );

        XInput::key(self::KEY_A);
        self::pump(2.0, static fn(): bool => $seen !== []);
        self::assertCount(1, $seen, 'one key press arrived');
        [$keyval, $keycode, $state, $event, $eventState, $time] = $seen[0];
        self::assertSame(self::KEY_A, $keyval);
        self::assertGreaterThan(0, $keycode);
        self::assertSame(0, $state & (GdkModifierType::SHIFT_MASK | GdkModifierType::CONTROL_MASK));
        self::assertSame($state, $eventState);
        self::assertInstanceOf(GdkKeyEvent::class, $event, 'the current event wraps as its real type');
        self::assertSame(GdkEventType::KeyPress, $event->get_event_type());
        self::assertSame(self::KEY_A, $event->get_keyval());
        self::assertSame($keycode, $event->get_keycode());
        self::assertSame($time, $event->get_time());
        self::assertFalse($event->is_modifier());
        self::assertGreaterThanOrEqual(0, $event->get_layout());
        self::assertGreaterThanOrEqual(0, $event->get_level());
        self::assertNull($event->get_position(), 'key events have no position');
        self::assertFalse($event->get_pointer_emulated());
        self::assertFalse($event->triggers_context_menu());
        self::assertInstanceOf(GdkDisplay::class, $event->get_display());
        self::assertSame([self::KEY_A, 0], $event->get_match());
        self::assertSame(GdkKeyMatch::Exact, $event->matches(self::KEY_A, 0));
        self::assertSame(GdkKeyMatch::None, $event->matches(self::KEY_ESCAPE, 0));
        self::pump(0.5, fn(): bool => $this->latched());
        self::assertTrue($this->latched(), 'and its release');
        // The handle keeps the event alive after the dispatch (it owns a reference).
        self::assertSame(self::KEY_A, $event->get_keyval());
    }

    public function testLegacyControllerHandsOutTypedEventsAndAcceptsThemBack(): void
    {
        $this->show();
        $legacy = new GtkEventControllerLegacy();
        $events = [];
        $legacy->connect('event', function (GtkEventControllerLegacy $c, GdkEvent $e) use (&$events): bool {
            $events[] = $e;
            return false;
        });
        $this->win->add_controller($legacy);
        XInput::move(100, 100);
        XInput::click(3);
        $isButton = static fn(GdkEvent $e): bool => $e instanceof GdkButtonEvent;
        self::pump(2.0, static fn(): bool => count(array_filter($events, $isButton)) >= 2);
        $buttons = [];
        foreach ($events as $e) {
            if ($e instanceof GdkButtonEvent) {
                $buttons[] = $e;
            }
        }
        self::assertCount(2, $buttons, 'press and release');
        self::assertSame(
            [GdkEventType::ButtonPress, GdkEventType::ButtonRelease],
            [$buttons[0]->get_event_type(), $buttons[1]->get_event_type()],
        );
        self::assertSame(3, $buttons[0]->get_button());
        self::assertSame([100.0, 100.0], $buttons[0]->get_position());
        self::assertTrue($buttons[0]->triggers_context_menu(), 'the secondary button');
        self::assertNotSame(0, $buttons[0]->get_time());
        $isMotion = static fn(GdkEvent $e): bool => $e->get_event_type() === GdkEventType::MotionNotify;
        $motion = array_values(array_filter($events, $isMotion));
        self::assertNotEmpty($motion);
        self::assertSame(GdkEvent::class, $motion[0]::class, 'kinds without getters of their own use the base class');

        // The other direction: a captured event goes back into GTK as a GValue (emit()).
        $seenAgain = null;
        $probe = new GtkEventControllerLegacy();
        $probe->connect('event', function (GtkEventControllerLegacy $c, GdkEvent $e) use (&$seenAgain): bool {
            $seenAgain = $e;
            return true;
        });
        self::assertTrue($probe->emit('event', $buttons[0]));
        // Fundamental handles carry no identity (unlike GObjects): a new handle, the same event.
        $again = $seenAgain;
        if (!$again instanceof GdkButtonEvent) {
            self::fail('the re-emitted event must come back as a GdkButtonEvent');
        }
        self::assertSame([3, $buttons[0]->get_time()], [$again->get_button(), $again->get_time()]);
    }

    public function testEventsCannotBeConstructed(): void
    {
        $this->expectException(\Error::class);
        $this->expectExceptionMessage('created by the extension, not with new');
        $class = self::opaque(static fn(): string => GdkKeyEvent::class)();
        new $class();
    }

    public function testClickGestureAndSingleGestureApi(): void
    {
        $this->show();
        $click = new GtkGestureClick();
        self::assertInstanceOf(GtkGestureSingle::class, $click);
        $click->set_button(0);
        self::assertSame(0, $click->get_button(), '0 = any button');
        $click->set_exclusive(true);
        self::assertTrue($click->get_exclusive());
        $presses = [];
        $click->connect('pressed', function (GtkGestureClick $g, int $n, float $x, float $y) use (&$presses): void {
            $presses[] = [$n, $x, $y, $g->get_current_button(), $g->is_active(), $g->get_bounding_box()];
        });
        $releases = $this->latch();
        $click->connect('released', $releases);
        $this->area->add_controller($click);
        XInput::move(60, 40);
        XInput::click(1);
        self::pump(2.0, fn(): bool => $this->latched());
        self::assertCount(1, $presses);
        [$n, $x, $y, $button, $active, $box] = $presses[0];
        self::assertSame(1, $n);
        self::assertSame([60.0, 40.0], [$x, $y]);
        self::assertSame(1, $button);
        self::assertTrue($active, 'active while the button is down');
        self::assertInstanceOf(GdkRectangle::class, $box);
        self::assertTrue($this->latched());
        self::assertFalse($click->is_active());
        self::assertNull($click->get_bounding_box(), 'nothing tracked once released');
        self::assertSame(0, $click->get_current_button());
    }

    public function testSequenceApiThroughThePointerAndEventIdentity(): void
    {
        $this->show();
        $drag = new GtkGestureDrag();
        $seen = null;
        $drag->connect('drag-begin', function (GtkGestureDrag $g) use (&$seen): void {
            // The pointer's sequence is null; every per-sequence method accepts it.
            $seen = [
                $g->get_current_sequence(),
                $g->get_sequences(),
                $g->get_point(null),
                $g->get_last_event(null),
                $g->handles_sequence(null),
                $g->get_last_updated_sequence(),
                $g->get_current_event(),
            ];
        });
        $this->area->add_controller($drag);
        XInput::move(40, 30);
        XInput::press(1);
        self::pump(2.0, static fn(): bool => $seen !== null);
        XInput::release(1);
        self::pump(0.3);
        self::assertNotNull($seen);
        [$current, $sequences, $point, $last, $handles, $updated, $event] = $seen;
        self::assertNull($current, 'a mouse has no sequence');
        self::assertSame([null], $sequences, 'get_sequences() lists the pointer as null');
        self::assertSame([40.0, 30.0], $point);
        self::assertInstanceOf(GdkButtonEvent::class, $last);
        self::assertTrue($handles);
        self::assertNull($updated);
        // Fundamental handles keep identity while PHP holds them: the event the controller
        // reports and the one the gesture stored are one object.
        self::assertSame($last, $event);
        self::assertSame(1, $last->get_button());
    }

    public function testDragGestureReportsOffsets(): void
    {
        $this->show();
        $drag = new GtkGestureDrag();
        $log = [];
        $drag->connect('drag-begin', function (GtkGestureDrag $g, float $x, float $y) use (&$log): void {
            $log[] = ['begin', $x, $y, $g->get_start_point()];
        });
        $drag->connect('drag-update', function (GtkGestureDrag $g, float $x, float $y) use (&$log): void {
            $log[] = ['update', $x, $y, $g->get_offset()];
        });
        $drag->connect('drag-end', function (GtkGestureDrag $g, float $x, float $y) use (&$log): void {
            $log[] = ['end', $x, $y, $g->get_offset()];
        });
        $this->area->add_controller($drag);
        self::assertNull($drag->get_start_point(), 'no drag yet');
        XInput::move(20, 20);
        XInput::press(1);
        self::pump(0.3);
        XInput::move(50, 60);
        self::pump(0.3);
        XInput::release(1);
        self::pump(2.0, static fn(): bool => $log !== [] && $log[array_key_last($log)][0] === 'end');
        self::assertSame('begin', $log[0][0]);
        self::assertSame([20.0, 20.0], [$log[0][1], $log[0][2]]);
        self::assertSame([20.0, 20.0], $log[0][3], 'get_start_point() -> [x, y]');
        $end = $log[array_key_last($log)];
        self::assertSame('end', $end[0]);
        self::assertSame([30.0, 40.0], [$end[1], $end[2]], 'offsets from the start point');
        self::assertSame([30.0, 40.0], $end[3]);
        self::assertContains('update', array_column($log, 0));
    }

    public function testMotionAndFocusControllers(): void
    {
        $this->show();
        $motion = new GtkEventControllerMotion();
        /** @var list<array{string, float, float, bool}> $moves */
        $moves = [];
        $motion->connect('enter', function (GtkEventControllerMotion $c, float $x, float $y) use (&$moves): void {
            $moves[] = ['enter', $x, $y, $c->contains_pointer()];
        });
        $motion->connect('motion', function (GtkEventControllerMotion $c, float $x, float $y) use (&$moves): void {
            $moves[] = ['motion', $x, $y, $c->is_pointer()];
        });
        $this->area->add_controller($motion);
        XInput::move(150, 150);
        self::pump(0.4);
        XInput::move(30, 70);
        self::pump(2.0, static fn(): bool => $moves !== [] && $moves[array_key_last($moves)][1] === 30.0);
        self::assertSame('enter', $moves[0][0]);
        self::assertTrue($moves[0][3], 'contains_pointer() inside the enter handler');
        $last = $moves[array_key_last($moves)];
        self::assertSame([30.0, 70.0], [$last[1], $last[2]], 'the last move is where the pointer went');
        self::assertContains('motion', array_column($moves, 0));
        self::assertTrue($motion->contains_pointer());

        $focus = new GtkEventControllerFocus();
        /** @var list<array{string, bool, bool}> $seen */
        $seen = [];
        $focus->connect('enter', function (GtkEventControllerFocus $c) use (&$seen): void {
            $seen[] = ['enter', $c->contains_focus(), $c->is_focus()];
        });
        $focus->connect('leave', function (GtkEventControllerFocus $c) use (&$seen): void {
            $seen[] = ['leave', $c->contains_focus(), $c->is_focus()];
        });
        $this->other->grab_focus();  // presenting focused the first button; start elsewhere
        self::pump(0.2);
        $this->button->add_controller($focus);
        $this->button->grab_focus();  // enter
        self::pump(0.2);
        $this->other->grab_focus();  // leave
        self::pump(0.2);
        $this->button->grab_focus();  // enter again
        self::pump(0.3);
        self::assertSame(['enter', 'leave', 'enter'], array_column($seen, 0));
        self::assertSame([true, true], [$seen[0][1], $seen[0][2]], 'contains_focus() and is_focus() inside enter');
        self::assertTrue($focus->contains_focus());
    }

    public function testScrollControllerFromWheelButtons(): void
    {
        $this->show();
        $scroll = new GtkEventControllerScroll(GtkEventControllerScrollFlags::VERTICAL);
        self::assertSame(GtkEventControllerScrollFlags::VERTICAL, $scroll->get_flags());
        $scrolls = [];
        $scroll->connect('scroll', function (GtkEventControllerScroll $c, float $dx, float $dy) use (&$scrolls): bool {
            $event = $c->get_current_event();
            $scrolls[] = [$dx, $dy, $event, $c->get_unit()];
            return true;
        });
        $this->area->add_controller($scroll);
        XInput::move(100, 100);
        XInput::click(5);  // wheel down
        self::pump(2.0, static fn(): bool => $scrolls !== []);
        self::assertCount(1, $scrolls);
        [$dx, $dy, $event] = $scrolls[0];
        self::assertSame(0.0, $dx);
        self::assertSame(1.0, $dy, 'one wheel click down');
        self::assertInstanceOf(GdkScrollEvent::class, $event);
        self::assertSame(GdkScrollDirection::Down, $event->get_direction());
        self::assertFalse($event->is_stop());
        self::assertCount(2, $event->get_deltas());
    }

    public function testLongPressGesture(): void
    {
        $this->show();
        $long = new GtkGestureLongPress();
        $long->set_delay_factor(0.5);  // GTK clamps to 0.5 .. 2.0
        self::assertSame(0.5, $long->get_delay_factor());
        $pressed = [];
        $long->connect('pressed', function (GtkGestureLongPress $g, float $x, float $y) use (&$pressed): void {
            $pressed[] = [$x, $y];
        });
        $cancelled = $this->latch();
        $long->connect('cancelled', $cancelled);
        $this->area->add_controller($long);
        XInput::move(80, 80);
        XInput::press(1);
        self::pump(2.5, static fn(): bool => $pressed !== []);
        XInput::release(1);
        self::pump(0.3);
        self::assertSame([[80.0, 80.0]], $pressed, 'held long enough');
        self::assertFalse($this->latched());
    }

    public function testControllerBaseApiAndGrouping(): void
    {
        $key = new GtkEventControllerKey();
        self::assertInstanceOf(GtkEventController::class, $key);
        $key->set_name('keys');
        self::assertSame('keys', $key->get_name());
        $key->set_propagation_phase(GtkPropagationPhase::Capture);
        $key->set_propagation_limit(GtkPropagationLimit::SameNative);
        self::assertSame(GtkPropagationPhase::Capture, $key->get_propagation_phase());
        self::assertSame(GtkPropagationLimit::SameNative, $key->get_propagation_limit());
        $key->reset();
        self::assertSame(0, $key->get_current_event_time());
        self::assertSame(0, $key->get_current_event_state());

        $a = new GtkGestureClick();
        $b = new GtkGestureClick();
        $a->group($b);
        self::assertTrue($a->is_grouped_with($b));
        self::assertCount(2, $a->get_group());
        $b->ungroup();
        self::assertFalse($a->is_grouped_with($b));
        self::assertFalse($a->is_recognized());
        self::assertNull($a->get_bounding_box_center());
        self::assertSame(0, GtkEventSequenceState::None->value);

        $pan = new GtkGesturePan(GtkOrientation::Vertical);
        self::assertSame(GtkOrientation::Vertical, $pan->get_orientation());
        $pan->set_orientation(GtkOrientation::Horizontal);
        self::assertSame(GtkOrientation::Horizontal, $pan->get_orientation());
        self::assertInstanceOf(GtkGestureDrag::class, $pan);
        self::assertSame(0, GtkPanDirection::Left->value);
        $swipe = new GtkGestureSwipe();
        self::assertFalse($swipe->is_active(), 'no swipe in progress');
        $zoom = new GtkGestureZoom();
        self::assertSame(1.0, $zoom->get_scale_delta());
        $rotate = new GtkGestureRotate();
        self::assertSame(0.0, $rotate->get_angle_delta());
        $long = new GtkGestureLongPress();
        $long->set_touch_only(true);
        self::assertTrue($long->get_touch_only());
    }
}
