<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GListModel;
use Gtk4\GListStore;
use Gtk4\GObject;
use Gtk4\GtkButton;
use Gtk4\PhpValue;

/** Gtk4\PhpValue (PHP data as a GObject) and Gtk4\GListStore / GListModel. */
final class ListStoreTest extends GtkTestCase
{
    public function testPhpValueRoundTripsAnyValue(): void
    {
        foreach ([null, true, 42, 2.5, 'str', ['a' => [1, 2]], new \stdClass()] as $v) {
            $item = new PhpValue($v);
            self::assertInstanceOf(GObject::class, $item);
            self::assertSame($v, $item->get_value());
        }
        $item = new PhpValue();
        self::assertNull($item->get_value());
        $item->set_value('changed');
        self::assertSame('changed', $item->get_value());
    }

    public function testPhpValueHoldsObjectsByReferenceAndReleasesThem(): void
    {
        $obj = new \stdClass();
        $obj->n = 1;
        $weak = \WeakReference::create($obj);
        $item = new PhpValue($obj);
        unset($obj);
        self::assertNotNull($weak->get(), 'the item keeps the object alive');
        $held = $item->get_value();
        self::assertInstanceOf(\stdClass::class, $held);
        $held->n = 2;
        $again = $weak->get();
        self::assertInstanceOf(\stdClass::class, $again);
        self::assertSame(2, $again->n, 'same object, not a copy');
        unset($item, $held, $again);
        self::assertNull($weak->get(), 'released with the last handle');
    }

    public function testStoreDefaultsAndItemTypeNames(): void
    {
        self::assertSame('GObject', new GListStore()->get_item_type());
        self::assertSame('PhpValue', new GListStore(PhpValue::class)->get_item_type());
        self::assertSame('GtkButton', new GListStore('GtkButton')->get_item_type());
        self::assertInstanceOf(GListModel::class, new GListStore());
    }

    public function testUnknownItemTypeRejected(): void
    {
        $this->expectException(\ValueError::class);
        new GListStore('NoSuchType');
    }

    public function testAppendInsertRemoveFind(): void
    {
        $store = new GListStore(PhpValue::class);
        $a = new PhpValue('a');
        $b = new PhpValue('b');
        $c = new PhpValue('c');
        $store->append($a);
        $store->append($c);
        $store->insert(1, $b);
        self::assertSame(3, $store->get_n_items());
        self::assertSame(3, $store->n_items, '@property');
        self::assertSame($b, $store->get_item(1), 'identity: the same handle comes back');
        $third = $store->get_item(2);
        self::assertInstanceOf(PhpValue::class, $third);
        self::assertSame('c', $third->get_value());
        self::assertNull($store->get_item(3));
        self::assertSame(1, $store->find($b));
        self::assertNull($store->find(new PhpValue('b')), 'find is by identity, not value');
        $store->remove(0);
        $values = [];
        for ($i = 0; $i < $store->get_n_items(); $i++) {
            $it = $store->get_item($i);
            $values[] = $it instanceof PhpValue ? $it->get_value() : null;
        }
        self::assertSame(['b', 'c'], $values);
        $store->remove_all();
        self::assertSame(0, $store->get_n_items());
    }

    public function testStoreKeepsItemsAliveWithoutPhpHandles(): void
    {
        $store = new GListStore(PhpValue::class);
        $weak = null;
        (function () use ($store, &$weak): void {
            $payload = new \stdClass();
            $weak = \WeakReference::create($payload);
            $store->append(new PhpValue($payload));
        })();
        self::assertNotNull($weak->get(), 'the store owns the item, the item owns the payload');
        $item = $store->get_item(0);
        self::assertInstanceOf(PhpValue::class, $item, 'a fresh handle for a GTK-owned object');
        self::assertSame($weak->get(), $item->get_value());
        unset($item);
        $store->remove_all();
        self::assertNull($weak->get(), 'released once the store dropped it');
    }

    public function testWrongItemTypeIsATypeError(): void
    {
        $store = new GListStore(PhpValue::class);
        $this->expectException(\TypeError::class);
        $store->append(new GtkButton());
    }

    public function testPositionBounds(): void
    {
        $store = new GListStore();
        $this->expectException(\ValueError::class);
        $store->remove(0);
    }

    public function testItemsChangedSignal(): void
    {
        $store = new GListStore(PhpValue::class);
        $events = [];
        $handler = function (GListStore $s, int $pos, int $removed, int $added) use (&$events): void {
            $events[] = [$pos, $removed, $added, $s->get_n_items()];
        };
        $store->connect('items-changed', $handler);
        $store->append(new PhpValue(1));
        $store->append(new PhpValue(2));
        $store->remove(0);
        self::assertSame([[0, 0, 1, 1], [1, 0, 1, 2], [0, 1, 0, 1]], $events);
    }
}
