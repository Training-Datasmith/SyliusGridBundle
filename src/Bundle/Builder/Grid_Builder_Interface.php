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
use Sylius\Bundle\Grid_Bundle\Builder\Action_Group\Action_Group_Interface;
use Sylius\Bundle\Grid_Bundle\Builder\Field\Field_Interface;
use Sylius\Bundle\Grid_Bundle\Builder\Filter\Filter_Interface;
/**
 * @method string|callable|null getProvider()
 * @method GridBuilderInterface setProvider(string|callable|null $provider)
 * @method GridBuilderInterface withFields(FieldInterface ...$fields)
 * @method GridBuilderInterface withFilters(FilterInterface ...$filters)
 *
 * @psalm-method string|callable|null getProvider()
 * @psalm-method GridBuilderInterface setProvider(string|callable|null $provider)
 */
interface Grid_Builder_Interface
{
    public static function create(string $name, ?string $resource_class = null): self;
    public function get_name(): string;
    public function set_driver(string $driver): self;
    /**
     * @param mixed $value
     */
    public function set_driver_option(string $option, $value): self;
    /**
     * @param string|callable|mixed[] $method
     * @param mixed[] $arguments
     */
    public function set_repository_method($method, array $arguments = []): self;
    public function add_field(Field_Interface $field): self;
    public function remove_field(string $name): self;
    public function order_by(string $name, string $direction): self;
    public function add_order_by(string $name, string $direction = 'asc'): self;
    /**
     * @param int[] $limits
     */
    public function set_limits(array $limits): self;
    public function add_filter(Filter_Interface $filter): self;
    public function remove_filter(string $name): self;
    public function add_action_group(Action_Group_Interface $action_group): self;
    public function remove_action_group(string $name): self;
    public function add_action(Action_Interface $action, string $group): self;
    public function remove_action(string $name, string $group): self;
    public function extends(string $grid_name): self;
    /**
     * @return array<string, mixed>
     */
    public function to_array(): array;
}