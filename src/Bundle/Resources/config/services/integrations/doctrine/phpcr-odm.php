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

use Sylius\Bundle\Grid_Bundle\Doctrine\PHPCRODM\Driver;
return static function (Container_Configurator $container): void {
    $services = $container->services();
    $services->defaults()->public();
    $services->set('sylius.grid_driver.doctrine.phpcrodm', Driver::class)->args([service('doctrine_phpcr.odm.document_manager')->null_on_invalid()])->tag('sylius.grid_driver', ['alias' => 'doctrine/phpcr-odm'])->deprecate('sylius/grid-bundle', '1.3', 'The "%service_id%" service is deprecated since Sylius 1.3. Doctrine MongoDB and PHPCR support will no longer be supported in Sylius 2.0.');
};