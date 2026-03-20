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

use Sylius\Component\Grid\Data\Data_Source_Interface;
use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
use Sylius\Component\Registry\Service_Registry_Interface;
final readonly class Filters_Applicator implements Filters_Applicator_Interface
{
    private Service_Registry_Interface $filters_registry;
    public function __construct(Service_Registry_Interface $filters_registry, private Filters_Criteria_Resolver_Interface $criteria_resolver)
    {
        $this->filters_registry = $filters_registry;
    }
    public function apply(Data_Source_Interface $data_source, Grid $grid, Parameters $parameters): void
    {
        if (!$this->criteria_resolver->has_criteria($grid, $parameters)) {
            return;
        }
        $criteria = $this->criteria_resolver->get_criteria($grid, $parameters);
        foreach ($criteria as $name => $data) {
            if (!$grid->has_filter($name)) {
                continue;
            }
            $grid_filter = $grid->get_filter($name);
            /** @var FilterInterface $filter */
            $filter = $this->filters_registry->get($grid_filter->get_type());
            $filter->apply($data_source, $name, $data, $grid_filter->get_options());
        }
    }
}