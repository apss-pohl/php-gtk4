<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GSimpleAction;
use Gtk4\GVariant;

/**
 * Gtk4\GVariant: a value with a type PHP spelled, accepted wherever a plain value would be
 * inferred - the way to put a tuple inside a variant or a byte array under a `v`.
 */
final class GVariantTest extends GtkTestCase
{
    /** A dbusmenu layout item: `(ia{sv}av)`, the shape inference cannot spell inside a `v`. */
    private const ITEM = '(ia{sv}av)';

    public function testCarriesTheTypeAndTheValue(): void
    {
        $item = new GVariant(self::ITEM, [2, ['label' => 'Quit'], []]);
        self::assertSame(self::ITEM, $item->get_type_string());
        self::assertSame([2, ['label' => 'Quit'], []], $item->unpack());
        self::assertSame("(2, {'label': <'Quit'>}, [])", $item->print());
        self::assertSame("(2, {'label': <'Quit'>}, @av [])", $item->print(true), 'types annotated');
        ob_start();
        var_dump($item);
        $dump = (string) ob_get_clean();
        self::assertStringContainsString(self::ITEM, $dump, 'var_dump() shows the type');
        self::assertStringContainsString('Quit', $dump, 'var_dump() shows the value');
    }

    /** Inside a `v` a plain list infers to `av`; the handle keeps its tuple. */
    public function testATupleInsideAVariantNeedsTheHandle(): void
    {
        $plain = new GVariant('(u(ia{sv}av))', [1, [0, [], [[2, ['label' => 'Quit'], []]]]]);
        self::assertSame(
            "(uint32 1, (0, @a{sv} {}, [<[<2>, <{'label': <'Quit'>}>, <@as []>]>]))",
            $plain->print(true),
            'a nested list under v is an av, which no dbusmenu client accepts',
        );

        $child = new GVariant(self::ITEM, [2, ['label' => 'Quit'], []]);
        $typed = new GVariant('(u(ia{sv}av))', [1, [0, [], [$child]]]);
        self::assertSame(
            "(uint32 1, (0, @a{sv} {}, [<(2, {'label': <'Quit'>}, @av [])>]))",
            $typed->print(true),
            'the handle is wrapped under the v with its tuple intact',
        );
        self::assertSame([1, [0, [], [[2, ['label' => 'Quit'], []]]]], $typed->unpack());
    }

    public function testAHandleUnderItsOwnTypeIsAcceptedAndUnderAnotherRefused(): void
    {
        $int = new GVariant('x', 5);
        self::assertSame('(x)', new GVariant('(x)', [$int])->get_type_string(), 'own type');
        self::assertSame('(v)', new GVariant('(v)', [$int])->get_type_string(), 'wrapped');
        self::assertSame('(<int64 5>,)', new GVariant('(v)', [$int])->print(true), 'the x survives inside the v');
        self::assertSame(5, new GVariant('v', $int)->unpack(), 'a v holding it unpacks to the inner value');

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('cannot convert a GVariant of type x to GVariant type s');
        new GVariant('(s)', [$int]);
    }

    /** A byte array under inference is a string (`s`); the handle spells `ay`. */
    public function testBytesAndWideIntegers(): void
    {
        $bytes = new GVariant('ay', "\x00\x01\xff");
        self::assertSame([0, 1, 255], $bytes->unpack());
        self::assertSame('[byte 0x00, 0x01, 0xff]', $bytes->print(true));

        $wide = new GVariant('t', PHP_INT_MAX);
        self::assertSame(PHP_INT_MAX, $wide->unpack());
        self::assertSame('uint64 ' . PHP_INT_MAX, $wide->print(true));
    }

    /** Wherever a plain value is inferred the handle passes as it is: an action's state. */
    public function testAHandleTypesAnActionState(): void
    {
        $action = GSimpleAction::new_stateful('mode', null, new GVariant('x', 5));
        self::assertSame('x', $action->get_state_type(), 'an int would have inferred to i');
        self::assertSame(5, $action->get_state());

        $action->set_state(new GVariant('x', 6));
        self::assertSame(6, $action->get_state());
        $action->set_property('state', new GVariant('x', 7));
        self::assertSame(7, $action->state, 'a property write takes the handle too');
    }

    public function testConvertsLikeATypedParameter(): void
    {
        self::assertSame(3, new GVariant('i', 3)->unpack());
        self::assertNull(new GVariant('ms', null)->unpack());
        self::assertSame(['a' => 1, 'b' => 'x'], new GVariant('a{sv}', ['a' => 1, 'b' => 'x'])->unpack());
        self::assertSame(['x', 'y'], new GVariant('as', ['x', 'y'])->unpack());

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('cannot convert string to GVariant type i');
        new GVariant('(i)', ['x']);
    }

    public function testRefusesARangeViolationLikeAParameter(): void
    {
        $this->expectException(\ValueError::class);
        new GVariant('u', -1);
    }

    public function testRefusesAnIndefiniteOrInvalidTypeString(): void
    {
        $this->assertThrows(\ValueError::class, '"a*" given', fn() => new GVariant('a*', []));
        $this->assertThrows(\ValueError::class, '"(" given', fn() => new GVariant('(', []));
        $this->assertThrows(\ValueError::class, 'definite GVariant type string', fn() => new GVariant('', 1));
    }

    public function testImmutableCloneAndValueEquality(): void
    {
        $a = new GVariant(self::ITEM, [1, ['label' => 'a'], []]);
        $b = new GVariant(self::ITEM, [1, ['label' => 'a'], []]);
        $c = new GVariant(self::ITEM, [1, ['label' => 'b'], []]);
        self::assertTrue($a == $b);
        self::assertFalse($a == $c);
        self::assertFalse($a == new GVariant('i', 1), 'the type is part of the value');
        self::assertTrue($a == clone $a);
        self::assertNotSame($a, clone $a);
        self::assertSame($a->print(), (clone $a)->print());
    }

    /**
     * @param class-string<\Throwable> $class
     */
    private function assertThrows(string $class, string $needle, callable $fn): void
    {
        try {
            $fn();
        } catch (\Throwable $e) {
            self::assertInstanceOf($class, $e);
            self::assertStringContainsString($needle, $e->getMessage());
            return;
        }
        self::fail("expected $class");
    }
}
