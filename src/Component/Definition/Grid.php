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

use Webmozart\Assert\Assert;
class Grid
{
    /** @var string|callable|null */
    private $provider;
    /** @var array<string, string> */
    private array $sorting = [];
    /** @var array<int> */
    private array $limits = [];
    /** @var array<string, Field> */
    private array $fields = [];
    /** @var array<string, Filter> */
    private array $filters = [];
    /** @var array<string, ActionGroup> */
    private array $action_groups = [];
    /**
     * @param array<string, mixed> $driverConfiguration
     */
    private function __construct(private readonly string $code, private readonly string $driver, private array $driver_configuration)
    {
    }
    /**
     * @param array<string, mixed> $driverConfiguration
     */
    public static function from_code_and_driver_configuration(string $code, string $driver, array $driver_configuration): self
    {
        return new self($code, $driver, $driver_configuration);
    }
    public function get_code(): string
    {
        return $this->code;
    }
    public function get_driver(): string
    {
        return $this->driver;
    }
    /**
     * @return array<string, mixed>
     */
    public function get_driver_configuration(): array
    {
        return $this->driver_configuration;
    }
    /**
     * @param array<string, mixed> $driverConfiguration
     */
    public function set_driver_configuration(array $driver_configuration): void
    {
        $this->driver_configuration = $driver_configuration;
    }
    public function get_provider(): string|callable|null
    {
        return $this->provider;
    }
    public function set_provider(string|callable|null $provider): void
    {
        $this->provider = $provider;
    }
    /**
     * @return array<string, string>
     */
    public function get_sorting(): array
    {
        return $this->sorting;
    }
    /**
     * @param array<string, string> $sorting
     */
    public function set_sorting(array $sorting): void
    {
        $this->sorting = $sorting;
    }
    /**
     * @return array<int>
     */
    public function get_limits(): array
    {
        return $this->limits;
    }
    /**
     * @param int[] $limits
     */
    public function set_limits(array $limits): void
    {
        $this->limits = $limits;
    }
    /**
     * @return array<string, Field>
     */
    public function get_fields(): array
    {
        return $this->fields;
    }
    /**
     * @return array<string, Field>
     */
    public function get_enabled_fields(): array
    {
        return array_filter($this->get_fields(), fn(Field $field): bool => $field->is_enabled());
    }
    /**
     * @throws \InvalidArgumentException
     */
    public function add_field(Field $field): void
    {
        $name = $field->get_name();
        Assert::false($this->has_field($name), sprintf('Field "%s" already exists.', $name));
        $this->fields[$name] = $field;
    }
    public function remove_field(string $name): void
    {
        if ($this->has_field($name)) {
            unset($this->fields[$name]);
        }
    }
    /**
     * @throws \InvalidArgumentException
     */
    public function get_field(string $name): Field
    {
        Assert::true($this->has_field($name), sprintf('Field "%s" does not exist.', $name));
        return $this->fields[$name];
    }
    public function set_field(Field $field): void
    {
        $name = $field->get_name();
        $this->fields[$name] = $field;
    }
    public function has_field(string $name): bool
    {
        return array_key_exists($name, $this->fields);
    }
    /**
     * @return array<string, ActionGroup>
     */
    public function get_action_groups(): array
    {
        return $this->action_groups;
    }
    /**
     * @return array<string, ActionGroup>
     */
    public function get_enabled_action_groups(): array
    {
        return array_filter(
            $this->get_action_groups(),
            // TODO: There's no `isEnabled` method on ActionGroup, so we assume all of them are enabled
            fn(Action_Group $action_group): bool => true
        );
    }
    /**
     * @throws \InvalidArgumentException
     */
    public function add_action_group(Action_Group $action_group): void
    {
        $name = $action_group->get_name();
        Assert::false($this->has_action_group($name), sprintf('ActionGroup "%s" already exists.', $name));
        $this->action_groups[$name] = $action_group;
    }
    public function remove_action_group(string $name): void
    {
        if ($this->has_action_group($name)) {
            unset($this->action_groups[$name]);
        }
    }
    public function get_action_group(string $name): Action_Group
    {
        Assert::true($this->has_action_group($name), sprintf('ActionGroup "%s" does not exist.', $name));
        return $this->action_groups[$name];
    }
    public function set_action_group(Action_Group $action_group): void
    {
        $name = $action_group->get_name();
        $this->action_groups[$name] = $action_group;
    }
    /**
     * @return Action[]
     */
    public function get_actions(string $group_name): array
    {
        return $this->get_action_group($group_name)->get_actions();
    }
    /**
     * @return Action[]
     */
    public function get_enabled_actions(string $group_name): array
    {
        return array_filter($this->get_actions($group_name), fn(Action $action): bool => $action->is_enabled());
    }
    public function has_action_group(string $name): bool
    {
        return array_key_exists($name, $this->action_groups);
    }
    /**
     * @return array<string, Filter>
     */
    public function get_filters(): array
    {
        return $this->filters;
    }
    /**
     * @return array<string, Filter>
     */
    public function get_enabled_filters(): array
    {
        return array_filter($this->get_filters(), fn(Filter $filter): bool => $filter->is_enabled());
    }
    /**
     * @throws \InvalidArgumentException
     */
    public function add_filter(Filter $filter): void
    {
        $name = $filter->get_name();
        Assert::false($this->has_filter($name), sprintf('Filter "%s" already exists.', $name));
        $this->filters[$name] = $filter;
    }
    public function remove_filter(string $name): void
    {
        if ($this->has_filter($name)) {
            unset($this->filters[$name]);
        }
    }
    public function get_filter(string $name): Filter
    {
        Assert::true($this->has_filter($name), sprintf('Filter "%s" does not exist.', $name));
        return $this->filters[$name];
    }
    public function set_filter(Filter $filter): void
    {
        $name = $filter->get_name();
        $this->filters[$name] = $filter;
    }
    public function has_filter(string $name): bool
    {
        return array_key_exists($name, $this->filters);
    }
}