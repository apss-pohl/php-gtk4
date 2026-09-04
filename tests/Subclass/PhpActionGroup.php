<?php

declare(strict_types=1);

namespace PhpGtk4\Tests\Subclass;

use Gtk4\GActionGroup;
use Gtk4\GObject;

/**
 * A GActionGroup implemented in PHP. php-gtk4 binds five of the interface's slots, so GTK can
 * announce changes to the group and ask whether an action exists and is enabled - it cannot ask
 * this object to *list* its actions, which is why GtkWidget::insert_action_group() refuses it.
 * Drives tests/PhpActionGroupTest.php.
 */
final class PhpActionGroup extends GObject implements GActionGroup
{
    /** @var list<string> slot names in call order */
    public array $calls = [];

    /** @var array<string, bool> action name => enabled */
    public array $actions = ['go' => true];

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
        $this->calls[] = "action_state_changed($action_name)";
    }

    public function activate_action(string $action_name, mixed $parameter = null): void
    {
        $this->calls[] = "activate_action($action_name)";
    }

    public function change_action_state(string $action_name, mixed $value = null): void
    {
        $this->calls[] = "change_action_state($action_name)";
    }

    public function get_action_enabled(string $action_name): bool
    {
        $this->calls[] = "get_action_enabled($action_name)";

        return $this->actions[$action_name] ?? false;
    }

    public function get_action_parameter_type(string $action_name): ?string
    {
        return null;
    }

    public function get_action_state(string $action_name): mixed
    {
        return null;
    }

    public function get_action_state_hint(string $action_name): mixed
    {
        return null;
    }

    public function get_action_state_type(string $action_name): ?string
    {
        return null;
    }

    public function has_action(string $action_name): bool
    {
        $this->calls[] = "has_action($action_name)";

        return isset($this->actions[$action_name]);
    }

    /** @return list<string> */
    public function list_actions(): array
    {
        return array_keys($this->actions);
    }
}
