<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\JSCContext;
use Gtk4\JSCException;
use Gtk4\JSCValue;
use Gtk4\JSCVirtualMachine;

/**
 * JavaScriptCore on its own (`--enable-gtk4-webkit`): a {@see JSCContext} evaluates scripts
 * without any web view, a {@see JSCValue} converts both ways, functions and constructors are
 * called with a PHP list of values (the three `*_callv` overrides in gen/overrides), and a
 * JavaScript exception lands in the context rather than crossing into PHP on its own.
 *
 * Skipped as a whole in a build without WebKit ({@see Features}).
 */
final class JSCTest extends GtkTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Features::requires('webkit');
    }

    public function testEvaluatesAScriptToAValue(): void
    {
        $ctx = new JSCContext();
        $v = $ctx->evaluate('6 * 7', -1);
        self::assertTrue($v->is_number());
        self::assertSame(42, $v->to_int32());
        self::assertEqualsWithDelta(42.0, $v->to_double(), 1e-9);
        self::assertSame('42', $v->to_string());
        self::assertSame($ctx, $v->get_context(), 'the value knows its context, as the same handle');
        self::assertNull($ctx->get_exception());
    }

    public function testValuesConvertBothWays(): void
    {
        $ctx = new JSCContext();
        self::assertTrue(JSCValue::new_boolean($ctx, true)->to_boolean());
        self::assertTrue(JSCValue::new_null($ctx)->is_null());
        self::assertTrue(JSCValue::new_undefined($ctx)->is_undefined());
        self::assertSame('héllo', JSCValue::new_string($ctx, 'héllo')->to_string());
        self::assertSame('', JSCValue::new_string($ctx, null)->to_string());
        self::assertEqualsWithDelta(1.5, JSCValue::new_number($ctx, 1.5)->to_double(), 1e-12);

        $list = JSCValue::new_array_from_strv($ctx, ['a', 'b']);
        self::assertTrue($list->is_array());
        self::assertSame('["a","b"]', $list->to_json(0));
        self::assertSame('b', $list->object_get_property_at_index(1)->to_string());

        $object = JSCValue::new_from_json($ctx, '{"x": 1, "y": [2, 3]}');
        self::assertTrue($object->is_object());
        self::assertSame(1, $object->object_get_property('x')->to_int32());
        self::assertTrue($object->object_has_property('y'));
        self::assertFalse($object->object_has_property('z'));
        $object->object_set_property('z', JSCValue::new_string($ctx, 'new'));
        self::assertSame('{"x":1,"y":[2,3],"z":"new"}', $object->to_json(0));
        self::assertSame(['x', 'y', 'z'], $object->object_enumerate_properties());
    }

    public function testTheGlobalObjectIsSharedWithScripts(): void
    {
        $ctx = new JSCContext();
        $ctx->set_value('fromPhp', JSCValue::new_number($ctx, 7.0));
        self::assertSame(14, $ctx->evaluate('fromPhp * 2', -1)->to_int32());
        $ctx->evaluate('var fromJs = "hi"', -1);
        self::assertSame('hi', $ctx->get_value('fromJs')->to_string());
        self::assertSame('hi', $ctx->get_global_object()->object_get_property('fromJs')->to_string());
    }

    public function testFunctionsConstructorsAndMethodsTakeAListOfValues(): void
    {
        $ctx = new JSCContext();
        $multiply = $ctx->evaluate('(function (a, b) { return a * b; })', -1);
        self::assertTrue($multiply->is_function());
        $product = $multiply->function_call([JSCValue::new_number($ctx, 6.0), JSCValue::new_number($ctx, 7.0)]);
        self::assertSame(42, $product->to_int32());
        self::assertTrue($multiply->function_call()->is_number(), 'no arguments: NaN, still a number');

        $point = $ctx->evaluate('function Point(x) { this.x = x; }; Point', -1);
        self::assertTrue($point->is_constructor());
        $p = $point->constructor_call([JSCValue::new_number($ctx, 3.0)]);
        self::assertSame(3, $p->object_get_property('x')->to_int32());
        self::assertTrue($p->object_is_instance_of('Point'));

        $counter = $ctx->evaluate('({ n: 0, add(by) { this.n += by; return this.n; } })', -1);
        self::assertSame(5, $counter->object_invoke_method('add', [JSCValue::new_number($ctx, 5.0)])->to_int32());
        self::assertSame(5, $counter->object_get_property('n')->to_int32());
        self::assertTrue($counter->object_invoke_method('add')->is_number(), 'no arguments: NaN, still a number');
    }

    public function testAWrongElementInTheListIsATypeError(): void
    {
        $ctx = new JSCContext();
        $fn = $ctx->evaluate('(function () {})', -1);
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('must be a list of JSCValue, string found in it');
        /** @var array<int, mixed> $wrong a list, but not of JSCValue */
        $wrong = ['x'];
        $fn->function_call($wrong);
    }

    public function testAJavascriptExceptionStaysInTheContext(): void
    {
        $ctx = new JSCContext();
        $v = $ctx->evaluate('nope()', -1);
        self::assertTrue($v->is_undefined());
        $e = $ctx->get_exception();
        self::assertInstanceOf(JSCException::class, $e);
        self::assertSame('ReferenceError', $e->get_name());
        self::assertStringContainsString('nope', $e->get_message());
        self::assertSame(1, $e->get_line_number());
        $ctx->clear_exception();
        self::assertNull($ctx->get_exception());

        $own = new JSCException($ctx, 'boom');
        self::assertSame('boom', $own->get_message());
    }

    public function testContextsOnOneVirtualMachineAreIndependent(): void
    {
        $vm = new JSCVirtualMachine();
        $a = JSCContext::new_with_virtual_machine($vm);
        $b = JSCContext::new_with_virtual_machine($vm);
        self::assertSame($vm, $a->get_virtual_machine());
        $a->evaluate('var only = 1', -1);
        self::assertTrue($b->evaluate('typeof only', -1)->to_string() === 'undefined');
        self::assertSame(1, $a->evaluate('only', -1)->to_int32());
    }

    public function testALengthPastTheCodeIsRefused(): void
    {
        // JavaScriptCore reads `length` bytes of the code and aborts the process past its end.
        $ctx = new JSCContext();
        self::assertSame(3, $ctx->evaluate('1 + 2; 9', 5)->to_int32(), 'a shorter length reads a prefix');
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('Argument #2 ($length) must be -1 or at most the length of the code');
        $ctx->evaluate('1 + 2', 6);
    }

    public function testAStringWithANulIsRefused(): void
    {
        $ctx = new JSCContext();
        $this->expectException(\ValueError::class);
        JSCValue::new_string($ctx, "a\0b");
    }
}
