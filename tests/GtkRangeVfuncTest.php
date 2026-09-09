<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GtkOrientation;
use PhpGtk4\Tests\Subclass\RecordingScale;

/**
 * The GtkRange class-struct slots. Each of the four is the default handler of a signal, so
 * emit() reaches the generated thunk the same way a scroll or a key press would.
 */
final class GtkRangeVfuncTest extends GtkTestCase
{
    private function scale(): RecordingScale
    {
        $scale = new RecordingScale(GtkOrientation::Horizontal);
        $scale->set_range(0.0, 10.0);
        $scale->calls = [];

        return $scale;
    }

    public function testAdjustBoundsReachesItsSlot(): void
    {
        $scale = $this->scale();

        $scale->emit('adjust-bounds', 5.0);

        self::assertSame(['adjust_bounds'], $scale->calls);
    }

    public function testMoveSliderReachesItsSlot(): void
    {
        $scale = $this->scale();

        $scale->emit('move-slider', 0);   // GTK_SCROLL_NONE

        self::assertSame(['move_slider'], $scale->calls);
    }

    public function testChangeValueReachesItsSlotAndMovesTheRange(): void
    {
        $scale = $this->scale();

        $scale->emit('change-value', 3, 5.0);   // GTK_SCROLL_STEP_FORWARD

        self::assertSame(['change_value', 'adjust_bounds', 'value_changed'], $scale->calls);
        self::assertSame(5.0, $scale->get_value(), 'parent:: chaining performed the move');
    }

    public function testValueChangedReachesItsSlot(): void
    {
        $scale = $this->scale();

        $scale->emit('value-changed');

        self::assertSame(['value_changed'], $scale->calls);
    }
}
