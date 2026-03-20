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

use Sylius\Bundle\Grid_Bundle\Templating\Helper\Bulk_Action_Grid_Helper;
use Sylius\Bundle\Grid_Bundle\Templating\Helper\Grid_Helper;
return static function (Container_Configurator $container): void {
    $services = $container->services();
    $services->defaults()->public();
    $services->set('sylius.templating.helper.grid', Grid_Helper::class)->lazy()->args([service('sylius.grid.renderer')]);
    $services->alias(Grid_Helper::class, 'sylius.templating.helper.grid');
    $services->set('sylius.templating.helper.bulk_action_grid', Bulk_Action_Grid_Helper::class)->lazy()->args([service('sylius.grid.bulk_action_renderer')]);
    $services->alias(Bulk_Action_Grid_Helper::class, 'sylius.templating.helper.bulk_action_grid');
};