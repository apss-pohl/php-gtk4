<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GActionGroup;
use Gtk4\GMenu;
use Gtk4\GtkApplication;
use Gtk4\GtkButton;
use Gtk4\GtkMenuButton;
use PhpGtk4\Tests\Subclass\PhpActionGroup;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * A GActionGroup implemented in PHP: GTK's action muxer lists it, queries it and activates it
 * through the interface slots, and the one group GTK cannot read - an unregistered
 * application - is refused rather than walked (the muxer reads the action list the moment a
 * group is inserted, without checking the result, which was a SIGSEGV).
 */
final class PhpActionGroupTest extends GtkTestCase
{
    public function testThePhpObjectIsAnActionGroup(): void
    {
        $group = new PhpActionGroup();

        self::assertInstanceOf(GActionGroup::class, $group);
    }

    /**
     * The four change signals, each routed into the PHP slot of the same name; the state one
     * carries a variant, converted to the PHP value.
     *
     * @return iterable<string, array{string, list<mixed>, string}> signal, arguments, recorded slot
     */
    public static function changeSignals(): iterable
    {
        yield 'action-added'   => ['action-added', ['go'], 'action_added(go)'];
        yield 'action-removed' => ['action-removed', ['go'], 'action_removed(go)'];
        yield 'enabled-changed' => [
            'action-enabled-changed', ['go', false], 'action_enabled_changed(go,false)',
        ];
        yield 'state-changed' => [
            'action-state-changed', ['flag', false], 'action_state_changed(flag,false)',
        ];
    }

    /**
     * @param list<mixed> $arguments
     */
    #[DataProvider('changeSignals')]
    public function testChangeSignalReachesItsSlot(string $signal, array $arguments, string $recorded): void
    {
        $group = new PhpActionGroup();

        $group->emit($signal, ...$arguments);

        self::assertSame([$recorded], $group->calls);
    }

    public function testTheGroupAnswersTheQueries(): void
    {
        $group = new PhpActionGroup();

        self::assertSame(['go', 'say', 'flag'], $group->list_actions());
        self::assertTrue($group->has_action('say'));
        self::assertSame('s', $group->get_action_parameter_type('say'));
        self::assertNull($group->get_action_parameter_type('go'));
        self::assertSame('b', $group->get_action_state_type('flag'));
        self::assertTrue($group->get_action_state('flag'));
        self::assertNull($group->get_action_state('go'));
        self::assertNull($group->get_action_state_hint('flag'));
    }

    /**
     * A menu bound to the group queries it the way every GTK consumer does - through the
     * interface slots, via the widget's action muxer: the action list at insertion, then the
     * whole of g_action_group_query_action() for the item's action. The state is converted
     * against the type the group declares for the action, so the type is asked for after it.
     */
    public function testAMenuQueriesTheGroupThroughTheSlots(): void
    {
        $group = new PhpActionGroup();
        $menu = new GMenu();
        $menu->append('Flag', 't.flag');
        $button = new GtkMenuButton();
        $button->insert_action_group('t', $group);

        $button->set_menu_model($menu);

        self::assertSame('list_actions', $group->calls[0]);
        self::assertContains('get_action_enabled(flag)', $group->calls);
        self::assertContains('get_action_parameter_type(flag)', $group->calls);
        self::assertContains('get_action_state(flag)', $group->calls);
        self::assertContains('get_action_state_type(flag)', $group->calls);
        self::assertGreaterThan(
            array_search('get_action_state(flag)', $group->calls, true),
            array_search('get_action_state_type(flag)', $group->calls, true),
        );
    }

    /** A state that does not fit the declared type is a TypeError on the PHP side, NULL for GTK. */
    public function testAStateThatDoesNotFitItsTypeIsATypeError(): void
    {
        $group = new PhpActionGroup();
        $group->actions['flag']['state'] = ['yes'];
        $menu = new GMenu();
        $menu->append('Flag', 't.flag');
        $button = new GtkMenuButton();
        $button->insert_action_group('t', $group);

        $seen = $this->captureHandlerException(fn() => $button->set_menu_model($menu));

        self::assertNotNull($seen);
        self::assertSame('GActionGroup::get_action_state', $seen[1]);
    }

    /**
     * GTK's muxer reads the group's action list the moment it is inserted - the slot the PHP
     * implementation used to lack - and routes the widget's actions to the group afterwards:
     * the parameter arrives converted against the group's own parameter type.
     */
    public function testAWidgetTakesThePhpGroupAndActivatesThroughIt(): void
    {
        $group = new PhpActionGroup();
        $button = new GtkButton();

        $button->insert_action_group('t', $group);

        self::assertTrue($button->activate_action('t.go', null));
        self::assertTrue($button->activate_action('t.say', 'hello'));
        self::assertContains('activate_action(go,NULL)', $group->calls);
        self::assertContains("activate_action(say,'hello')", $group->calls);
    }

    public function testAStateChangeReachesTheSlotTyped(): void
    {
        $group = new PhpActionGroup();

        $group->change_action_state('flag', false);

        self::assertSame(['change_action_state(flag,false)'], $group->calls);
        self::assertFalse($group->get_action_state('flag'));
    }

    /**
     * An unregistered GApplication answers NULL to the same question, with a CRITICAL of its
     * own; the muxer walked that too.
     */
    public function testInsertingAnUnregisteredApplicationIsRefusedNotACrash(): void
    {
        $application = new GtkApplication('org.phpgtk4.InsertActionGroup', 0);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('not registered yet');
        new GtkButton()->insert_action_group('t', $application);
    }

    /** Removing a group is the one call that takes no group, and it always worked. */
    public function testRemovingAGroupIsAccepted(): void
    {
        $button = new GtkButton();

        $button->insert_action_group('t', null);

        self::assertInstanceOf(GtkButton::class, $button);
    }
}
