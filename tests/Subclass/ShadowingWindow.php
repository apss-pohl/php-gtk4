<?php

declare(strict_types=1);

namespace PhpGtk4\Tests\Subclass;

use Gtk4\GtkWindow;

/**
 * PropertyAccessTest: declared PHP properties named like GObject properties. The class author's
 * property wins on the `$obj->prop` path; GTK's stays reachable through its methods.
 */
final class ShadowingWindow extends GtkWindow
{
    public string $title = 'php';
    private int $default_width = 7;

    public function width(): int
    {
        return $this->default_width;
    }

    public function setWidth(int $width): void
    {
        $this->default_width = $width;
        $this->default_width++;
    }
}
