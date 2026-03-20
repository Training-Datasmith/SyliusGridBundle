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
namespace Sylius\Component\Grid\Data;

interface Member_Of_Aware_Expression_Builder_Interface extends Expression_Builder_Interface
{
    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function member_of($value, string $field);
}