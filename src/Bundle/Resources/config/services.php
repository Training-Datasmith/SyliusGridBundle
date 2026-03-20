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

use Sylius\Bundle\Grid_Bundle\Command\Debug_Grid_Command;
use Sylius\Bundle\Grid_Bundle\Maker\Make_Grid;
use Sylius\Bundle\Grid_Bundle\Parser\Options_Parser;
use Sylius\Bundle\Grid_Bundle\Parser\Options_Parser_Interface;
use Sylius\Bundle\Grid_Bundle\Provider\Service_Grid_Provider;
use Sylius\Bundle\Grid_Bundle\Registry\Grid_Registry;
use Sylius\Bundle\Grid_Bundle\Registry\Grid_Registry_Interface;
use Sylius\Component\Grid\Configuration\Grid_Configuration_Extender;
use Sylius\Component\Grid\Configuration\Grid_Configuration_Extender_Interface;
use Sylius\Component\Grid\Configuration\Grid_Configuration_Removals_Handler;
use Sylius\Component\Grid\Configuration\Grid_Configuration_Removals_Handler_Interface;
use Sylius\Component\Grid\Configuration\Grid_Configuration_Sorting_Handler;
use Sylius\Component\Grid\Configuration\Grid_Configuration_Sorting_Handler_Interface;
use Sylius\Component\Grid\Data\Data_Provider;
use Sylius\Component\Grid\Data\Data_Provider_Interface;
use Sylius\Component\Grid\Data\Data_Source_Provider;
use Sylius\Component\Grid\Data\Data_Source_Provider_Interface;
use Sylius\Component\Grid\Data\Driver_Interface;
use Sylius\Component\Grid\Data\Provider;
use Sylius\Component\Grid\Data_Extractor\Data_Extractor_Interface;
use Sylius\Component\Grid\Data_Extractor\Property_Access_Data_Extractor;
use Sylius\Component\Grid\Definition\Array_To_Definition_Converter;
use Sylius\Component\Grid\Definition\Array_To_Definition_Converter_Interface;
use Sylius\Component\Grid\Field_Types\Field_Type_Interface;
use Sylius\Component\Grid\Filtering\Filter_Interface;
use Sylius\Component\Grid\Filtering\Filters_Applicator;
use Sylius\Component\Grid\Filtering\Filters_Applicator_Interface;
use Sylius\Component\Grid\Filtering\Filters_Criteria_Resolver;
use Sylius\Component\Grid\Filtering\Filters_Criteria_Resolver_Interface;
use Sylius\Component\Grid\Provider\Array_Grid_Provider;
use Sylius\Component\Grid\Provider\Chain_Provider;
use Sylius\Component\Grid\Provider\Grid_Provider_Interface;
use Sylius\Component\Grid\Sorting\Sorter;
use Sylius\Component\Grid\Sorting\Sorter_Interface;
use Sylius\Component\Grid\Validation\Field_Validator;
use Sylius\Component\Grid\Validation\Field_Validator_Interface;
use Sylius\Component\Grid\Validation\Sorting_Parameters_Validator;
use Sylius\Component\Grid\Validation\Sorting_Parameters_Validator_Interface;
use Sylius\Component\Grid\View\Grid_View_Factory;
use Sylius\Component\Grid\View\Grid_View_Factory_Interface;
use Sylius\Component\Registry\Service_Registry;
return static function (Container_Configurator $container): void {
    $services = $container->services();
    $container->import('services/field_types.php');
    $container->import('services/filters.php');
    $services->defaults()->public();
    $services->set('sylius.grid.data_extractor.property_access', Property_Access_Data_Extractor::class)->args([service('property_accessor')]);
    $services->alias(Property_Access_Data_Extractor::class, 'sylius.grid.data_extractor.property_access');
    $services->alias(Data_Extractor_Interface::class, 'sylius.grid.data_extractor.property_access');
    $services->set('sylius.grid.array_to_definition_converter', Array_To_Definition_Converter::class)->args([service('event_dispatcher')]);
    $services->alias(Array_To_Definition_Converter_Interface::class, 'sylius.grid.array_to_definition_converter');
    $services->set('sylius.grid.grid_registry', Grid_Registry::class)->args([tagged_locator('sylius.grid', indexAttribute: 'name', defaultIndexMethod: 'getName')]);
    $services->alias(Grid_Registry_Interface::class, 'sylius.grid.grid_registry');
    $services->set('sylius.grid.configuration_extender', Grid_Configuration_Extender::class);
    $services->alias(Grid_Configuration_Extender_Interface::class, 'sylius.grid.configuration_extender');
    $services->set('sylius.grid.configuration_removals_handler', Grid_Configuration_Removals_Handler::class);
    $services->alias(Grid_Configuration_Removals_Handler_Interface::class, 'sylius.grid.configuration_removals_handler');
    $services->set('sylius.grid.configuration_sorting_handler', Grid_Configuration_Sorting_Handler::class);
    $services->alias(Grid_Configuration_Sorting_Handler_Interface::class, 'sylius.grid.configuration_sorting_handler');
    $services->alias('sylius.grid.provider', 'sylius.grid.chain_provider');
    $services->alias(Grid_Provider_Interface::class, 'sylius.grid.provider');
    $services->set('sylius.grid.array_grid_provider', Array_Grid_Provider::class)->args([service('sylius.grid.array_to_definition_converter'), '%sylius.grids_definitions%', service('sylius.grid.configuration_extender'), service('sylius.grid.configuration_removals_handler'), service('sylius.grid.configuration_sorting_handler')])->tag('sylius.grid_provider', ['key' => 'array', 'priority' => -200]);
    $services->alias(Array_Grid_Provider::class, 'sylius.grid.array_grid_provider');
    $services->set('sylius.grid.service_grid_provider', Service_Grid_Provider::class)->args([service('sylius.grid.array_to_definition_converter'), service('sylius.grid.grid_registry'), service('sylius.grid.configuration_extender'), service('sylius.grid.configuration_removals_handler'), service('sylius.grid.configuration_sorting_handler')])->tag('sylius.grid_provider', ['key' => 'service', 'priority' => -100]);
    $services->alias(Service_Grid_Provider::class, 'sylius.grid.service_grid_provider');
    $services->set('sylius.grid.chain_provider', Chain_Provider::class)->args([tagged_iterator('sylius.grid_provider')]);
    $services->alias(Chain_Provider::class, 'sylius.grid.chain_provider');
    $services->set('sylius.grid.view_factory', Grid_View_Factory::class)->args([service('sylius.grid.data_provider')]);
    $services->alias(Grid_View_Factory_Interface::class, 'sylius.grid.view_factory');
    $services->set('sylius.grid.data_provider', Data_Provider::class)->args([service('sylius.grid.data_source_provider'), service('sylius.grid.filters_applicator'), service('sylius.grid.sorter')]);
    $services->alias(Data_Provider_Interface::class, 'sylius.grid.data_provider');
    $services->set(Provider::class)->decorate('sylius.grid.data_provider')->args([tagged_locator('sylius.grid_data_provider'), service('.inner')]);
    $services->set('sylius.grid.filters_criteria_resolver', Filters_Criteria_Resolver::class);
    $services->alias(Filters_Criteria_Resolver_Interface::class, 'sylius.grid.filters_criteria_resolver');
    $services->set('sylius.grid.filters_applicator', Filters_Applicator::class)->args([service('sylius.registry.grid_filter'), service('sylius.grid.filters_criteria_resolver')]);
    $services->alias(Filters_Applicator_Interface::class, 'sylius.grid.filters_applicator');
    $services->set('sylius.grid.sorter.validator', Sorting_Parameters_Validator::class);
    $services->alias(Sorting_Parameters_Validator_Interface::class, 'sylius.grid.sorter.validator');
    $services->set('sylius.grid.field.validator', Field_Validator::class);
    $services->alias(Field_Validator_Interface::class, 'sylius.grid.field.validator');
    $services->set('sylius.grid.sorter', Sorter::class)->args([service('sylius.grid.sorter.validator'), service('sylius.grid.field.validator')]);
    $services->alias(Sorter_Interface::class, 'sylius.grid.sorter');
    $services->set(Data_Source_Provider_Interface::class, Data_Source_Provider::class)->args([service('sylius.registry.grid_driver')]);
    $services->alias('sylius.grid.data_source_provider', Data_Source_Provider_Interface::class);
    $services->set('sylius.registry.grid_driver', Service_Registry::class)->args([Driver_Interface::class, 'grid driver']);
    $services->set('sylius.registry.grid_filter', Service_Registry::class)->args([Filter_Interface::class, 'grid filter']);
    $services->set('sylius.registry.grid_field', Service_Registry::class)->args([Field_Type_Interface::class, 'grid field']);
    $services->set('sylius.grid.maker', Make_Grid::class)->args([service('doctrine')->null_on_invalid()])->tag('maker.command');
    $services->alias(Make_Grid::class, 'sylius.grid.maker');
    $services->set('sylius.grid.console.command.grid_debug', Debug_Grid_Command::class)->args([service('sylius.grid.provider'), tagged_locator('sylius.grid'), '%sylius.grids_definitions%'])->tag('console.command');
    $services->set('sylius.grid.options_parser', Options_Parser::class)->private();
    $services->alias(Options_Parser_Interface::class, 'sylius.grid.options_parser')->private();
};