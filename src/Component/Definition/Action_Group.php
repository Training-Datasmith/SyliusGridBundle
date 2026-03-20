<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare (strict_types=1);
namespace Sylius\Component\Grid\Definition;

class Action_Group
{
    /** @var array<string, Action> */
    private array $actions = [];
    private function __construct(private readonly string $name)
    {
    }
    public static function named(string $name): self
    {
        return new self($name);
    }
    public function get_name(): string
    {
        return $this->name;
    }
    /**
     * @return array<string, Action>
     */
    public function get_actions(): array
    {
        return $this->actions;
    }
    /**
     * @throws \InvalidArgumentException
     */
    public function add_action(Action $action): void
    {
        if ($this->has_action($name = $action->get_name())) {
            throw new \InvalidArgumentException(sprintf('Action "%s" already exists.', $name));
        }
        $this->actions[$name] = $action;
    }
    public function get_action(string $name): Action
    {
        if (!$this->has_action($name)) {
            throw new \InvalidArgumentException(sprintf('Action "%s" does not exist.', $name));
        }
        return $this->actions[$name];
    }
    public function has_action(string $name): bool
    {
        return isset($this->actions[$name]);
    }
}