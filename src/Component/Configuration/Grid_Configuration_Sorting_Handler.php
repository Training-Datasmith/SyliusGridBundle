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

use Webmozart\Assert\Assert;
final class Grid_Configuration_Sorting_Handler implements Grid_Configuration_Sorting_Handler_Interface
{
    /**
     * @param array{
     *        fields?: array<string, array{sortable?: bool}>,
     *        sorting?: array<string, string>,
     * } $gridConfiguration
     */
    public function handle(array $grid_configuration): array
    {
        if (false === isset($grid_configuration['sorting'])) {
            return $grid_configuration;
        }
        foreach ($grid_configuration['sorting'] as $sorting => $order) {
            /** @var array<string, mixed> $fields */
            $fields = $grid_configuration['fields'] ?? [];
            Assert::key_exists($fields, $sorting);
            if (isset($grid_configuration['fields'][$sorting]['sortable'])) {
                continue;
            }
            $grid_configuration['fields'][$sorting]['sortable'] = true;
        }
        return $grid_configuration;
    }
}