<?php

declare(strict_types=1);

namespace PhpGtk4\Tests\Subclass;

use Gtk4\GdkClipboard;
use Gtk4\GdkContentProvider;

/**
 * A content provider written in PHP: GDK tells a provider when it is put on a clipboard and when
 * it is taken off again, and asks it to announce that what it offers has changed. Each override
 * records the call and then chains to GTK's own implementation - `content_changed` is what emits
 * the ::content-changed signal, so skipping the chain would silently swallow it.
 */
final class RecordingContentProvider extends GdkContentProvider
{
    /** @var list<string> */
    public array $calls = [];

    public function vfunc_attach_clipboard(GdkClipboard $clipboard): void
    {
        $this->calls[] = 'attach';
        parent::vfunc_attach_clipboard($clipboard);
    }

    public function vfunc_detach_clipboard(GdkClipboard $clipboard): void
    {
        $this->calls[] = 'detach';
        parent::vfunc_detach_clipboard($clipboard);
    }

    public function vfunc_content_changed(): void
    {
        $this->calls[] = 'changed';
        parent::vfunc_content_changed();
    }
}
