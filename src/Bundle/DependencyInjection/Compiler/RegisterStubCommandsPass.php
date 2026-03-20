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

use Sylius\Bundle\Grid_Bundle\Command\Stub_Make_Grid;
use Sylius\Bundle\Grid_Bundle\Maker\Make_Grid;
use Symfony\Bundle\Maker_Bundle\Maker_Bundle;
use Symfony\Component\Dependency_Injection\Compiler\Compiler_Pass_Interface;
use Symfony\Component\Dependency_Injection\Container_Builder;
final class Register_Stub_Commands_Pass implements Compiler_Pass_Interface
{
    public function process(Container_Builder $container): void
    {
        if (!$this->is_maker_enabled($container)) {
            $container->register(Stub_Make_Grid::class)->set_class(Stub_Make_Grid::class)->add_tag('console.command');
            $container->remove_definition('sylius.grid.maker');
            $container->remove_alias(Make_Grid::class);
        }
    }
    private function is_maker_enabled(Container_Builder $container): bool
    {
        if (!class_exists(Maker_Bundle::class)) {
            return false;
        }
        /** @var array<string, class-string> $bundles */
        $bundles = $container->get_parameter('kernel.bundles');
        return in_array(Maker_Bundle::class, $bundles, true);
    }
}