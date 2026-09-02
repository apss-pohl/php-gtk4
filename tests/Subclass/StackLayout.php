<?php

declare(strict_types=1);

namespace PhpGtk4\Tests\Subclass;

use Gtk4\GtkLayoutManager;
use Gtk4\GtkOrientation;
use Gtk4\GtkSizeRequestMode;
use Gtk4\GtkWidget;

/**
 * A layout manager written in PHP: every child gets the full width and an equal slice of the
 * height, stacked top to bottom. core/subtype installs the thunks on the class' GType, so GTK's
 * own layout pass calls these methods for the widget the manager is set on.
 */
final class StackLayout extends GtkLayoutManager
{
    public int $allocations = 0;

    public int $measurements = 0;

    /** +1 for every vfunc_root(), -1 for every vfunc_unroot(). */
    public int $rooted = 0;

    /** @var list<array{int, int, int, int}> width, height, x, y per child of the last allocation */
    public array $placed = [];

    /** @return array{int, int, int, int} */
    public function vfunc_measure(GtkWidget $widget, GtkOrientation $orientation, int $for_size): array
    {
        $this->measurements++;
        $total = 0;
        foreach (self::children($widget) as $child) {
            [, $natural] = $child->measure($orientation, -1);
            $total = $orientation === GtkOrientation::Vertical ? $total + $natural : max($total, $natural);
        }

        return [$total, $total, -1, -1];
    }

    public function vfunc_allocate(GtkWidget $widget, int $width, int $height, int $baseline): void
    {
        $this->allocations++;
        $this->placed = [];
        $children = self::children($widget);
        $slice = (int) ($height / max(count($children), 1));
        foreach ($children as $i => $child) {
            $child->allocate($width, $slice, -1, 0, $i * $slice);
            $this->placed[] = [$width, $slice, 0, $i * $slice];
        }
    }

    public function vfunc_get_request_mode(GtkWidget $widget): GtkSizeRequestMode
    {
        return GtkSizeRequestMode::ConstantSize;
    }

    public function vfunc_root(): void
    {
        $this->rooted++;
        parent::vfunc_root();
    }

    public function vfunc_unroot(): void
    {
        $this->rooted--;
        parent::vfunc_unroot();
    }

    /** @return list<GtkWidget> */
    private static function children(GtkWidget $widget): array
    {
        $children = [];
        for ($c = $widget->get_first_child(); $c !== null; $c = $c->get_next_sibling()) {
            $children[] = $c;
        }

        return $children;
    }
}
