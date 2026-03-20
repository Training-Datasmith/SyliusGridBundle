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

use Sylius\Bundle\Grid_Bundle\Form\Registry\Form_Type_Registry;
use Sylius\Bundle\Grid_Bundle\Form\Type\Filter\Boolean_Filter_Type;
use Sylius\Bundle\Grid_Bundle\Form\Type\Filter\Date_Filter_Type;
use Sylius\Bundle\Grid_Bundle\Form\Type\Filter\Entity_Filter_Type;
use Sylius\Bundle\Grid_Bundle\Form\Type\Filter\Enum_Filter_Type;
use Sylius\Bundle\Grid_Bundle\Form\Type\Filter\Exists_Filter_Type;
use Sylius\Bundle\Grid_Bundle\Form\Type\Filter\Numeric_Range_Filter_Type;
use Sylius\Bundle\Grid_Bundle\Form\Type\Filter\Select_Filter_Type;
use Sylius\Bundle\Grid_Bundle\Form\Type\Filter\String_Filter_Type;
use Sylius\Bundle\Grid_Bundle\Storage\Filter_Storage_Interface;
use Sylius\Bundle\Grid_Bundle\Storage\Session_Filter_Storage;
use Sylius\Component\Grid\Filter\Boolean_Filter;
use Sylius\Component\Grid\Filter\Date_Filter;
use Sylius\Component\Grid\Filter\Entity_Filter;
use Sylius\Component\Grid\Filter\Exists_Filter;
use Sylius\Component\Grid\Filter\Numeric_Range_Filter;
use Sylius\Component\Grid\Filter\Select_Filter;
use Sylius\Component\Grid\Filter\String_Filter;
return static function (Container_Configurator $container): void {
    $services = $container->services();
    $services->defaults()->public();
    $services->set('sylius.grid.filter_storage.session', Session_Filter_Storage::class)->private()->args([service('request_stack')]);
    $services->alias('sylius.grid.filter_storage', 'sylius.grid.filter_storage.session');
    $services->alias(Filter_Storage_Interface::class, 'sylius.grid.filter_storage');
    $services->set('sylius.form_registry.grid_filter', Form_Type_Registry::class)->private();
    $services->set('sylius.grid_filter.string', String_Filter::class)->tag('sylius.grid_filter', ['type' => 'string', 'form_type' => String_Filter_Type::class]);
    $services->alias(String_Filter::class, 'sylius.grid_filter.string');
    $services->set(String_Filter_Type::class)->tag('form.type');
    $services->alias('sylius.form.type.grid_filter.string', String_Filter_Type::class);
    $services->set('sylius.grid_filter.boolean', Boolean_Filter::class)->tag('sylius.grid_filter', ['type' => 'boolean', 'form_type' => Boolean_Filter_Type::class]);
    $services->alias(Boolean_Filter::class, 'sylius.grid_filter.boolean');
    $services->set('sylius.form.type.grid_filter.boolean', Boolean_Filter_Type::class)->tag('form.type');
    $services->alias(Boolean_Filter_Type::class, 'sylius.form.type.grid_filter.boolean');
    $services->set('sylius.grid_filter.date', Date_Filter::class)->tag('sylius.grid_filter', ['type' => 'date', 'form_type' => Date_Filter_Type::class]);
    $services->alias(Date_Filter::class, 'sylius.grid_filter.date');
    $services->set('sylius.form.type.grid_filter.date', Date_Filter_Type::class)->tag('form.type');
    $services->alias(Date_Filter_Type::class, 'sylius.form.type.grid_filter.date');
    $services->set('sylius.grid_filter.entity', Entity_Filter::class)->tag('sylius.grid_filter', ['type' => 'entity', 'form_type' => Entity_Filter_Type::class]);
    $services->alias(Entity_Filter::class, 'sylius.grid_filter.entity');
    $services->set('sylius.form.type.grid_filter.entity', Entity_Filter_Type::class)->tag('form.type');
    $services->alias(Entity_Filter_Type::class, 'sylius.form.type.grid_filter.entity');
    $services->set('sylius.grid_filter.exists', Exists_Filter::class)->tag('sylius.grid_filter', ['type' => 'exists', 'form_type' => Exists_Filter_Type::class]);
    $services->alias(Exists_Filter::class, 'sylius.grid_filter.exists');
    $services->set('sylius.form.type.grid_filter.exists', Exists_Filter_Type::class)->tag('form.type');
    $services->alias(Exists_Filter_Type::class, 'sylius.form.type.grid_filter.exists');
    $services->set(Numeric_Range_Filter::class)->tag('sylius.grid_filter', ['type' => 'numeric_range', 'form_type' => Numeric_Range_Filter_Type::class]);
    $services->alias('sylius.grid_filter.numeric_range', Numeric_Range_Filter::class);
    $services->set('sylius.form.type.grid_filter.numeric_range', Numeric_Range_Filter_Type::class)->tag('form.type');
    $services->alias(Numeric_Range_Filter_Type::class, 'sylius.form.type.grid_filter.numeric_range');
    $services->set('sylius.grid_filter.select', Select_Filter::class)->tag('sylius.grid_filter', ['type' => 'select', 'form_type' => Select_Filter_Type::class]);
    $services->alias(Select_Filter::class, 'sylius.grid_filter.select');
    $services->set('sylius.form.type.grid_filter.select', Select_Filter_Type::class)->tag('form.type');
    $services->alias(Select_Filter_Type::class, 'sylius.form.type.grid_filter.select');
    $services->set('sylius.grid_filter.enum', Select_Filter::class)->tag('sylius.grid_filter', ['type' => 'enum', 'form_type' => Enum_Filter_Type::class]);
    $services->set('sylius.form.type.grid_filter.enum', Enum_Filter_Type::class)->tag('form.type');
    $services->alias(Enum_Filter_Type::class, 'sylius.form.type.grid_filter.enum');
};