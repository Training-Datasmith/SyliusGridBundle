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
namespace Sylius\Component\Grid\Filtering;

use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
interface Filters_Criteria_Resolver_Interface
{
    public function has_criteria(Grid $grid, Parameters $parameters): bool;
    /**
     * @return array<string, mixed>
     */
    public function get_criteria(Grid $grid, Parameters $parameters): array;
}