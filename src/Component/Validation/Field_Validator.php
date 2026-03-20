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

use Symfony\Component\Http_Kernel\Exception\Bad_Request_Http_Exception;
final class Field_Validator implements Field_Validator_Interface
{
    public function validate_field_name(string $field_name, array $enabled_fields): void
    {
        $enabled_fields_names = array_keys($enabled_fields);
        if (!in_array($field_name, $enabled_fields_names, true)) {
            throw new Bad_Request_Http_Exception(sprintf('%s is not valid field, did you mean one of these: %s?', $field_name, implode(', ', $enabled_fields_names)));
        }
    }
}