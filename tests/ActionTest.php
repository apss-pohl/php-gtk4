<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GAction;
use Gtk4\GActionGroup;
use Gtk4\GActionMap;
use Gtk4\GObject;
use Gtk4\GSimpleAction;
use Gtk4\GtkApplication;
use Gtk4\GtkButton;
use Gtk4\GtkWindow;
use PHPUnit\Framework\Attributes\DataProvider;

/** GSimpleAction, the GAction/GActionMap/GActionGroup interfaces, and GVariant <-> PHP mapping. */
final class ActionTest extends GtkTestCase
{
    private static function app(): GtkApplication
    {
        return new GtkApplication(null, 1 << 5);
    }

    public function testInterfacesAreRealPhpInterfaces(): void
    {
        self::assertTrue(interface_exists(GAction::class));
        $a = new GSimpleAction('x');
        self::assertInstanceOf(GAction::class, $a);
        self::assertInstanceOf(GObject::class, $a);
        $app = self::app();
        self::assertInstanceOf(GActionMap::class, $app);
        self::assertInstanceOf(GActionGroup::class, $app);
        self::assertNotInstanceOf(GAction::class, $app);
    }

    public function testStatelessActionActivate(): void
    {
        $a = new GSimpleAction('quit');
        self::assertSame('quit', $a->get_name());
        self::assertSame('quit', $a->name);
        self::assertTrue($a->get_enabled());
        self::assertNull($a->get_parameter_type());
        self::assertNull($a->get_state());

        $fired = [];
        $a->connect('activate', function (GSimpleAction $act, mixed $param) use (&$fired): void {
            $fired[] = $param;
        });
        $a->activate();
        self::assertSame([null], $fired);
        $a->set_enabled(false);
        $a->activate();
        self::assertCount(1, $fired, 'disabled actions do not activate');
    }


    /** @return iterable<string, array{string, mixed}> type string, value */
    public static function parameterTypes(): iterable
    {
        yield 'string'   => ['s', 'hello'];
        yield 'int32'    => ['i', 42];
        yield 'int64'    => ['x', PHP_INT_MAX];
        yield 'bool'     => ['b', true];
        yield 'double'   => ['d', 2.5];
        yield 'strings'  => ['as', ['a', 'b']];
        yield 'ints'     => ['ai', [1, 2, 3]];
        yield 'vardict'  => ['a{sv}', ['k' => 'v', 'n' => 7, 'flag' => false]];
        yield 'tuple'    => ['(si)', ['x', 9]];
        yield 'maybe'    => ['mi', null];
        yield 'maybe v'  => ['mi', 5];
    }

    #[DataProvider('parameterTypes')]
    public function testParameterRoundTrip(string $type, mixed $value): void
    {
        $a = new GSimpleAction('p', $type);
        self::assertSame($type, $a->get_parameter_type());
        $got = 'unset';
        $a->connect('activate', function (GSimpleAction $act, mixed $param) use (&$got): void {
            $got = $param;
        });
        $a->activate($value);
        self::assertSame($value, $got);
    }

    public function testParameterTypeMismatchIsATypeError(): void
    {
        $a = new GSimpleAction('p', 'i');
        $this->expectException(\TypeError::class);
        $a->activate(['not', 'an', 'int']);
    }

    public function testMissingRequiredParameter(): void
    {
        $a = new GSimpleAction('p', 's');
        $this->expectException(\ValueError::class);
        $a->activate();
    }

    public function testStatefulAction(): void
    {
        $toggle = GSimpleAction::new_stateful('dark', null, false);
        self::assertFalse($toggle->get_state());
        $changes = [];
        $toggle->connect('change-state', function (GSimpleAction $act, mixed $requested) use (&$changes): void {
            $changes[] = $requested;
            $act->set_state($requested);
        });
        $toggle->activate();  // a stateful boolean action toggles on activate
        self::assertTrue($toggle->get_state());
        self::assertSame([true], $changes);

        $counter = GSimpleAction::new_stateful('n', 'i', 0);
        $counter->connect('change-state', fn(GSimpleAction $act, mixed $v) => $act->set_state($v));
        $counter->activate(3);
        self::assertSame(3, $counter->get_state());
        $counter->set_state(10);
        self::assertSame(10, $counter->get_state());
    }

    public function testStateTypeIsInferredFromTheInitialValue(): void
    {
        $s = GSimpleAction::new_stateful('s', null, ['x' => 1, 'y' => 'two']);
        self::assertSame(['x' => 1, 'y' => 'two'], $s->get_state());
        $s->set_state(['x' => 2]);
        self::assertSame(['x' => 2], $s->get_state());
        $this->expectException(\LogicException::class);
        new GSimpleAction('stateless')->set_state(1);
    }

    public function testApplicationActionMapAndGroup(): void
    {
        $app = self::app();
        $quit = new GSimpleAction('quit');
        $app->add_action($quit);
        self::assertSame($quit, $app->lookup_action('quit'), 'identity through the interface');
        self::assertNull($app->lookup_action('nope'));

        // The GActionGroup side (has/list/activate) is only live once the application
        // is registered, i.e. from `startup` on.
        $hits = 0;
        $quit->connect('activate', function () use (&$hits): void {
            $hits++;
        });
        $has = null;
        $list = [];
        $after = null;
        $error = '';
        $app->connect('activate', function (GtkApplication $a) use (&$has, &$list, &$after, &$error): void {
            $has = $a->has_action('quit');
            $list = $a->list_actions();
            $a->activate_action('quit');
            $a->remove_action('quit');
            $after = $a->has_action('quit');
            try {
                $a->activate_action('quit');
            } catch (\ValueError $e) {
                $error = $e->getMessage();
            }
            $a->quit();
        });
        $app->run();
        self::assertTrue($has);
        self::assertContains('quit', $list);
        self::assertSame(1, $hits);
        self::assertFalse($after);
        self::assertStringContainsString("no action 'quit'", $error);
    }

    /**
     * Every GActionGroup query on an unregistered GApplication refuses instead of letting GLib
     * CRITICAL and answer a default that reads like "no such action" (gen/overrides/
     * Gio.ActionGroup.cpp). `add/remove/lookup_action` are GActionMap and work at any time.
     *
     * @return iterable<string, array{string, list<mixed>}>
     */
    public static function unregisteredQueries(): iterable
    {
        yield 'has_action' => ['has_action', ['quit']];
        yield 'get_action_enabled' => ['get_action_enabled', ['quit']];
        yield 'get_action_state' => ['get_action_state', ['quit']];
        yield 'get_action_parameter_type' => ['get_action_parameter_type', ['quit']];
        yield 'get_action_state_hint' => ['get_action_state_hint', ['quit']];
        yield 'get_action_state_type' => ['get_action_state_type', ['quit']];
        yield 'list_actions' => ['list_actions', []];
        yield 'change_action_state' => ['change_action_state', ['quit', 1]];
    }

    /**
     * @param list<mixed> $args
     */
    #[DataProvider('unregisteredQueries')]
    public function testActionQueriesBeforeRegistrationRefuse(string $method, array $args): void
    {
        $app = self::app();
        $app->add_action(new GSimpleAction('quit'));
        // The GActionMap side answers at any time, so the action really is there.
        self::assertSame('quit', $app->lookup_action('quit')?->get_name());

        try {
            $app->{$method}(...$args);
            self::fail("$method() answered instead of refusing before registration");
        } catch (\LogicException $e) {
            self::assertStringContainsString('not registered yet', $e->getMessage());
            self::assertStringContainsString($method, $e->getMessage());
        }
    }

    public function testWidgetActivateActionReachesTheApplication(): void
    {
        $app = self::app();
        $got = null;
        $say = new GSimpleAction('say', 's');
        $say->connect('activate', function (GSimpleAction $a, mixed $p) use (&$got): void {
            $got = $p;
        });
        $app->add_action($say);
        $app->connect('activate', function (GtkApplication $a) use (&$got): void {
            $win = new GtkWindow();
            $win->set_application($a);
            $button = GtkButton::new_with_label('Say');
            $win->set_child($button);
            self::assertTrue($button->activate_action('app.say', 'hi'));
            self::assertSame('hi', $got);
            self::assertFalse($button->activate_action('app.missing'));
            $a->quit();
        });
        $app->run();
        self::assertSame('hi', $got);
    }

    public function testVariantPropertyOnGObject(): void
    {
        // GSimpleAction:state is a GVariant property -> value mapping through get/set_property too.
        $a = GSimpleAction::new_stateful('v', null, 'initial');
        self::assertSame('initial', $a->get_property('state'));
        self::assertSame('initial', $a->state);
    }

    public function testChangeStateAndStateHint(): void
    {
        $a = GSimpleAction::new_stateful('level', null, 1);
        self::assertSame(1, $a->get_state());
        $a->change_state(5);                    // goes through change-state, unlike set_state()
        self::assertSame(5, $a->get_state());
        self::assertNull($a->get_state_hint());
        $a->set_state_hint([1, 5, 10]);
        self::assertSame([1, 5, 10], $a->get_state_hint());
        $a->set_enabled(false);
        self::assertFalse($a->get_enabled());
    }
}
