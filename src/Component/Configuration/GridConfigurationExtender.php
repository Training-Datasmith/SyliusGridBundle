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
namespace Sylius\Component\Grid\Configuration;

final class Grid_Configuration_Extender implements Grid_Configuration_Extender_Interface
{
    public function extends(array $grid_configuration, array $parent_grid_configuration): array
    {
        unset($parent_grid_configuration['sorting']);
        // Do not inherit sorting.
        /** @var array<string, mixed> $configuration */
        $configuration = array_replace_recursive($parent_grid_configuration, $grid_configuration) ?: [];
        unset($configuration['extends']);
        return $configuration;
    }
}