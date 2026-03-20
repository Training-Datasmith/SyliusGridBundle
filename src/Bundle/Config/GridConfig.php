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
namespace Sylius\Bundle\Grid_Bundle\Config;

use Sylius\Bundle\Grid_Bundle\Builder\Grid_Builder_Interface;
final class Grid_Config implements Grid_Config_Interface
{
    /** @var array<string, GridBuilderInterface> */
    private array $grids = [];
    public function add_grid(Grid_Builder_Interface $grid_builder): Grid_Config_Interface
    {
        $this->grids[$grid_builder->get_name()] = $grid_builder;
        return $this;
    }
    /**
     * @return array<string, mixed>
     */
    public function to_array(): array
    {
        if (count($this->grids) <= 0) {
            return [];
        }
        $output = ['grids' => []];
        foreach ($this->grids as $name => $grid_builder) {
            $output['grids'][$name] = $grid_builder->to_array();
        }
        return $output;
    }
    public function get_extension_alias(): string
    {
        return 'sylius_grid';
    }
}