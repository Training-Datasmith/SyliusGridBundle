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

interface Grid_Configuration_Extender_Interface
{
    /**
     * @param array<string, mixed> $gridConfiguration
     * @param array<string, mixed> $parentGridConfiguration
     *
     * @return array<string, mixed>
     */
    public function extends(array $grid_configuration, array $parent_grid_configuration): array;
}