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
final class Sorting_Parameters_Validator implements Sorting_Parameters_Validator_Interface
{
    public function validate_sorting_parameters(array $sorting, array $enabled_fields): void
    {
        foreach (array_keys($enabled_fields) as $key) {
            if (array_key_exists($key, $sorting) && !in_array($sorting[$key], ['asc', 'desc'])) {
                throw new Bad_Request_Http_Exception(sprintf('%s is not valid, use asc or desc instead.', $sorting[$key]));
            }
        }
    }
}