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

use Sylius\Bundle\Grid_Bundle\Doctrine\DBAL\Driver as DBALDriver;
use Sylius\Bundle\Grid_Bundle\Doctrine\ORM\Driver as ORMDriver;
return static function (Container_Configurator $container): void {
    $services = $container->services();
    $services->defaults()->public();
    $services->set('sylius.grid_driver.doctrine.orm', Orm_Driver::class)->args([service('doctrine')])->tag('sylius.grid_driver', ['alias' => 'doctrine/orm']);
    $services->alias(Orm_Driver::class, 'sylius.grid_driver.doctrine.orm');
    $services->set('sylius.grid_driver.doctrine.dbal', Dbal_Driver::class)->args([service('doctrine.dbal.default_connection')])->tag('sylius.grid_driver', ['alias' => 'doctrine/dbal']);
    $services->alias(Dbal_Driver::class, 'sylius.grid_driver.doctrine.dbal');
};