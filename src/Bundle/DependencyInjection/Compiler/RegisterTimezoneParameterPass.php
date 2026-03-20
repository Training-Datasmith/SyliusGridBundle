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
namespace Sylius\Bundle\Grid_Bundle\Dependency_Injection\Compiler;

use Symfony\Component\Dependency_Injection\Compiler\Compiler_Pass_Interface;
use Symfony\Component\Dependency_Injection\Container_Builder;
final class Register_Timezone_Parameter_Pass implements Compiler_Pass_Interface
{
    public function process(Container_Builder $container): void
    {
        if ($container->has_parameter('sylius_grid.timezone')) {
            return;
        }
        $container->set_parameter('sylius_grid.timezone', null);
    }
}