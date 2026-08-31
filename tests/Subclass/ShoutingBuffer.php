<?php

declare(strict_types=1);

namespace PhpGtk4\Tests\Subclass;

use Gtk4\GtkTextBuffer;
use Gtk4\GtkTextIter;

/** Uppercases everything inserted - a GtkTextBuffer subclass overriding the insert-text slot. */
final class ShoutingBuffer extends GtkTextBuffer
{
    public function vfunc_insert_text(GtkTextIter $pos, string $new_text, int $new_text_length): void
    {
        $upper = strtoupper($new_text);
        parent::vfunc_insert_text($pos, $upper, strlen($upper));
    }
}
