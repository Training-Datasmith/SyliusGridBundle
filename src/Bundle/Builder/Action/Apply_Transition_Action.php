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
namespace Sylius\Bundle\Grid_Bundle\Builder\Action;

final class Apply_Transition_Action
{
    /**
     * @param array<string, mixed> $routeParameters
     * @param array<string, mixed> $options
     */
    public static function create(string $name, string $route, array $route_parameters = [], array $options = []): Action_Interface
    {
        $action = Action::create($name, 'apply_transition');
        $options = array_merge(['link' => ['route' => $route, 'parameters' => $route_parameters], 'transition' => $name], $options);
        $action->set_options($options);
        return $action;
    }
}