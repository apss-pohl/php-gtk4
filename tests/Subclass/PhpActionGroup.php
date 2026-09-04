<?php

declare(strict_types=1);

namespace PhpGtk4\Tests\Subclass;

use Gtk4\GActionGroup;
use Gtk4\GObject;

/**
 * A GActionGroup implemented in PHP. Every slot of the interface is bound, so GTK can list the
 * group's actions, ask for their types and state, activate them and announce changes - a
 * widget takes it through GtkWidget::insert_action_group() like any group. Actions are the
 * rows of $actions: enabled, parameter type, state type, state.
 * Drives tests/PhpActionGroupTest.php.
 */
final class PhpActionGroup extends GObject implements GActionGroup
{
    /** @var list<string> slot names in call order */
    public array $calls = [];

    /** @var array<string, array{enabled: bool, parameter: ?string, state_type: ?string, state: mixed}> */
    public array $actions = [
        'go' => ['enabled' => true, 'parameter' => null, 'state_type' => null, 'state' => null],
        'say' => ['enabled' => true, 'parameter' => 's', 'state_type' => null, 'state' => null],
        'flag' => ['enabled' => true, 'parameter' => null, 'state_type' => 'b', 'state' => true],
    ];

    public function action_added(string $action_name): void
    {
        $this->calls[] = "action_added($action_name)";
    }

    public function action_enabled_changed(string $action_name, bool $enabled): void
    {
        $this->calls[] = "action_enabled_changed($action_name," . ($enabled ? 'true' : 'false') . ')';
    }

    public function action_removed(string $action_name): void
    {
        $this->calls[] = "action_removed($action_name)";
    }

    public function action_state_changed(string $action_name, mixed $state = null): void
    {
        $this->calls[] = "action_state_changed($action_name," . var_export($state, true) . ')';
    }

    public function activate_action(string $action_name, mixed $parameter = null): void
    {
        $this->calls[] = "activate_action($action_name," . var_export($parameter, true) . ')';
    }

    public function change_action_state(string $action_name, mixed $value = null): void
    {
        $this->calls[] = "change_action_state($action_name," . var_export($value, true) . ')';
        $this->actions[$action_name]['state'] = $value;
    }

    public function get_action_enabled(string $action_name): bool
    {
        $this->calls[] = "get_action_enabled($action_name)";

        return $this->actions[$action_name]['enabled'] ?? false;
    }

    public function get_action_parameter_type(string $action_name): ?string
    {
        $this->calls[] = "get_action_parameter_type($action_name)";

        return $this->actions[$action_name]['parameter'] ?? null;
    }

    public function get_action_state(string $action_name): mixed
    {
        $this->calls[] = "get_action_state($action_name)";

        return $this->actions[$action_name]['state'] ?? null;
    }

    public function get_action_state_hint(string $action_name): mixed
    {
        $this->calls[] = "get_action_state_hint($action_name)";

        return null;
    }

    public function get_action_state_type(string $action_name): ?string
    {
        $this->calls[] = "get_action_state_type($action_name)";

        return $this->actions[$action_name]['state_type'] ?? null;
    }

    public function has_action(string $action_name): bool
    {
        $this->calls[] = "has_action($action_name)";

        return isset($this->actions[$action_name]);
    }

    /** @return list<string> */
    public function list_actions(): array
    {
        $this->calls[] = 'list_actions';

        return array_keys($this->actions);
    }
}
