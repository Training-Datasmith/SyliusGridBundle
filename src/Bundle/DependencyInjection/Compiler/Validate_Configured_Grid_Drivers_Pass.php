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
namespace Sylius\Bundle\Grid_Bundle\Dependency_Injection\Compiler;

use Symfony\Component\Dependency_Injection\Compiler\Compiler_Pass_Interface;
use Symfony\Component\Dependency_Injection\Container_Builder;
final class Validate_Configured_Grid_Drivers_Pass implements Compiler_Pass_Interface
{
    public function process(Container_Builder $container): void
    {
        if (!$container->has_parameter('sylius.grids_definitions')) {
            return;
        }
        $available_drivers = $this->get_available_drivers($container);
        /** @var array<string, array{driver?: array{name?: string|false|null}}> $gridsDefinitions */
        $grids_definitions = $container->get_parameter('sylius.grids_definitions');
        $this->validate_grid_drivers($grids_definitions, $available_drivers);
    }
    /**
     * @return string[]
     */
    private function get_available_drivers(Container_Builder $container): array
    {
        /** @var array<string> $drivers */
        $drivers = [];
        foreach ($container->find_tagged_service_ids('sylius.grid_driver') as $attributes) {
            foreach ($attributes as $attribute) {
                if (!is_array($attribute)) {
                    continue;
                }
                $alias = $attribute['alias'] ?? null;
                if (!is_string($alias)) {
                    continue;
                }
                $drivers[] = $alias;
            }
        }
        return $drivers;
    }
    /**
     * @param array<string, array{driver?: array{name?: string|false|null}}> $gridsDefinitions
     * @param array<string> $availableDrivers
     */
    private function validate_grid_drivers(array $grids_definitions, array $available_drivers): void
    {
        foreach ($grids_definitions as $grid_name => $grid_definition) {
            $driver_name = null;
            $driver = $grid_definition['driver'] ?? [];
            $driver_name = $driver['name'] ?? null;
            if (!is_string($driver_name)) {
                continue;
            }
            if (in_array($driver_name, $available_drivers, true)) {
                continue;
            }
            throw new \InvalidArgumentException(sprintf('Grid "%s" uses driver "%s" which is not registered. Available drivers are: %s', $grid_name, $driver_name, empty($available_drivers) ? 'none' : implode(', ', $available_drivers)));
        }
    }
}