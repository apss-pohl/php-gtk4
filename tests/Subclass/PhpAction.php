<?php

declare(strict_types=1);

namespace PhpGtk4\Tests\Subclass;

use Gtk4\GAction;
use Gtk4\GObject;

/**
 * A GAction implemented in PHP: every slot of the interface is bound, so a GActionMap takes it
 * like a GSimpleAction - GLib asks it for its name, its types and its state, activates it and
 * asks it to change state. The interface's five properties (`name`, `state`, ...) are answered
 * by the getters of the same name; on the PHP side they are the ordinary properties below.
 * Drives tests/PhpActionTest.php.
 */
final class PhpAction extends GObject implements GAction
{
    /** @var list<array{string, mixed}> slot name and argument, in call order */
    public array $calls = [];

    public function __construct(
        public string $name = 'act',
        public ?string $parameter_type = null,
        public ?string $state_type = null,
        public mixed $state = null,
        public mixed $state_hint = null,
        public bool $enabled = true,
    ) {
        parent::__construct();
    }

    public function activate(mixed $parameter = null): void
    {
        $this->calls[] = ['activate', $parameter];
    }

    public function change_state(mixed $value = null): void
    {
        $this->calls[] = ['change_state', $value];
        $this->state = $value;
    }

    public function get_enabled(): bool
    {
        return $this->enabled;
    }

    public function get_name(): string
    {
        return $this->name;
    }

    public function get_parameter_type(): ?string
    {
        return $this->parameter_type;
    }

    public function get_state(): mixed
    {
        return $this->state;
    }

    public function get_state_hint(): mixed
    {
        return $this->state_hint;
    }

    public function get_state_type(): ?string
    {
        return $this->state_type;
    }
}
