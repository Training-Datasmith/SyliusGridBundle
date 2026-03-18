<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Component\Grid\Sorting;

use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
use Sylius\Component\Grid\Validation\FieldValidator;
use Sylius\Component\Grid\Validation\FieldValidatorInterface;
use Sylius\Component\Grid\Validation\SortingParametersValidator;
use Sylius\Component\Grid\Validation\SortingParametersValidatorInterface;

final readonly class Sorter implements SorterInterface
{
    public function __construct(private ?SortingParametersValidatorInterface $sortingValidator = new SortingParametersValidator(), private ?FieldValidatorInterface $fieldValidator = new FieldValidator())
    {
    }

    public function sort(DataSourceInterface $dataSource, Grid $grid, Parameters $parameters): void
    {
        $enabledFields = $grid->getFields();

        $expressionBuilder = $dataSource->getExpressionBuilder();

        /** @var array<string, string> $sorting */
        $sorting = $parameters->get('sorting', $grid->getSorting());
        $this->sortingValidator->validateSortingParameters($sorting, $enabledFields);

        foreach ($sorting as $field => $order) {
            $this->fieldValidator->validateFieldName($field, $enabledFields);
            $gridField = $grid->getField($field);
            $property = $gridField->getSortable();

            if (null !== $property) {
                $expressionBuilder->addOrderBy($property, $order);
            }
        }
    }
}
