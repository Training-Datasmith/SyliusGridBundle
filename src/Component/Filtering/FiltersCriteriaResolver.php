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

use Sylius\Component\Grid\Definition\Filter;
use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
final class Filters_Criteria_Resolver implements Filters_Criteria_Resolver_Interface
{
    public function has_criteria(Grid $grid, Parameters $parameters): bool
    {
        if ($parameters->has('criteria')) {
            return true;
        }
        return !empty($this->get_filters_default_criteria($grid->get_filters()));
    }
    public function get_criteria(Grid $grid, Parameters $parameters): array
    {
        $default_criteria = array_map(fn(Filter $filter) => $filter->get_criteria(), $this->get_filters_default_criteria($grid->get_filters()));
        /** @var array<string, mixed> $criteria */
        $criteria = $parameters->get('criteria', $default_criteria);
        return $criteria;
    }
    /**
     * @param Filter[] $filters
     *
     * @return Filter[]
     */
    private function get_filters_default_criteria(array $filters): array
    {
        return array_filter($filters, fn(Filter $filter) => null !== $filter->get_criteria());
    }
}