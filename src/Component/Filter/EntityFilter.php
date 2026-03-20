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
final class Entity_Filter implements Filter_Interface
{
    public function apply(Data_Source_Interface $data_source, string $name, $data, array $options): void
    {
        if (empty($data)) {
            return;
        }
        $values = is_array($data) ? $data : [$data];
        /** @var string[] $fields */
        $fields = $options['fields'] ?? [$name];
        $expression_builder = $data_source->get_expression_builder();
        $expressions = [];
        foreach ($fields as $field) {
            foreach ($values as $value) {
                $expressions[] = $expression_builder->equals($field, $value);
            }
        }
        $data_source->restrict($expression_builder->or_x(...$expressions));
    }
}