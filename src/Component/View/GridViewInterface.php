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
namespace Sylius\Component\Grid\View;

use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
interface Grid_View_Interface
{
    /**
     * @return mixed
     */
    public function get_data();
    public function get_definition(): Grid;
    public function get_parameters(): Parameters;
    public function get_sorting_order(string $field_name): ?string;
    public function is_sorted_by(string $field_name): bool;
}