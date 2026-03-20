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
namespace Sylius\Bundle\Grid_Bundle\Grid;

use Sylius\Bundle\Grid_Bundle\Builder\Grid_Builder;
use Sylius\Bundle\Grid_Bundle\Builder\Grid_Builder_Interface;
use Sylius\Component\Grid\Attribute\As_Grid;
use Sylius\Component\Grid\Exception\LogicException;
abstract class Abstract_Grid implements Grid_Interface
{
    public static function get_name(): string
    {
        return self::get_as_grid_attribute()->name ?? static::class;
    }
    public function to_array(): array
    {
        $grid_builder = $this->create_grid_builder();
        $provider = self::get_as_grid_attribute()?->provider;
        if (null !== $provider) {
            $grid_builder->set_provider($provider);
        }
        $build_method = self::get_as_grid_attribute()?->build_method;
        if (null === $build_method && method_exists($this, '__invoke')) {
            $build_method = '__invoke';
        }
        $build_method ??= 'buildGrid';
        if (!method_exists($this, $build_method)) {
            throw new LogicException(sprintf('The configured build method "%s" does not exist.', $build_method));
        }
        $this->{$build_method}($grid_builder);
        return $grid_builder->to_array();
    }
    public function build_grid(Grid_Builder_Interface $grid_builder): void
    {
    }
    private function create_grid_builder(): Grid_Builder_Interface
    {
        $resource_class = self::get_as_grid_attribute()?->resource_class;
        if (null === $resource_class && $this instanceof Resource_Aware_Grid_Interface) {
            $resource_class = $this->get_resource_class();
        }
        return Grid_Builder::create($this::get_name(), $resource_class);
    }
    private static function get_as_grid_attribute(): ?As_Grid
    {
        $reflection = (new \ReflectionClass(static::class))->get_attributes(As_Grid::class)[0] ?? null;
        return $reflection?->new_instance();
    }
}