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
namespace Sylius\Bundle\Grid_Bundle\Builder\Field;

final class Date_Time_Field
{
    public static function create(string $name, string $format = 'Y-m-d H:i:s', ?string $timezone = null): Field_Interface
    {
        return Field::create($name, 'datetime')->with_options(['format' => $format, 'timezone' => $timezone]);
    }
}