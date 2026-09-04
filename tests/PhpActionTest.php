<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GAction;
use Gtk4\GApplication;
use Gtk4\GApplicationFlags;
use PhpGtk4\Tests\Subclass\PhpAction;

/**
 * A GAction implemented in PHP, driven through a GActionMap the way GLib drives any action:
 * the thunks convert variants and variant types both ways, and GLib keeps the borrowed name
 * and type pointers the instance owns.
 */
final class PhpActionTest extends GtkTestCase
{
    private static int $apps = 0;

    /**
     * A registered GApplication: `add/remove/lookup_action` always work, the GActionGroup side
     * (`list_actions`, `activate_action`, ...) only from `startup` on, which register() emits
     * without entering run(). Non-unique, so it stays off the session bus.
     */
    private function map(): GApplication
    {
        $id = 'org.phpgtk4.PhpAction.p' . getmypid() . 'n' . self::$apps++;
        $app = new GApplication($id, GApplicationFlags::NON_UNIQUE);
        $app->register(null);

        return $app;
    }

    public function testThePhpObjectIsAnAction(): void
    {
        self::assertInstanceOf(GAction::class, new PhpAction());
    }

    public function testAMapTakesItByTheNameItAnswers(): void
    {
        $action = new PhpAction('hello');
        $map = $this->map();

        $map->add_action($action);

        self::assertSame(['hello'], $map->list_actions());
        self::assertTrue($map->has_action('hello'));
        self::assertSame($action, $map->lookup_action('hello'));
        self::assertTrue($map->get_action_enabled('hello'));
    }

    public function testTypesAndStateComeFromTheGetters(): void
    {
        $action = new PhpAction('n', parameter_type: 's', state_type: 'i', state: 3, state_hint: [1, 2, 3]);
        $map = $this->map();
        $map->add_action($action);

        self::assertSame('s', $map->get_action_parameter_type('n'));
        self::assertSame('i', $map->get_action_state_type('n'));
        self::assertSame(3, $map->get_action_state('n'));
        self::assertSame([1, 2, 3], $map->get_action_state_hint('n'));
    }

    /** The state is converted against the type the action itself declares, not inferred. */
    public function testTheStateIsTypedByTheStateType(): void
    {
        $action = new PhpAction('n', state_type: 'x', state: 5);
        $map = $this->map();
        $map->add_action($action);

        self::assertSame(5, $map->get_action_state('n'));
        $action->state = ['not', 'an', 'int'];
        $seen = $this->captureHandlerException(fn() => $map->get_action_state('n'));

        self::assertNotNull($seen);
        self::assertSame('GAction::get_state', $seen[1]);
    }

    public function testActivationAndStateChangesReachThePhpSlots(): void
    {
        $action = new PhpAction('n', parameter_type: 's', state_type: 'i', state: 1);
        $map = $this->map();
        $map->add_action($action);

        $map->activate_action('n', 'go');
        $map->change_action_state('n', 7);

        self::assertSame([['activate', 'go'], ['change_state', 7]], $action->calls);
        self::assertSame(7, $map->get_action_state('n'));
    }

    public function testANullParameterAndAStatelessActionAreNull(): void
    {
        $action = new PhpAction('n');
        $map = $this->map();
        $map->add_action($action);

        $map->activate_action('n', null);

        self::assertSame([['activate', null]], $action->calls);
        self::assertNull($map->get_action_parameter_type('n'));
        self::assertNull($map->get_action_state_type('n'));
        self::assertNull($map->get_action_state('n'));
        self::assertNull($map->get_action_state_hint('n'));
    }

    /** The type string is validated in the thunk; GLib gets NULL, PHP the TypeError. */
    public function testAnInvalidTypeStringIsATypeError(): void
    {
        $action = new PhpAction('n', state_type: 'not a type');
        $map = $this->map();

        $seen = $this->captureHandlerException(function () use ($map, $action): void {
            $map->add_action($action);  // GLib asks for the state type as soon as it holds the action
            self::assertNull($map->get_action_state_type('n'));
        });

        self::assertNotNull($seen);
        self::assertStringContainsString('must be a valid GVariant type string', $seen[0]);
        self::assertSame('GAction::get_state_type', $seen[1]);
    }

    /**
     * The interface's properties, read the way C reads them (g_object_get_property()): each
     * is what the PHP getter answers. On the PHP side `$action->name` is the plain property
     * the getter reads - not a GObject property, or the getter would call itself.
     */
    public function testTheInterfacePropertiesAreAnsweredByTheGetters(): void
    {
        $action = new PhpAction('n', parameter_type: 's', state_type: 'b', state: true, enabled: false);

        self::assertSame('n', $action->get_property('name'));
        self::assertSame('s', $action->get_property('parameter-type'));
        self::assertSame('b', $action->get_property('state-type'));
        self::assertTrue($action->get_property('state'));
        self::assertFalse($action->get_property('enabled'));
        self::assertSame('n', $action->name);
        $action->name = 'renamed';
        self::assertSame('renamed', $action->get_property('name'));
    }

    public function testAnInvalidTypeStringAsAPropertyIsATypeError(): void
    {
        $action = new PhpAction('n', state_type: 'not a type');

        $seen = $this->captureHandlerException(fn() => $action->get_property('state-type'));

        self::assertNotNull($seen);
        self::assertStringContainsString('GVariant type string', $seen[0]);
        self::assertSame(PhpAction::class . '::get_state_type', $seen[1]);
    }
}
