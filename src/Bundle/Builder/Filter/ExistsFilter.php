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

final class Exists_Filter
{
    public static function create(string $name, ?string $field = null): Filter_Interface
    {
        $filter = Filter::create($name, 'exists');
        if (null !== $field) {
            $filter->set_options(['field' => $field]);
        }
        return $filter;
    }
}