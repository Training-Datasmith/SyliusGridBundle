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
namespace Sylius\Component\Grid\Filter;

use Sylius\Component\Grid\Data\Data_Source_Interface;
use Sylius\Component\Grid\Filtering\Filter_Interface;
final class Select_Filter implements Filter_Interface
{
    public function apply(Data_Source_Interface $data_source, string $name, $data, array $options): void
    {
        if (empty($data)) {
            return;
        }
        /** @var string $field */
        $field = $options['field'] ?? $name;
        if (is_array($data)) {
            $data_source->restrict($data_source->get_expression_builder()->in($field, $data));
            return;
        }
        $data_source->restrict($data_source->get_expression_builder()->equals($field, $data));
    }
}