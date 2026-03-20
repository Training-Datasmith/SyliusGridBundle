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

use Sylius\Bundle\Grid_Bundle\Builder\Grid_Builder_Interface;
interface Grid_Interface
{
    public static function get_name(): string;
    /**
     * @return array<string, mixed>
     */
    public function to_array(): array;
    public function build_grid(Grid_Builder_Interface $grid_builder): void;
}