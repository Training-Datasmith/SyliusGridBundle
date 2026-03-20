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
namespace Sylius\Bundle\Grid_Bundle\Dependency_Injection;

use Sylius\Bundle\Grid_Bundle\Doctrine\ORM\Driver as DoctrineORMDriver;
use Sylius\Bundle\Grid_Bundle\Sylius_Grid_Bundle;
use Symfony\Component\Config\Definition\Builder\Array_Node_Definition;
use Symfony\Component\Config\Definition\Builder\Tree_Builder;
use Symfony\Component\Config\Definition\Configuration_Interface;
final class Configuration implements Configuration_Interface
{
    /**
     * @return TreeBuilder<'array'>
     */
    public function get_config_tree_builder(): Tree_Builder
    {
        $tree_builder = new Tree_Builder('sylius_grid');
        /** @var ArrayNodeDefinition<TreeBuilder<'array'>> $rootNode */
        $root_node = $tree_builder->get_root_node();
        $this->add_drivers_section($root_node);
        $this->add_templates_section($root_node);
        $this->add_grids_section($root_node);
        return $tree_builder;
    }
    /**
     * @param ArrayNodeDefinition<TreeBuilder<'array'>> $node
     */
    private function add_drivers_section(Array_Node_Definition $node): void
    {
        $node->children()->array_node('drivers')->set_deprecated('sylius/grid-bundle', '1.15', 'Drivers config is deprecated and will be removed in 2.0, register drivers using tagged service "sylius.grid_driver" instead.')->default_value([])->enum_prototype()->values(Sylius_Grid_Bundle::get_available_drivers())->end()->end()->end();
    }
    /**
     * @param ArrayNodeDefinition<TreeBuilder<'array'>> $node
     */
    private function add_templates_section(Array_Node_Definition $node): void
    {
        $node->children()->array_node('templates')->add_defaults_if_not_set()->children()->array_node('filter')->use_attribute_as_key('name')->scalar_prototype()->end()->end()->array_node('action')->use_attribute_as_key('name')->scalar_prototype()->end()->end()->array_node('bulk_action')->use_attribute_as_key('name')->scalar_prototype()->end()->end()->end()->end()->end();
    }
    /**
     * @param ArrayNodeDefinition<TreeBuilder<'array'>> $node
     */
    private function add_grids_section(Array_Node_Definition $node): void
    {
        $node->children()->array_node('grids')->use_attribute_as_key('code')->array_prototype()->children()->scalar_node('extends')->cannot_be_empty()->end()->scalar_node('provider')->cannot_be_empty()->end()->array_node('driver')->add_defaults_if_not_set()->children()->scalar_node('name')->cannot_be_empty()->default_value(Doctrine_Orm_Driver::NAME)->end()->array_node('options')->perform_no_deep_merging()->variable_prototype()->end()->default_value([])->end()->end()->end()->array_node('sorting')->perform_no_deep_merging()->use_attribute_as_key('name')->enum_prototype()->values(['asc', 'desc'])->cannot_be_empty()->end()->end()->array_node('limits')->perform_no_deep_merging()->integer_prototype()->end()->default_value([10, 25, 50])->end()->array_node('fields')->use_attribute_as_key('name')->array_prototype()->children()->scalar_node('type')->is_required()->cannot_be_empty()->end()->scalar_node('label')->cannot_be_empty()->end()->scalar_node('path')->cannot_be_empty()->end()->scalar_node('sortable')->end()->scalar_node('enabled')->default_true()->end()->scalar_node('position')->default_value(100)->end()->array_node('options')->perform_no_deep_merging()->variable_prototype()->end()->end()->end()->end()->end()->array_node('filters')->use_attribute_as_key('name')->array_prototype()->children()->scalar_node('type')->is_required()->cannot_be_empty()->end()->scalar_node('label')->cannot_be_empty()->end()->scalar_node('enabled')->default_true()->end()->scalar_node('template')->end()->scalar_node('position')->default_value(100)->end()->array_node('options')->perform_no_deep_merging()->variable_prototype()->end()->end()->array_node('form_options')->perform_no_deep_merging()->variable_prototype()->end()->end()->variable_node('default_value')->end()->end()->end()->end()->array_node('actions')->use_attribute_as_key('name')->array_prototype()->use_attribute_as_key('name')->array_prototype()->children()->scalar_node('type')->is_required()->end()->scalar_node('label')->end()->scalar_node('enabled')->default_true()->end()->scalar_node('template')->end()->scalar_node('icon')->end()->scalar_node('position')->default_value(100)->end()->array_node('options')->perform_no_deep_merging()->variable_prototype()->end()->end()->end()->end()->end()->end()->array_node('removals')->ignore_extra_keys(false)->end()->end()->end()->end()->end();
    }
}