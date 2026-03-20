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
namespace Sylius\Bundle\Grid_Bundle\Builder;

use Sylius\Bundle\Grid_Bundle\Builder\Action\Action_Interface;
use Sylius\Bundle\Grid_Bundle\Builder\Action_Group\Action_Group;
use Sylius\Bundle\Grid_Bundle\Builder\Action_Group\Action_Group_Interface;
use Sylius\Bundle\Grid_Bundle\Builder\Field\Field_Interface;
use Sylius\Bundle\Grid_Bundle\Builder\Filter\Filter_Interface;
final class Grid_Builder implements Grid_Builder_Interface
{
    private const DEFAULT_DRIVER_NAME = 'doctrine/orm';
    private string $driver;
    /** @var array<string, mixed> */
    private array $driver_configuration = [];
    /** @var string|callable|null */
    private $provider;
    /** @var array<string, FieldInterface> */
    private array $fields = [];
    /** @var array<string, string> */
    private array $sorting = [];
    /** @var array<string, FilterInterface> */
    private array $filters = [];
    /** @var array<string, ActionGroupInterface> */
    private array $action_groups = [];
    /** @var int[] */
    private array $limits = [];
    private ?string $extends = null;
    /**
     * @var array{
     *     fields?: string[],
     *     filters?: string[],
     *     actions?: mixed,
     * }
     */
    private array $removals = [];
    private function __construct(private readonly string $name, ?string $resource_class = null)
    {
        $this->driver = self::DEFAULT_DRIVER_NAME;
        if (null !== $resource_class) {
            $this->driver_configuration['class'] = $resource_class;
        }
    }
    public static function create(string $name, ?string $resource_class = null): Grid_Builder_Interface
    {
        return new self($name, $resource_class);
    }
    public function get_name(): string
    {
        return $this->name;
    }
    public function set_driver(string $driver): Grid_Builder_Interface
    {
        $this->driver = $driver;
        return $this;
    }
    /**
     * @param mixed $value
     */
    public function set_driver_option(string $option, $value): Grid_Builder_Interface
    {
        $this->driver_configuration[$option] = $value;
        return $this;
    }
    public function set_repository_method($method, array $arguments = []): Grid_Builder_Interface
    {
        return $this->set_driver_option('repository', ['method' => $method, 'arguments' => $arguments]);
    }
    public function get_provider(): callable|string|null
    {
        return $this->provider;
    }
    public function set_provider(callable|string|null $provider): Grid_Builder_Interface
    {
        $this->provider = $provider;
        return $this;
    }
    public function add_field(Field_Interface $field): self
    {
        $this->fields[$field->get_name()] = $field;
        return $this;
    }
    public function with_fields(Field_Interface ...$fields): Grid_Builder_Interface
    {
        foreach ($fields as $field) {
            $this->add_field($field);
        }
        return $this;
    }
    public function remove_field(string $name): Grid_Builder_Interface
    {
        unset($this->fields[$name]);
        $this->removals['fields'][] = $name;
        return $this;
    }
    public function order_by(string $name, string $direction = 'asc'): self
    {
        $this->sorting = [$name => $direction];
        return $this;
    }
    public function add_order_by(string $name, string $direction = 'asc'): self
    {
        $this->sorting[$name] = $direction;
        return $this;
    }
    public function add_filter(Filter_Interface $filter): self
    {
        $this->filters[$filter->get_name()] = $filter;
        return $this;
    }
    public function with_filters(Filter_Interface ...$filters): Grid_Builder_Interface
    {
        foreach ($filters as $filter) {
            $this->add_filter($filter);
        }
        return $this;
    }
    public function remove_filter(string $name): Grid_Builder_Interface
    {
        unset($this->filters[$name]);
        $this->removals['filters'][] = $name;
        return $this;
    }
    public function add_action_group(Action_Group_Interface $action_group): self
    {
        $name = $action_group->get_name();
        if (!isset($this->action_groups[$name])) {
            $this->action_groups[$name] = $action_group;
        }
        return $this;
    }
    public function remove_action_group(string $name): self
    {
        unset($this->action_groups[$name]);
        $this->removals['actions'][] = $name;
        return $this;
    }
    public function add_action(Action_Interface $action, string $group): self
    {
        $this->add_action_group(Action_Group::create($group));
        $this->action_groups[$group]->add_action($action);
        return $this;
    }
    public function remove_action(string $name, string $group): self
    {
        $action_group = $this->action_groups[$group] ?? null;
        if ($action_group !== null) {
            $action_group->remove_action($name);
        }
        if (!isset($this->removals['actions'])) {
            $this->removals['actions'] = [];
        }
        if (!is_array($this->removals['actions'])) {
            $this->removals['actions'] = [];
        }
        if (!isset($this->removals['actions'][$group])) {
            $this->removals['actions'][$group] = [];
        }
        $this->removals['actions'][$group][] = $name;
        return $this;
    }
    public function set_limits(array $limits): Grid_Builder_Interface
    {
        $this->limits = $limits;
        return $this;
    }
    public function extends(string $grid_name): Grid_Builder_Interface
    {
        $this->extends = $grid_name;
        return $this;
    }
    public function to_array(): array
    {
        $output = ['driver' => ['name' => $this->driver], 'removals' => $this->removals];
        if (null !== $this->provider) {
            $output['provider'] = $this->provider;
        }
        if (count($this->driver_configuration) > 0) {
            $output['driver']['options'] = $this->driver_configuration;
        }
        if (count($this->fields) > 0) {
            $output['fields'] = array_map(fn(Field_Interface $field) => $field->to_array(), $this->fields);
        }
        if (count($this->sorting) > 0) {
            $output['sorting'] = $this->sorting;
        }
        foreach ($this->filters as $name => $filter) {
            $output['filters'][$name] = $filter->to_array();
        }
        foreach ($this->action_groups as $name => $action_group) {
            $output['actions'][$name] = $action_group->to_array();
        }
        if (count($this->limits) > 0) {
            $output['limits'] = $this->limits;
        }
        if (null !== $this->extends) {
            $output['extends'] = $this->extends;
        }
        return $output;
    }
}