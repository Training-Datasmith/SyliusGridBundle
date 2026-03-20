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
namespace Sylius\Component\Grid\Filtering;

use Sylius\Component\Grid\Data\Data_Source_Interface;
interface Filter_Interface
{
    /**
     * @param mixed $data
     * @param array<string, mixed> $options
     */
    public function apply(Data_Source_Interface $data_source, string $name, $data, array $options): void;
}