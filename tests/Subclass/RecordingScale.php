<?php

declare(strict_types=1);

namespace PhpGtk4\Tests\Subclass;

use Gtk4\GtkScale;

/**
 * Overrides every GtkRange slot. All four back a signal, so emit() is what drives them.
 * Drives tests/GtkRangeVfuncTest.php; ChainingScale is the narrower fixture ArgumentGuardTest
 * uses for the enum check on vfunc_change_value().
 */
final class RecordingScale extends GtkScale
{
    /** @var list<string> slot names in call order */
    public array $calls = [];

    public function vfunc_adjust_bounds(float $new_value): void
    {
        $this->calls[] = 'adjust_bounds';
        parent::vfunc_adjust_bounds($new_value);
    }

    public function vfunc_change_value(int $scroll, float $new_value): bool
    {
        $this->calls[] = 'change_value';

        return parent::vfunc_change_value($scroll, $new_value);
    }

    public function vfunc_move_slider(int $scroll): void
    {
        $this->calls[] = 'move_slider';
        parent::vfunc_move_slider($scroll);
    }

    public function vfunc_value_changed(): void
    {
        $this->calls[] = 'value_changed';
        parent::vfunc_value_changed();
    }
}
