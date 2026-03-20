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
namespace Sylius\Component\Grid\Sorting;

use Sylius\Component\Grid\Data\Data_Source_Interface;
use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
use Sylius\Component\Grid\Validation\Field_Validator;
use Sylius\Component\Grid\Validation\Field_Validator_Interface;
use Sylius\Component\Grid\Validation\Sorting_Parameters_Validator;
use Sylius\Component\Grid\Validation\Sorting_Parameters_Validator_Interface;
final readonly class Sorter implements Sorter_Interface
{
    public function __construct(private ?Sorting_Parameters_Validator_Interface $sorting_validator = new Sorting_Parameters_Validator(), private ?Field_Validator_Interface $field_validator = new Field_Validator())
    {
    }
    public function sort(Data_Source_Interface $data_source, Grid $grid, Parameters $parameters): void
    {
        $enabled_fields = $grid->get_fields();
        $expression_builder = $data_source->get_expression_builder();
        /** @var array<string, string> $sorting */
        $sorting = $parameters->get('sorting', $grid->get_sorting());
        $this->sorting_validator->validate_sorting_parameters($sorting, $enabled_fields);
        foreach ($sorting as $field => $order) {
            $this->field_validator->validate_field_name($field, $enabled_fields);
            $grid_field = $grid->get_field($field);
            $property = $grid_field->get_sortable();
            if (null !== $property) {
                $expression_builder->add_order_by($property, $order);
            }
        }
    }
}