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
use Symfony\Component\Dependency_Injection\Reference;
final class Register_Field_Types_Pass implements Compiler_Pass_Interface
{
    public function process(Container_Builder $container): void
    {
        if (!$container->has_definition('sylius.registry.grid_field')) {
            return;
        }
        $registry = $container->get_definition('sylius.registry.grid_field');
        /** @var array<string, array<string, string>> $attributes */
        foreach ($container->find_tagged_service_ids('sylius.grid_field') as $id => $attributes) {
            foreach ($attributes as $attribute) {
                if (!isset($attribute['type'])) {
                    throw new \InvalidArgumentException('Tagged grid fields needs to have `type` attribute.');
                }
                $registry->add_method_call('register', [$attribute['type'], new Reference($id)]);
            }
        }
    }
}