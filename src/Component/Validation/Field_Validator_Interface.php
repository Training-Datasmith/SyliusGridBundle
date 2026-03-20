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
namespace Sylius\Component\Grid\Validation;

use Sylius\Component\Grid\Definition\Field;
interface Field_Validator_Interface
{
    /**
     * @param array<string, Field> $enabledFields
     */
    public function validate_field_name(string $field_name, array $enabled_fields): void;
}