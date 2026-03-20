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
namespace Symfony\Component\Dependency_Injection\Loader\Configurator;

use Sylius\Bundle\Grid_Bundle\Form\Type\Filter\Money_Filter_Type;
use Sylius\Component\Grid\Filter\Money_Filter;
return static function (Container_Configurator $container): void {
    $services = $container->services();
    $services->defaults()->public();
    $services->set('sylius.grid_filter.money', Money_Filter::class)->tag('sylius.grid_filter', ['type' => 'money', 'form_type' => Money_Filter_Type::class]);
    $services->alias(Money_Filter::class, 'sylius.grid_filter.money');
    $services->set('sylius.form.type.grid_filter.money', Money_Filter_Type::class)->tag('form.type');
    $services->alias(Money_Filter_Type::class, 'sylius.form.type.grid_filter.money');
};