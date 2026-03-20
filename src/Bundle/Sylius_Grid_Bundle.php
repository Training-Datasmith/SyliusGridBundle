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
namespace Sylius\Bundle\Grid_Bundle;

use Sylius\Bundle\Grid_Bundle\Dependency_Injection\Compiler\Register_Drivers_Pass;
use Sylius\Bundle\Grid_Bundle\Dependency_Injection\Compiler\Register_Field_Types_Pass;
use Sylius\Bundle\Grid_Bundle\Dependency_Injection\Compiler\Register_Filters_Pass;
use Sylius\Bundle\Grid_Bundle\Dependency_Injection\Compiler\Register_Stub_Commands_Pass;
use Sylius\Bundle\Grid_Bundle\Dependency_Injection\Compiler\Register_Timezone_Parameter_Pass;
use Sylius\Bundle\Grid_Bundle\Dependency_Injection\Compiler\Validate_Configured_Grid_Drivers_Pass;
use Symfony\Component\Dependency_Injection\Container_Builder;
use Symfony\Component\Http_Kernel\Bundle\Bundle;
final class Sylius_Grid_Bundle extends Bundle
{
    public const DRIVER_DOCTRINE_ORM = 'doctrine/orm';
    public const DRIVER_DOCTRINE_PHPCR_ODM = 'doctrine/phpcr-odm';
    public function build(Container_Builder $container): void
    {
        parent::build($container);
        $container->add_compiler_pass(new Register_Drivers_Pass());
        $container->add_compiler_pass(new Register_Filters_Pass());
        $container->add_compiler_pass(new Register_Field_Types_Pass());
        $container->add_compiler_pass(new Register_Stub_Commands_Pass());
        $container->add_compiler_pass(new Register_Timezone_Parameter_Pass());
        $container->add_compiler_pass(new Validate_Configured_Grid_Drivers_Pass());
    }
    /**
     * @return string[]
     */
    public static function get_available_drivers(): array
    {
        return [self::DRIVER_DOCTRINE_ORM, self::DRIVER_DOCTRINE_PHPCR_ODM];
    }
}