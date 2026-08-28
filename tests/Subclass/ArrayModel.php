<?php

declare(strict_types=1);

namespace PhpGtk4\Tests\Subclass;

use Gtk4\GListModel;
use Gtk4\GObject;
use Gtk4\PhpValue;

/**
 * A GListModel implemented in PHP (core/subtype adds the GTK interface to the class' GType and
 * routes its three slots to these methods). Changes are announced with `items-changed`.
 */
final class ArrayModel extends GObject implements GListModel
{
    /** @var list<PhpValue> */
    private array $items = [];

    public int $calls = 0;

    public function get_item_type(): string
    {
        return 'PhpValue';
    }

    public function get_n_items(): int
    {
        $this->calls++;

        return count($this->items);
    }

    public function get_item(int $position): ?GObject
    {
        return $this->items[$position] ?? null;
    }

    public function push(mixed $value): void
    {
        $this->items[] = new PhpValue($value);
        $this->emit('items-changed', count($this->items) - 1, 0, 1);
    }
}
