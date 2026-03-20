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

use Sylius\Component\Grid\Field_Types\Callable_Field_Type;
use Sylius\Component\Grid\Field_Types\Datetime_Field_Type;
use Sylius\Component\Grid\Field_Types\Enum_Field_Type;
use Sylius\Component\Grid\Field_Types\String_Field_Type;
return static function (Container_Configurator $container): void {
    $services = $container->services();
    $services->defaults()->public();
    $services->set('sylius.grid_field.callable', Callable_Field_Type::class)->args([service('sylius.grid.data_extractor'), tagged_locator('sylius.grid_field_callable_service')])->tag('sylius.grid_field', ['type' => 'callable']);
    $services->alias(Callable_Field_Type::class, 'sylius.grid_field.callable');
    $services->set('sylius.grid_field.datetime', Datetime_Field_Type::class)->args([service('sylius.grid.data_extractor'), '%sylius_grid.timezone%'])->tag('sylius.grid_field', ['type' => 'datetime']);
    $services->alias(Datetime_Field_Type::class, 'sylius.grid_field.datetime');
    $services->set('sylius.grid_field.string', String_Field_Type::class)->args([service('sylius.grid.data_extractor')])->tag('sylius.grid_field', ['type' => 'string']);
    $services->alias(String_Field_Type::class, 'sylius.grid_field.string');
    $services->set('sylius.grid_field.enum', Enum_Field_Type::class)->args([service('sylius.grid.data_extractor'), service('translator')->null_on_invalid()])->tag('sylius.grid_field', ['type' => 'enum']);
    $services->alias(Enum_Field_Type::class, 'sylius.grid_field.enum');
};