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
namespace Sylius\Bundle\Grid_Bundle\Builder\Action_Group;

use Sylius\Bundle\Grid_Bundle\Builder\Action\Action_Interface;
final class Action_Group implements Action_Group_Interface
{
    /** @var array<string, ActionInterface> */
    private array $actions = [];
    private function __construct(private readonly string $name)
    {
    }
    public static function create(string $name, Action_Interface ...$actions): Action_Group_Interface
    {
        $action_group = new self($name);
        foreach ($actions as $action) {
            $action_group->add_action($action);
        }
        return $action_group;
    }
    public function get_name(): string
    {
        return $this->name;
    }
    public function add_action(Action_Interface $action): Action_Group_Interface
    {
        $this->actions[$action->get_name()] = $action;
        return $this;
    }
    public function remove_action(string $name): Action_Group_Interface
    {
        unset($this->actions[$name]);
        return $this;
    }
    public function to_array(): array
    {
        if (count($this->actions) <= 0) {
            return [];
        }
        $output = [];
        foreach ($this->actions as $name => $action) {
            $output[$name] = $action->to_array();
        }
        return $output;
    }
}