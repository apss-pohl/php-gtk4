<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GMenu;
use PhpGtk4\Tests\Subclass\RecordingMenu;

/**
 * GMenuModel's four slots, reached from a PHP subclass of GMenu: GMenuModel itself refuses
 * `new` (private constructor, gen/skip.txt), GMenu fills them, and the PHP override sits above both.
 * The attribute slot returns a variant, converted from whatever the override answers.
 */
final class GMenuVfuncTest extends GtkTestCase
{
    public function testEverySlotRunsAndChains(): void
    {
        $menu = new RecordingMenu();
        $menu->append('One', 'app.one');
        $section = new GMenu();
        $menu->append_section('More', $section);

        self::assertSame(2, $menu->get_n_items());
        self::assertTrue($menu->is_mutable());
        self::assertSame('One', $menu->get_item_attribute_value(0, 'label', 's'));
        self::assertSame('app.one', $menu->get_item_attribute_value(0, 'action', null));
        self::assertNull($menu->get_item_link(0, 'section'));
        self::assertSame($section, $menu->get_item_link(1, 'section'));

        self::assertContains('get_n_items', $menu->calls);
        self::assertContains('is_mutable', $menu->calls);
        self::assertContains('get_item_attribute_value(0,label,s)', $menu->calls);
        self::assertContains('get_item_attribute_value(0,action,null)', $menu->calls);
        self::assertContains('get_item_link(1,section)', $menu->calls);
    }

    public function testAnOverrideCanAnswerForItself(): void
    {
        $menu = new class extends GMenu {
            public function vfunc_get_n_items(): int
            {
                return 42;
            }

            public function vfunc_get_item_attribute_value(
                int $item_index,
                string $attribute,
                ?string $expected_type,
            ): mixed {
                return $attribute === 'label' ? "item $item_index" : null;
            }
        };

        self::assertSame(42, $menu->get_n_items());
        self::assertSame('item 7', $menu->get_item_attribute_value(7, 'label', null));
        self::assertNull($menu->get_item_attribute_value(7, 'icon', null));
    }

    /** Calling the native slot on a plain GMenu is chaining from nowhere: refused, not a crash. */
    public function testTheNativeSlotIsForChainingOnly(): void
    {
        $this->expectException(\LogicException::class);
        new GMenu()->vfunc_get_n_items();
    }
}
