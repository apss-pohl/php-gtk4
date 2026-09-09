<?php

declare(strict_types=1);

namespace PhpGtk4\Tests\Subclass;

use Gtk4\GdkClipboard;
use Gtk4\GdkPaintable;
use Gtk4\GtkTextBuffer;
use Gtk4\GtkTextIter;
use Gtk4\GtkTextMark;
use Gtk4\GtkTextTag;

/**
 * Overrides every GtkTextBuffer slot php-gtk4 binds, records which ones GTK routed through
 * PHP and chains each to the native implementation, so the buffer still behaves normally.
 * Drives tests/GtkTextBufferVfuncTest.php.
 */
final class RecordingTextBuffer extends GtkTextBuffer
{
    /** @var list<string> slot names in call order */
    public array $calls = [];

    public function vfunc_apply_tag(GtkTextTag $tag, GtkTextIter $start, GtkTextIter $end): void
    {
        $this->calls[] = 'apply_tag';
        parent::vfunc_apply_tag($tag, $start, $end);
    }

    public function vfunc_begin_user_action(): void
    {
        $this->calls[] = 'begin_user_action';
        parent::vfunc_begin_user_action();
    }

    public function vfunc_changed(): void
    {
        $this->calls[] = 'changed';
        parent::vfunc_changed();
    }

    public function vfunc_delete_range(GtkTextIter $start, GtkTextIter $end): void
    {
        $this->calls[] = 'delete_range';
        parent::vfunc_delete_range($start, $end);
    }

    public function vfunc_end_user_action(): void
    {
        $this->calls[] = 'end_user_action';
        parent::vfunc_end_user_action();
    }

    public function vfunc_insert_paintable(GtkTextIter $iter, GdkPaintable $paintable): void
    {
        $this->calls[] = 'insert_paintable';
        parent::vfunc_insert_paintable($iter, $paintable);
    }

    public function vfunc_insert_text(GtkTextIter $pos, string $new_text, int $new_text_length): void
    {
        $this->calls[] = 'insert_text';
        parent::vfunc_insert_text($pos, $new_text, $new_text_length);
    }

    public function vfunc_mark_deleted(GtkTextMark $mark): void
    {
        $this->calls[] = 'mark_deleted';
        parent::vfunc_mark_deleted($mark);
    }

    public function vfunc_mark_set(GtkTextIter $location, GtkTextMark $mark): void
    {
        $this->calls[] = 'mark_set';
        parent::vfunc_mark_set($location, $mark);
    }

    public function vfunc_modified_changed(): void
    {
        $this->calls[] = 'modified_changed';
        parent::vfunc_modified_changed();
    }

    public function vfunc_paste_done(GdkClipboard $clipboard): void
    {
        $this->calls[] = 'paste_done';
        parent::vfunc_paste_done($clipboard);
    }

    public function vfunc_redo(): void
    {
        $this->calls[] = 'redo';
        parent::vfunc_redo();
    }

    public function vfunc_remove_tag(GtkTextTag $tag, GtkTextIter $start, GtkTextIter $end): void
    {
        $this->calls[] = 'remove_tag';
        parent::vfunc_remove_tag($tag, $start, $end);
    }

    public function vfunc_undo(): void
    {
        $this->calls[] = 'undo';
        parent::vfunc_undo();
    }
}
