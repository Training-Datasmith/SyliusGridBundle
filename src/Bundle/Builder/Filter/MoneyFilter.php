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

use Sylius\Component\Grid\Filter\Money_Filter as GridMoneyFilter;
final class Money_Filter
{
    public static function create(string $name, string $currency_code, ?int $scale = null): Filter_Interface
    {
        $filter = Filter::create($name, 'money');
        $scale ??= Grid_Money_Filter::DEFAULT_SCALE;
        $filter->set_form_options(['scale' => $scale]);
        $filter->set_options(['currency_field' => $currency_code, 'scale' => $scale]);
        return $filter;
    }
}