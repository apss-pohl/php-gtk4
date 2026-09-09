<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GObject;
use Gtk4\GParamSpec;
use Gtk4\GtkApplication;
use Gtk4\GtkButton;
use Gtk4\GtkLabel;
use Gtk4\GtkWindow;

/** Milestone 2b mechanisms: collections with transfer semantics, out parameters, fundamental handles. */
final class FoundationTest extends GtkTestCase
{
    public function testGListTransferNoneKeepsIdentity(): void
    {
        $app = new GtkApplication(null, 1 << 5);
        $seen = null;
        $app->connect('activate', function (GtkApplication $a) use (&$seen): void {
            $w1 = new GtkWindow();
            $w1->set_application($a);
            $w2 = new GtkWindow();
            $w2->set_application($a);
            $windows = $a->get_windows();
            self::assertCount(2, $windows);
            self::assertContains($w1, $windows, 'transfer none: the same handles come back');
            self::assertContains($w2, $windows);
            $w1->destroy();
            self::assertCount(1, $a->get_windows());
            $seen = $windows;
            $w2->destroy();
        });
        $app->run();
        self::assertNotNull($seen);
        self::assertSame([], new GtkApplication(null, 1 << 5)->get_windows(), 'empty list -> []');
    }

    public function testGListTransferContainer(): void
    {
        $button = new GtkButton();
        $label = new GtkLabel('_Press');
        self::assertSame([], $button->list_mnemonic_labels());
        $label->set_property('use-underline', true);
        $label->set_property('mnemonic-widget', $button);
        $labels = $button->list_mnemonic_labels();
        self::assertSame([$label], $labels, 'container transfer: list freed, elements borrowed and wrapped');
    }

    public function testOutParametersBecomeAList(): void
    {
        $b = new GtkButton();
        self::assertSame([-1, -1], $b->get_size_request(), 'unset = natural size');
        $b->set_size_request(120, 40);
        self::assertSame([120, 40], $b->get_size_request());

        $w = $this->window();
        $w->set_default_size(300, 200);
        self::assertSame([300, 200], $w->get_default_size());
    }

    public function testBooleanWithOutParametersReturnsOutsOrNull(): void
    {
        $l = new GtkLabel('hello world');
        $l->set_selectable(true);
        self::assertNull($l->get_selection_bounds(), 'nothing selected -> null');
        $l->select_region(0, 5);
        self::assertSame([0, 5], $l->get_selection_bounds());
    }

    public function testFundamentalHandleOwnsAReferenceAndIsNotCloneable(): void
    {
        $w = $this->window();
        $spec = null;
        $w->connect('notify::title', function (\Gtk4\GObject $o, GParamSpec $p) use (&$spec): void {
            $spec = $p;
        });
        $w->set_title('x');
        self::assertInstanceOf(GParamSpec::class, $spec);
        $w->destroy();
        unset($w);
        self::assertSame('title', $spec->get_name(), 'handle keeps the spec alive after the emitter is gone');
        $this->expectException(\Error::class);
        $copy = clone $spec;
        unset($copy);
    }

    public function testStrvRejectsNestedValues(): void
    {
        $this->expectException(\TypeError::class);
        new GtkButton()->set_css_classes(['ok', ['nested']]);
    }

    public function testFundamentalHandlesKeepIdentity(): void
    {
        // notify hands out the same GParamSpec twice: same C instance -> same handle (===),
        // like GObject handles; a released handle is forgotten and a later wrap makes a new one.
        $w = $this->window();
        $specs = [];
        $w->connect('notify::title', function (GObject $o, GParamSpec $spec) use (&$specs): void {
            $specs[] = $spec;
        });
        /** @return list<mixed> what the handler collected so far (by reference, so read it here) */
        $collected = function () use (&$specs): array {
            return $specs;
        };
        $w->set_title('a');
        $w->set_title('b');
        self::assertCount(2, $specs);
        self::assertSame($specs[0], $specs[1], 'the property\'s GParamSpec is one handle');
        self::assertSame('title', $specs[0]->get_name());
        $weak = \WeakReference::create($specs[0]);
        $specs = [];
        self::assertNull($weak->get(), 'released with the last reference; the identity entry goes with it');
        $w->set_title('c');
        $fresh = $collected()[0] ?? null;
        self::assertInstanceOf(GParamSpec::class, $fresh, 'a fresh handle serves the next emission');
        self::assertSame('title', $fresh->get_name());
    }
}
