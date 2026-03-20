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

use Doctrine\Bundle\Doctrine_Bundle\Doctrine_Bundle;
use Doctrine\Bundle\Phpcr_Bundle\Doctrine_Phpcr_Bundle;
use Sylius\Bundle\Currency_Bundle\Sylius_Currency_Bundle;
use Sylius\Bundle\Grid_Bundle\Grid\Grid_Interface;
use Sylius\Bundle\Grid_Bundle\Sylius_Grid_Bundle;
use Sylius\Component\Grid\Annotation\As_Grid_Field_Callable_Service;
use Sylius\Component\Grid\Attribute\As_Field;
use Sylius\Component\Grid\Attribute\As_Filter;
use Sylius\Component\Grid\Data\Data_Provider_Interface;
use Sylius\Component\Grid\Filtering\Configurable_Filter_Interface;
use Symfony\Component\Config\File_Locator;
use Symfony\Component\Dependency_Injection\Child_Definition;
use Symfony\Component\Dependency_Injection\Container_Builder;
use Symfony\Component\Dependency_Injection\Extension\Extension;
use Symfony\Component\Dependency_Injection\Loader\Php_File_Loader;
use Twig\Environment;
final class Sylius_Grid_Extension extends Extension
{
    public function load(array $configs, Container_Builder $container): void
    {
        $config = $this->process_configuration($this->get_configuration([], $container), $configs);
        $loader = new Php_File_Loader($container, new File_Locator(__DIR__ . '/../Resources/config'));
        $loader->load('services.php');
        /**
         * @var array{
         *     'filter': array<string, string>,
         *     'action': array<string, string>,
         *     'bulk_action': array<string, string>,
         * } $templates
         */
        $templates = $config['templates'];
        /** @var array<string, mixed> $gridsDefinitions */
        $grids_definitions = $config['grids'];
        $container->set_parameter('sylius.grid.templates.action', $templates['action']);
        $container->set_parameter('sylius.grid.templates.bulk_action', $templates['bulk_action']);
        $container->set_parameter('sylius.grid.templates.filter', $templates['filter']);
        $container->set_parameter('sylius.grids_definitions', $grids_definitions);
        $container->set_alias('sylius.grid.renderer', 'sylius.grid.renderer.twig');
        $container->set_alias('sylius.grid.bulk_action_renderer', 'sylius.grid.bulk_action_renderer.twig');
        $container->set_alias('sylius.grid.data_extractor', 'sylius.grid.data_extractor.property_access');
        if ($container::will_be_available('twig/twig', Environment::class, ['symfony/twig-bundle'])) {
            $loader->load('services/integrations/twig.php');
        }
        if (\class_exists(Sylius_Currency_Bundle::class)) {
            $loader->load('services/integrations/sylius_currency_bundle.php');
        }
        if (\class_exists(Doctrine_Bundle::class)) {
            $loader->load('services/integrations/doctrine/orm.php');
        }
        if (\class_exists(Doctrine_Phpcr_Bundle::class)) {
            @trigger_error(sprintf('The "%s" driver is deprecated in Sylius 1.3. Doctrine PHPCR will no longer be supported in Sylius 2.0.', Sylius_Grid_Bundle::DRIVER_DOCTRINE_PHPCR_ODM), \E_USER_DEPRECATED);
            $loader->load('services/integrations/doctrine/phpcr-odm.php');
        }
        $container->register_for_autoconfiguration(Grid_Interface::class)->add_tag('sylius.grid');
        $container->register_attribute_for_autoconfiguration(As_Filter::class, static function (Child_Definition $definition, As_Filter $attribute, \ReflectionClass $reflector): void {
            $definition->add_tag(As_Filter::SERVICE_TAG, ['type' => $attribute->type ?? $reflector->get_name(), 'form_type' => $attribute->form_type, 'template' => $attribute->template]);
        });
        $container->register_attribute_for_autoconfiguration(As_Field::class, static function (Child_Definition $definition, As_Field $attribute, \ReflectionClass $reflector): void {
            $definition->add_tag(As_Field::SERVICE_TAG, ['type' => $attribute->type ?? $reflector->get_name()]);
        });
        $container->register_for_autoconfiguration(Configurable_Filter_Interface::class)->add_tag(As_Filter::SERVICE_TAG);
        $container->register_for_autoconfiguration(Data_Provider_Interface::class)->add_tag('sylius.grid_data_provider');
        $container->register_attribute_for_autoconfiguration(As_Grid_Field_Callable_Service::class, static function (Child_Definition $definition, As_Grid_Field_Callable_Service $attribute, \Reflector $reflector): void {
            $definition->add_tag('sylius.grid_field_callable_service');
        });
    }
    /**
     * @param array<int, mixed> $config
     */
    public function get_configuration(array $config, Container_Builder $container): Configuration
    {
        $configuration = new Configuration();
        $container->add_object_resource($configuration);
        return $configuration;
    }
}