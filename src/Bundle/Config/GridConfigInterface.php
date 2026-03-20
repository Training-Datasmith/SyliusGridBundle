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
namespace Sylius\Bundle\Grid_Bundle\Config;

use Sylius\Bundle\Grid_Bundle\Builder\Grid_Builder_Interface;
use Symfony\Component\Config\Builder\Config_Builder_Interface;
/**
 * @psalm-suppress UnrecognizedStatement
 * @psalm-suppress UndefinedClass
 */
if (interface_exists(Config_Builder_Interface::class)) {
    interface Grid_Config_Interface extends Config_Builder_Interface
    {
        public function add_grid(Grid_Builder_Interface $grid_builder): self;
    }
} else {
    interface Grid_Config_Interface
    {
        public function add_grid(Grid_Builder_Interface $grid_builder): self;
        /**
         * Gets all configuration represented as an array.
         *
         * @return array<string, mixed>
         */
        public function to_array(): array;
        /**
         * Gets the alias for the extension which config we are building.
         */
        public function get_extension_alias(): string;
    }
}