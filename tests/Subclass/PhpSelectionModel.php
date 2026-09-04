<?php

declare(strict_types=1);

namespace PhpGtk4\Tests\Subclass;

use Gtk4\GObject;
use Gtk4\GtkBitset;
use Gtk4\GtkSelectionModel;
use Gtk4\PhpValue;

/**
 * A GtkSelectionModel implemented in PHP: the list model itself plus the selection slots GTK
 * asks about while it renders and while a list view runs its `list.*` actions.
 * Drives tests/PhpSelectionModelTest.php.
 */
final class PhpSelectionModel extends GObject implements GtkSelectionModel
{
    /** @var list<string> slot names in call order */
    public array $calls = [];

    /** @var array<int, bool> position => selected */
    public array $selected = [];

    /** @var list<PhpValue> */
    private array $items;

    public function __construct(int $count = 3)
    {
        parent::__construct();
        $this->items = array_map(static fn(int $i): PhpValue => new PhpValue($i), range(0, $count - 1));
    }

    public function get_item_type(): string
    {
        return 'PhpValue';
    }

    public function get_n_items(): int
    {
        return count($this->items);
    }

    public function get_item(int $position): ?GObject
    {
        return $this->items[$position] ?? null;
    }

    public function get_selection_in_range(int $position, int $n_items): GtkBitset
    {
        $this->calls[] = 'get_selection_in_range';
        $bitset = GtkBitset::new_empty();
        foreach ($this->selected as $index => $on) {
            if ($on && $index >= $position && $index < $position + $n_items) {
                $bitset->add($index);
            }
        }

        return $bitset;
    }

    public function is_selected(int $position): bool
    {
        $this->calls[] = "is_selected($position)";

        return $this->selected[$position] ?? false;
    }

    public function select_all(): bool
    {
        $this->calls[] = 'select_all';
        foreach (array_keys($this->items) as $index) {
            $this->selected[$index] = true;
        }

        return true;
    }

    public function select_item(int $position, bool $unselect_rest): bool
    {
        $this->calls[] = "select_item($position)";
        if ($unselect_rest) {
            $this->selected = [];
        }
        $this->selected[$position] = true;

        return true;
    }

    public function select_range(int $position, int $n_items, bool $unselect_rest): bool
    {
        $this->calls[] = "select_range($position,$n_items)";
        if ($unselect_rest) {
            $this->selected = [];
        }
        for ($i = $position; $i < $position + $n_items; $i++) {
            $this->selected[$i] = true;
        }

        return true;
    }

    public function set_selection(GtkBitset $selected, GtkBitset $mask): bool
    {
        $this->calls[] = 'set_selection';
        foreach (array_keys($this->items) as $index) {
            if ($mask->contains($index)) {
                $this->selected[$index] = $selected->contains($index);
            }
        }

        return true;
    }

    public function unselect_all(): bool
    {
        $this->calls[] = 'unselect_all';
        $this->selected = [];

        return true;
    }

    public function unselect_item(int $position): bool
    {
        $this->calls[] = "unselect_item($position)";
        $this->selected[$position] = false;

        return true;
    }

    public function unselect_range(int $position, int $n_items): bool
    {
        $this->calls[] = "unselect_range($position,$n_items)";
        for ($i = $position; $i < $position + $n_items; $i++) {
            $this->selected[$i] = false;
        }

        return true;
    }
}
