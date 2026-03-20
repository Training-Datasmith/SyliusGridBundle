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
namespace Sylius\Component\Grid\Data;

use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Filtering\Filters_Applicator_Interface;
use Sylius\Component\Grid\Parameters;
use Sylius\Component\Grid\Sorting\Sorter_Interface;
final readonly class Data_Provider implements Data_Provider_Interface
{
    public function __construct(private Data_Source_Provider_Interface $data_source_provider, private Filters_Applicator_Interface $filters_applicator, private Sorter_Interface $sorter)
    {
    }
    public function get_data(Grid $grid, Parameters $parameters)
    {
        $data_source = $this->data_source_provider->get_data_source($grid, $parameters);
        $this->filters_applicator->apply($data_source, $grid, $parameters);
        $this->sorter->sort($data_source, $grid, $parameters);
        return $data_source->get_data($parameters);
    }
}