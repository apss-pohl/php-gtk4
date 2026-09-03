<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GAsyncResult;
use Gtk4\GdkClipboard;
use Gtk4\GdkContentFormats;
use Gtk4\GdkContentProvider;
use Gtk4\GdkDragAction;
use Gtk4\GdkTexture;
use Gtk4\GError;
use Gtk4\GLib;
use Gtk4\GtkDragSource;
use Gtk4\GtkDropTarget;
use Gtk4\GtkDropTargetAsync;
use PhpGtk4\Tests\Subclass\RecordingContentProvider;

/**
 * Drag and drop, and the typed clipboard payloads that share its machinery. A drag carries a
 * *value*: GIR spells that as a GValue with a GType, neither of which PHP has a word for, so the
 * payload is an ordinary PHP value and the type is named the way a list store names its item type
 * - "string"/"int"/"float"/"bool" or a registered class name (core/marshal).
 */
final class DragDropTest extends GtkTestCase
{
    private const string PNG = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJ'
        . 'AAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==';

    public function testAProviderCarriesEveryScalarBack(): void
    {
        self::assertSame('hello', GdkContentProvider::new_for_value('hello')->get_value());
        self::assertSame(42, GdkContentProvider::new_for_value(42)->get_value());
        self::assertSame(3.5, GdkContentProvider::new_for_value(3.5)->get_value());
        self::assertTrue(GdkContentProvider::new_for_value(true)->get_value());
    }

    public function testAProviderCarriesAGObjectHandle(): void
    {
        $texture = GdkTexture::new_from_bytes((string) base64_decode(self::PNG));
        $provider = GdkContentProvider::new_for_value($texture);
        self::assertSame($texture, $provider->get_value(), 'the same handle comes back');
    }

    public function testTheTypeCanBeNamedExplicitly(): void
    {
        self::assertSame('typed', GdkContentProvider::new_for_value('typed', 'string')->get_value());
        self::assertSame(
            'typed',
            GdkContentProvider::new_for_value('typed')->get_value('string'),
            'and asked for by name on the way out',
        );
    }

    public function testAnUnknownTypeNameIsRefused(): void
    {
        $this->expectException(\ValueError::class);
        GdkContentProvider::new_for_value('x', 'NoSuchTypeAnywhere');
    }

    public function testAProviderAdvertisesWhatItOffers(): void
    {
        $formats = GdkContentProvider::new_for_value('x')->ref_formats();
        self::assertSame(['string'], $formats->get_gtypes());
        self::assertTrue($formats->contain_gtype('string'));
        self::assertFalse($formats->contain_gtype('int'));
    }

    public function testAValuelessProviderSaysSoRatherThanGuessing(): void
    {
        // The base class offers nothing; GDK asserts on an untyped GValue, so it is refused.
        $this->expectException(\LogicException::class);
        new GdkContentProvider()->get_value();
    }

    public function testContentFormatsAreAListOfMimeTypes(): void
    {
        $formats = new GdkContentFormats(['text/plain', 'image/png']);
        self::assertSame(['text/plain', 'image/png'], $formats->get_mime_types());
        self::assertTrue($formats->contain_mime_type('image/png'));
        self::assertFalse($formats->contain_mime_type('application/x-nope'));
        self::assertSame([], new GdkContentFormats()->get_mime_types());
    }

    public function testContentFormatsRejectANonString(): void
    {
        $this->expectException(\TypeError::class);
        new GdkContentFormats([1, 2]);
    }

    public function testADropTargetDeclaresWhatItAccepts(): void
    {
        // GIR's constructor takes a GType, so without the override a target accepted nothing.
        $target = new GtkDropTarget('string', GdkDragAction::COPY);
        self::assertSame(['string'], $target->get_gtypes());
        self::assertSame(GdkDragAction::COPY, $target->get_actions());

        $target->set_gtypes(['string', 'GdkTexture']);
        self::assertSame(['string', 'GdkTexture'], $target->get_gtypes());
    }

    public function testADropTargetHasNoValueOutsideADrop(): void
    {
        self::assertNull(new GtkDropTarget('string', GdkDragAction::COPY)->get_value());
    }

    public function testADropTargetRefusesAnUnknownType(): void
    {
        $this->expectException(\ValueError::class);
        new GtkDropTarget('NoSuchTypeAnywhere', GdkDragAction::COPY);
    }

    public function testADragSourceOffersAProvider(): void
    {
        $provider = GdkContentProvider::new_for_value('dragged');
        $source = new GtkDragSource();
        $source->set_content($provider);
        $source->set_actions(GdkDragAction::COPY);

        self::assertSame($provider, $source->get_content());
        self::assertSame(GdkDragAction::COPY, $source->get_actions());
        self::assertNull($source->get_drag(), 'no drag is in flight');

        $source->set_content(null);
        self::assertNull($source->get_content());
    }

    public function testADragSourceIsAControllerAWidgetTakes(): void
    {
        $window = $this->window();
        $source = new GtkDragSource();
        $window->add_controller($source);
        // add_controller() hands ownership to the widget; the handle stays usable.
        self::assertSame(GdkDragAction::COPY, $source->get_actions() | GdkDragAction::COPY);
    }

    public function testTheClipboardTakesATypedValue(): void
    {
        // set_text()/set_texture() are the convenience calls; this is the general one, and it is
        // the reason GdkContentProvider had to be bound before the clipboard was complete.
        $clipboard = $this->window()->get_clipboard();
        $clipboard->set_value('typed clipboard');
        self::assertSame('typed clipboard', $clipboard->get_content()?->get_value());

        $clipboard->set_value(7);
        self::assertSame(7, $clipboard->get_content()?->get_value());
    }

    public function testTheClipboardTakesATextureByValue(): void
    {
        $texture = GdkTexture::new_from_bytes((string) base64_decode(self::PNG));
        $clipboard = $this->window()->get_clipboard();
        $clipboard->set_value($texture);
        self::assertSame($texture, $clipboard->get_content()?->get_value());
    }

    public function testTheseAreGdksToCreateNotPhps(): void
    {
        // A drag and a drop are made by GDK when one actually starts; a standalone one has no
        // device and aborts the process inside gdk_drag_set_property. GTK makes the drag icon
        // for a drag it started; a standalone one dies unrealized in gtk_drag_icon_realize.
        foreach ([\Gtk4\GdkDrag::class, \Gtk4\GdkDrop::class, \Gtk4\GtkDragIcon::class] as $class) {
            self::assertFalse(new \ReflectionClass($class)->isInstantiable(), "$class is not PHP's to make");
        }
    }

    public function testContentFormatsCombineWithoutConsumingEitherSide(): void
    {
        // gdk_content_formats_union() takes ownership of its receiver (GIR marks the instance
        // parameter `transfer full`), which the handle still owns: without the generator's copy
        // this corrupted the heap on the next use of $plain.
        $plain = new GdkContentFormats(['text/plain']);
        $png = new GdkContentFormats(['image/png']);
        self::assertSame(['text/plain', 'image/png'], $plain->union($png)->get_mime_types());
        self::assertSame(['text/plain'], $plain->get_mime_types(), 'the receiver survives it');
        self::assertSame(['image/png'], $png->get_mime_types());
    }

    public function testContentFormatsMatchAndPrint(): void
    {
        $plain = new GdkContentFormats(['text/plain']);
        $both = $plain->union(new GdkContentFormats(['image/png']));

        self::assertSame('text/plain', $plain->to_string());
        self::assertTrue($both->match($plain));
        self::assertSame('text/plain', $both->match_mime_type($plain));
        self::assertNull($plain->match_mime_type(new GdkContentFormats(['image/png'])));
    }

    public function testContentFormatsParseWhatTheyPrint(): void
    {
        self::assertSame(['text/plain'], GdkContentFormats::parse('text/plain')?->get_mime_types());
        self::assertNull(GdkContentFormats::parse('!!!!'), 'unparseable is null, not an exception');
    }

    public function testContentFormatsCrossTheSerialisationBoundary(): void
    {
        // A GType is what a drag carries in-process; a mime type is what crosses to another
        // application. The union_* calls are how GDK bridges the two.
        $gtypes = GdkContentProvider::new_for_value('x')->ref_formats();
        self::assertContains('text/plain', $gtypes->union_serialize_mime_types()->get_mime_types());
        self::assertSame(['string'], new GdkContentFormats(['text/plain'])->union_deserialize_gtypes()->get_gtypes());
    }

    public function testAProviderCarriesRawBytesUnderAMimeType(): void
    {
        $provider = GdkContentProvider::new_for_bytes('text/plain', 'raw bytes');
        self::assertSame(['text/plain'], $provider->ref_formats()->get_mime_types());
        self::assertSame(['text/plain'], $provider->ref_storable_formats()->get_mime_types());
    }

    public function testAProviderAnnouncesAChange(): void
    {
        $provider = GdkContentProvider::new_for_value('x');
        $provider->connect('content-changed', $this->latch());
        $provider->content_changed();
        self::assertTrue($this->latched());
    }

    public function testAnAsyncDropTargetTakesFormatsRatherThanATypeName(): void
    {
        // The async target matches on mime types, so it can accept a drag from another
        // application; the plain one matches on GTypes and so only on an in-process drag.
        $target = new GtkDropTargetAsync(new GdkContentFormats(['text/plain']), GdkDragAction::COPY);
        $formats = $target->get_formats();
        self::assertNotNull($formats);
        self::assertSame(['text/plain'], $formats->get_mime_types());
        self::assertSame(GdkDragAction::COPY, $target->get_actions());

        // GIR annotates gtk_drop_target_async_get_formats() `transfer full` although it returns a
        // borrowed pointer, so reading a second time used to read freed memory.
        $again = $target->get_formats();
        self::assertNotNull($again);
        self::assertSame(['text/plain'], $again->get_mime_types());

        $target->set_actions(GdkDragAction::MOVE);
        $target->set_formats(null);
        self::assertSame(GdkDragAction::MOVE, $target->get_actions());
        self::assertNull($target->get_formats(), 'null accepts everything');
        self::assertNull(new GtkDropTargetAsync(null, GdkDragAction::COPY)->get_formats());
    }

    public function testTheClipboardAdvertisesBothSpellingsOfWhatItHolds(): void
    {
        $clipboard = $this->window()->get_clipboard();
        $clipboard->set_value('typed and mimed');
        $formats = $clipboard->get_formats()->to_string();
        self::assertStringContainsString('gchararray', $formats, 'the in-process GType');
        self::assertStringContainsString('text/plain', $formats, 'and what leaves the process');
    }

    public function testStoringTheClipboardIsAnAnswerEitherWay(): void
    {
        // Handing the selection to a clipboard manager so it outlives the process. Xvfb has no
        // manager and answers with a GError; a desktop session with one answers true. Both are
        // the async pair working - what is pinned is that store_finish() reports rather than
        // returning a bogus value.
        $clipboard = $this->window()->get_clipboard();
        $clipboard->set_text('stored');

        $outcome = null;
        $clipboard->store_async(0, null, function (GdkClipboard $self, GAsyncResult $result) use (&$outcome): void {
            try {
                $outcome = $self->store_finish($result);
            } catch (GError $e) {
                $outcome = $e;
            }
        });
        self::pump(static fn(): bool => $outcome !== null);
        self::assertNotNull($outcome, 'the async callback ran');
        self::assertTrue($outcome === true || $outcome instanceof GError);
    }

    public function testADropTargetCanPreloadTheValue(): void
    {
        $target = new GtkDropTarget('string', GdkDragAction::COPY);
        self::assertFalse($target->get_preload(), 'the value arrives on drop by default');
        $target->set_preload(true);
        self::assertTrue($target->get_preload());

        self::assertSame(['string'], $target->get_formats()?->get_gtypes());
        self::assertNull($target->get_current_drop(), 'no drop is in flight');
        $target->reject();
    }

    public function testAContentProviderCanBeWrittenInPhp(): void
    {
        // GDK drives a provider through its class struct: it is told when it goes on a clipboard
        // and when it comes off, and content_changed() is what emits ::content-changed - so the
        // override has to chain, or the signal disappears.
        $provider = new RecordingContentProvider();
        $clipboard = $this->window()->get_clipboard();

        self::assertTrue($clipboard->set_content($provider));
        self::assertSame(['attach'], $provider->calls);

        $provider->connect('content-changed', $this->latch());
        $provider->content_changed();
        self::assertSame(['attach', 'changed'], $provider->calls);
        self::assertTrue($this->latched(), 'parent::vfunc_content_changed() emitted it');

        $clipboard->set_content(GdkContentProvider::new_for_value('something else'));
        self::assertSame(['attach', 'changed', 'detach'], $provider->calls);
    }

    public function testTheNativeVfuncsAreForChainingOnly(): void
    {
        // Calling one on a native handle would run GTK's implementation behind the class's back.
        $provider = GdkContentProvider::new_for_value('x');
        $this->expectException(\LogicException::class);
        $provider->vfunc_content_changed();
    }

    /** Pump the main context until $done() or the bound is reached (the async pair needs a loop). */
    private static function pump(callable $done, int $rounds = 500): void
    {
        for ($i = 0; $i < $rounds && !$done(); $i++) {
            GLib::main_context_iteration(false);
        }
    }

    public function testTheClipboardIsAContentProviderRoundTrip(): void
    {
        $clipboard = $this->window()->get_clipboard();
        $provider = GdkContentProvider::new_for_value('via provider');
        self::assertTrue($clipboard->set_content($provider));
        self::assertSame($provider, $clipboard->get_content());
    }
}
