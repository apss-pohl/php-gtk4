<?php

declare(strict_types=1);

namespace PhpGtk4\Tests\Subclass;

use Gtk4\GtkSnapshot;
use Gtk4\GtkTextBuffer;
use Gtk4\GtkTextIter;
use Gtk4\GtkTextView;

/**
 * Overrides every GtkTextView slot php-gtk4 binds. Most of them back a key-binding signal, so
 * emit() is what drives them; each records its name and chains to the native implementation.
 * Drives tests/GtkTextViewVfuncTest.php.
 */
final class RecordingTextView extends GtkTextView
{
    /** @var list<string> slot names in call order */
    public array $calls = [];

    /** Set when vfunc_create_buffer() answered - it runs before the constructor returns. */
    public bool $createdBuffer = false;

    public function vfunc_backspace(): void
    {
        $this->calls[] = 'backspace';
        parent::vfunc_backspace();
    }

    public function vfunc_copy_clipboard(): void
    {
        $this->calls[] = 'copy_clipboard';
        parent::vfunc_copy_clipboard();
    }

    public function vfunc_create_buffer(): GtkTextBuffer
    {
        $this->createdBuffer = true;
        return parent::vfunc_create_buffer();
    }

    public function vfunc_cut_clipboard(): void
    {
        $this->calls[] = 'cut_clipboard';
        parent::vfunc_cut_clipboard();
    }

    public function vfunc_delete_from_cursor(int $type, int $count): void
    {
        $this->calls[] = "delete_from_cursor($type,$count)";
        parent::vfunc_delete_from_cursor($type, $count);
    }

    public function vfunc_extend_selection(
        int $granularity,
        GtkTextIter $location,
        GtkTextIter $start,
        GtkTextIter $end,
    ): bool {
        $this->calls[] = 'extend_selection';
        return parent::vfunc_extend_selection($granularity, $location, $start, $end);
    }

    public function vfunc_insert_at_cursor(string $str): void
    {
        $this->calls[] = "insert_at_cursor($str)";
        parent::vfunc_insert_at_cursor($str);
    }

    public function vfunc_insert_emoji(): void
    {
        $this->calls[] = 'insert_emoji';
        // Not chained: the native slot opens an emoji chooser popover.
    }

    public function vfunc_move_cursor(int $step, int $count, bool $extend_selection): void
    {
        $this->calls[] = "move_cursor($step,$count)";
        parent::vfunc_move_cursor($step, $count, $extend_selection);
    }

    public function vfunc_paste_clipboard(): void
    {
        $this->calls[] = 'paste_clipboard';
        parent::vfunc_paste_clipboard();
    }

    public function vfunc_set_anchor(): void
    {
        $this->calls[] = 'set_anchor';
        parent::vfunc_set_anchor();
    }

    public function vfunc_snapshot_layer(int $layer, GtkSnapshot $snapshot): void
    {
        $this->calls[] = 'snapshot_layer';
        parent::vfunc_snapshot_layer($layer, $snapshot);
    }

    public function vfunc_toggle_overwrite(): void
    {
        $this->calls[] = 'toggle_overwrite';
        parent::vfunc_toggle_overwrite();
    }
}
