<?php

declare(strict_types=1);

namespace PhpGtk4\Tests\Subclass;

use Gtk4\GMenu;
use Gtk4\GMenuModel;

/**
 * A GMenu subclass overriding every GMenuModel slot. GMenuModel itself cannot be subclassed
 * (private constructor, gen/skip.txt), but its slots are inherited by GMenu, so a PHP GMenu
 * reaches all four and chains to the native menu. Drives tests/GMenuVfuncTest.php.
 */
final class RecordingMenu extends GMenu
{
    /** @var list<string> slot calls in order */
    public array $calls = [];

    public function vfunc_get_n_items(): int
    {
        $this->calls[] = 'get_n_items';

        return parent::vfunc_get_n_items();
    }

    public function vfunc_is_mutable(): bool
    {
        $this->calls[] = 'is_mutable';

        return parent::vfunc_is_mutable();
    }

    public function vfunc_get_item_link(int $item_index, string $link): ?GMenuModel
    {
        $this->calls[] = "get_item_link($item_index,$link)";

        return parent::vfunc_get_item_link($item_index, $link);
    }

    public function vfunc_get_item_attribute_value(int $item_index, string $attribute, ?string $expected_type): mixed
    {
        $this->calls[] = "get_item_attribute_value($item_index,$attribute," . ($expected_type ?? 'null') . ')';

        return parent::vfunc_get_item_attribute_value($item_index, $attribute, $expected_type);
    }
}
