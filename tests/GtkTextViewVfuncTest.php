<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GtkBox;
use Gtk4\GtkOrientation;
use Gtk4\GtkSnapshot;
use Gtk4\GtkTextBuffer;
use PhpGtk4\Tests\Subclass\RecordingTextView;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * The GtkTextView class-struct slots. Most of them back a key-binding signal, which is how a
 * test reaches them without a keyboard: emit() runs the same action GTK runs for the key, so
 * the generated thunk hands it to PHP and parent:: chains back into GTK.
 */
final class GtkTextViewVfuncTest extends GtkTestCase
{
    public function testTheViewAsksPhpForItsBuffer(): void
    {
        $view = new RecordingTextView();

        self::assertTrue($view->createdBuffer, 'GTK routed create_buffer through the PHP override');
        self::assertInstanceOf(GtkTextBuffer::class, $view->get_buffer());
    }

    /** @return iterable<string, array{string, list<mixed>, string}> signal, arguments, recorded slot */
    public static function actionSignals(): iterable
    {
        yield 'backspace'          => ['backspace', [], 'backspace'];
        yield 'copy-clipboard'     => ['copy-clipboard', [], 'copy_clipboard'];
        yield 'cut-clipboard'      => ['cut-clipboard', [], 'cut_clipboard'];
        yield 'paste-clipboard'    => ['paste-clipboard', [], 'paste_clipboard'];
        yield 'insert-emoji'       => ['insert-emoji', [], 'insert_emoji'];
        yield 'set-anchor'         => ['set-anchor', [], 'set_anchor'];
        yield 'toggle-overwrite'   => ['toggle-overwrite', [], 'toggle_overwrite'];
        yield 'move-cursor'        => ['move-cursor', [1, 1, false], 'move_cursor(1,1)'];
        yield 'delete-from-cursor' => ['delete-from-cursor', [0, 1], 'delete_from_cursor(0,1)'];
        yield 'insert-at-cursor'   => ['insert-at-cursor', ['xy'], 'insert_at_cursor(xy)'];
    }

    /**
     * @param list<mixed> $arguments
     */
    #[DataProvider('actionSignals')]
    public function testActionSignalReachesItsSlot(string $signal, array $arguments, string $recorded): void
    {
        $view = new RecordingTextView();
        $view->get_buffer()->set_text('hello world');
        $view->calls = [];

        $view->emit($signal, ...$arguments);

        self::assertSame([$recorded], $view->calls);
    }

    public function testExtendSelectionReachesItsSlot(): void
    {
        $view = new RecordingTextView();
        $buffer = $view->get_buffer();
        $buffer->set_text('hello world');
        $view->calls = [];

        $start = $buffer->get_start_iter();
        $view->emit('extend-selection', 0, $start, $start, $buffer->get_end_iter());

        self::assertSame(['extend_selection'], $view->calls);
    }

    public function testSnapshotLayerReachesItsSlotWhileTheViewPaints(): void
    {
        $view = new RecordingTextView();
        $view->get_buffer()->set_text('hello world');

        $box = new GtkBox(GtkOrientation::Vertical, 0);
        $box->append($view);
        $window = $this->window();
        $window->set_child($box);
        $window->present();
        $box->allocate(120, 80);          // a snapshot without an allocation is a GTK warning
        $view->calls = [];

        $box->snapshot_child($view, new GtkSnapshot());

        self::assertContains('snapshot_layer', $view->calls, 'GTK offered PHP each text layer');
    }
}
