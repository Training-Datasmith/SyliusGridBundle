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
namespace Sylius\Bundle\Grid_Bundle\Parser;

interface Options_Parser_Interface
{
    /**
     * @param array<string, mixed> $parameters
     *
     * @return array<string, mixed>
     */
    public function parse_options(array $parameters): array;
}