<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GActionGroup;
use Gtk4\GtkApplication;
use Gtk4\GtkButton;
use PhpGtk4\Tests\Subclass\PhpActionGroup;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * A GActionGroup implemented in PHP, and the one thing GTK does with an action group that used
 * to end the process: GtkWidget::insert_action_group() reads the group's action list straight
 * away and walks the result without checking it, so a group that cannot answer was a SIGSEGV
 * inside the action muxer.
 */
final class PhpActionGroupTest extends GtkTestCase
{
    public function testThePhpObjectIsAnActionGroup(): void
    {
        $group = new PhpActionGroup();

        self::assertInstanceOf(GActionGroup::class, $group);
    }

    /**
     * The three change signals php-gtk4 routes into a PHP implementation. `action-state-changed`
     * is deliberately absent: no slot is bound for it, so GTK never asks PHP about it.
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

    /**
     * php-gtk4 binds no `list_actions` slot for a PHP implementation, so the interface's
     * function pointer stays NULL and GTK's muxer called it. Refused now.
     */
    public function testInsertingAPhpGroupIntoAWidgetIsRefusedNotACrash(): void
    {
        $group = new PhpActionGroup();

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('cannot list its actions');
        new GtkButton()->insert_action_group('t', $group);
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
