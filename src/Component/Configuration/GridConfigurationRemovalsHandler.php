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

final class Grid_Configuration_Removals_Handler implements Grid_Configuration_Removals_Handler_Interface
{
    public function handle(array $grid_configuration): array
    {
        if (false === isset($grid_configuration['removals'])) {
            return $grid_configuration;
        }
        /** @var array<string, mixed> $removals */
        $removals = $grid_configuration['removals'];
        $this->handle_removals($grid_configuration, $removals);
        unset($grid_configuration['removals']);
        return $grid_configuration;
    }
    /**
     * @param array<string, mixed> $gridConfiguration
     * @param array<string, mixed> $removals
     */
    private function handle_removals(array &$grid_configuration, array $removals): void
    {
        foreach ($removals as $type => $name) {
            if (!is_array($name)) {
                unset($grid_configuration[$name]);
                continue;
            }
            if (isset($grid_configuration[$type])) {
                /** @var array<string, mixed> $subConfiguration */
                $sub_configuration = $grid_configuration[$type];
                /** @var array<string, mixed> $name */
                $this->handle_removals($sub_configuration, $name);
                $grid_configuration[$type] = $sub_configuration;
            }
        }
    }
}