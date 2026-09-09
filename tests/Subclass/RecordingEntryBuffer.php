<?php

declare(strict_types=1);

namespace PhpGtk4\Tests\Subclass;

use Gtk4\GtkEntryBuffer;

/**
 * Overrides every GtkEntryBuffer slot: the two that do the work (insert_text, delete_text),
 * the two notifications GTK sends afterwards and the length accessor GTK asks for constantly.
 * Drives tests/GtkEntryBufferVfuncTest.php.
 */
final class RecordingEntryBuffer extends GtkEntryBuffer
{
    /** @var list<string> slot names in call order */
    public array $calls = [];

    public function vfunc_delete_text(int $position, int $n_chars): int
    {
        $this->calls[] = "delete_text($position,$n_chars)";
        return parent::vfunc_delete_text($position, $n_chars);
    }

    public function vfunc_deleted_text(int $position, int $n_chars): void
    {
        $this->calls[] = "deleted_text($position,$n_chars)";
        parent::vfunc_deleted_text($position, $n_chars);
    }

    public function vfunc_get_length(): int
    {
        $this->calls[] = 'get_length';
        return parent::vfunc_get_length();
    }

    public function vfunc_insert_text(int $position, string $chars, int $n_chars): int
    {
        $this->calls[] = "insert_text($position,$chars)";
        return parent::vfunc_insert_text($position, $chars, $n_chars);
    }

    public function vfunc_inserted_text(int $position, string $chars, int $n_chars): void
    {
        $this->calls[] = "inserted_text($position,$chars)";
        parent::vfunc_inserted_text($position, $chars, $n_chars);
    }
}
