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
namespace Sylius\Component\Grid\Field_Types;

use Sylius\Component\Grid\Definition\Field;
use Symfony\Component\Options_Resolver\Options_Resolver;
interface Field_Type_Interface
{
    /**
     * Return a HTML representation of the $field using the given $data and
     * $options.
     *
     * @param array<string, mixed> $options
     *
     * @return string
     */
    public function render(Field $field, mixed $data, array $options);
    /**
     * Configure options for this field type.
     */
    public function configure_options(Options_Resolver $resolver): void;
}