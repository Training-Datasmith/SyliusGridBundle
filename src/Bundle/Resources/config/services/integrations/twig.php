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

use Sylius\Bundle\Grid_Bundle\Field_Types\Twig_Field_Type;
use Sylius\Bundle\Grid_Bundle\Renderer\Twig_Bulk_Action_Grid_Renderer;
use Sylius\Bundle\Grid_Bundle\Renderer\Twig_Grid_Renderer;
use Sylius\Bundle\Grid_Bundle\Twig\Bulk_Action_Grid_Extension;
use Sylius\Bundle\Grid_Bundle\Twig\Grid_Extension;
return static function (Container_Configurator $container): void {
    $services = $container->services();
    $container->import('twig/**');
    $services->defaults()->public();
    $services->set('sylius.grid.renderer.twig', Twig_Grid_Renderer::class)->args([service('twig'), service('sylius.registry.grid_field'), service('form.factory'), service('sylius.form_registry.grid_filter'), '@SyliusGrid/_grid.html.twig', '%sylius.grid.templates.action%', '%sylius.grid.templates.filter%', service('sylius.grid.options_parser')]);
    $services->alias(Twig_Grid_Renderer::class, 'sylius.grid.renderer.twig');
    $services->set('sylius.grid.bulk_action_renderer.twig', Twig_Bulk_Action_Grid_Renderer::class)->args([service('twig'), '%sylius.grid.templates.bulk_action%']);
    $services->alias(Twig_Bulk_Action_Grid_Renderer::class, 'sylius.grid.bulk_action_renderer.twig');
    $services->set('sylius.twig.extension.grid', Grid_Extension::class)->private()->args([service('sylius.templating.helper.grid')])->tag('twig.extension');
    $services->alias(Grid_Extension::class, 'sylius.twig.extension.grid')->private();
    $services->set('sylius.twig.extension.bulk_action_grid', Bulk_Action_Grid_Extension::class)->private()->args([service('sylius.templating.helper.bulk_action_grid')])->tag('twig.extension');
    $services->alias(Bulk_Action_Grid_Extension::class, 'sylius.twig.extension.bulk_action_grid')->private();
    $services->set('sylius.grid_field.twig', Twig_Field_Type::class)->args([service('sylius.grid.data_extractor'), service('twig')])->tag('sylius.grid_field', ['type' => 'twig']);
    $services->alias(Twig_Field_Type::class, 'sylius.grid_field.twig');
};