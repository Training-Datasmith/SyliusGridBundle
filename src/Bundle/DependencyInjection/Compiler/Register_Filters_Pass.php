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

use Sylius\Component\Grid\Attribute\As_Filter;
use Sylius\Component\Grid\Filtering\Form_Type_Aware_Filter_Interface;
use Sylius\Component\Grid\Filtering\Type_Aware_Filter_Interface;
use Symfony\Component\Dependency_Injection\Compiler\Compiler_Pass_Interface;
use Symfony\Component\Dependency_Injection\Container_Builder;
use Symfony\Component\Dependency_Injection\Definition;
use Symfony\Component\Dependency_Injection\Reference;
final class Register_Filters_Pass implements Compiler_Pass_Interface
{
    public function process(Container_Builder $container): void
    {
        if (!$container->has_definition('sylius.registry.grid_filter') || !$container->has_definition('sylius.form_registry.grid_filter')) {
            return;
        }
        $filter_registry = $container->get_definition('sylius.registry.grid_filter');
        $form_type_registry = $container->get_definition('sylius.form_registry.grid_filter');
        foreach ($container->find_tagged_service_ids(As_Filter::SERVICE_TAG) as $id => $attributes) {
            $definition = $container->get_definition($id);
            $class = $definition->get_class();
            $type = null;
            $form_type = null;
            if ($class !== null && is_a($class, Type_Aware_Filter_Interface::class, true)) {
                $type = $class::get_type();
            }
            if ($class !== null && is_a($class, Form_Type_Aware_Filter_Interface::class, true)) {
                $form_type = $class::get_form_type();
            }
            /** @var array<string, mixed> $attributes */
            $this->register_filter($container, $filter_registry, $form_type_registry, $id, $attributes, $type, $form_type);
        }
    }
    /**
     * @param array<string, mixed> $attributes
     */
    private function register_filter(Container_Builder $container, Definition $filter_registry, Definition $form_type_registry, string $id, array $attributes, ?string $type = null, ?string $form_type = null): void
    {
        /** @var array<string, mixed> $attribute */
        foreach ($attributes as $attribute) {
            /** @var string|null $template */
            $template = $attribute['template'] ?? null;
            /** @var string|null $filterType */
            $filter_type = $type ?? $attribute['type'] ?? null;
            $filter_form_type = $form_type ?? $attribute['form_type'] ?? null;
            if (null === $filter_type) {
                throw new \InvalidArgumentException(sprintf('Tagged grid filters needs to have "type" attribute or implements "%s".', Type_Aware_Filter_Interface::class));
            }
            if (null === $filter_form_type) {
                throw new \InvalidArgumentException(sprintf('Tagged grid filters needs to have "form_type" attribute or implements "%s".', Form_Type_Aware_Filter_Interface::class));
            }
            $filter_registry->add_method_call('register', [$filter_type, new Reference($id)]);
            $form_type_registry->add_method_call('add', [$filter_type, 'default', $filter_form_type]);
            if (null !== $template) {
                $this->register_filter_template($container, $filter_type, $template);
            }
        }
    }
    private function register_filter_template(Container_Builder $container, string $filter_type, string $template): void
    {
        /** @var array<string, string> $filtersConfig */
        $filters_config = $container->has_parameter('sylius.grid.templates.filter') ? $container->get_parameter('sylius.grid.templates.filter') : [];
        $filters_config[$filter_type] = $template;
        $container->set_parameter('sylius.grid.templates.filter', $filters_config);
    }
}