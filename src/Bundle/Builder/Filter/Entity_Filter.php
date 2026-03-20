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
namespace Sylius\Bundle\Grid_Bundle\Builder\Filter;

final class Entity_Filter
{
    /**
     * @param string[]|null $fields
     */
    public static function create(string $name, string $resource_class, ?bool $multiple = null, ?array $fields = null): Filter_Interface
    {
        $filter = Filter::create($name, 'entity');
        $filter->set_form_options(['class' => $resource_class]);
        if (null !== $fields) {
            $filter->set_options(['fields' => $fields]);
        }
        if (null !== $multiple) {
            $filter->add_form_option('multiple', $multiple);
        }
        return $filter;
    }
}