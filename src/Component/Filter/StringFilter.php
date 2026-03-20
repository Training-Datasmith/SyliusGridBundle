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
use Sylius\Component\Grid\Data\Expression_Builder_Interface;
use Sylius\Component\Grid\Data\Member_Of_Aware_Expression_Builder_Interface;
use Sylius\Component\Grid\Filtering\Filter_Interface;
final class String_Filter implements Filter_Interface
{
    public const NAME = 'string';
    public const TYPE_EQUAL = 'equal';
    public const TYPE_NOT_EQUAL = 'not_equal';
    public const TYPE_EMPTY = 'empty';
    public const TYPE_NOT_EMPTY = 'not_empty';
    public const TYPE_CONTAINS = 'contains';
    public const TYPE_NOT_CONTAINS = 'not_contains';
    public const TYPE_STARTS_WITH = 'starts_with';
    public const TYPE_ENDS_WITH = 'ends_with';
    public const TYPE_MEMBER_OF = 'member_of';
    public const TYPE_IN = 'in';
    public const TYPE_NOT_IN = 'not_in';
    /**
     * @param array{
     *     type?: string,
     *     value?: string,
     * }|string|null $data
     */
    public function apply(Data_Source_Interface $data_source, string $name, $data, array $options): void
    {
        $expression_builder = $data_source->get_expression_builder();
        $value = is_array($data) ? $data['value'] ?? null : $data;
        /** @var string $type */
        $type = $data['type'] ?? $options['type'] ?? self::TYPE_CONTAINS;
        /** @var string[] $fields */
        $fields = $options['fields'] ?? [$name];
        if (!in_array($type, [self::TYPE_NOT_EMPTY, self::TYPE_EMPTY], true) && '' === trim((string) $value)) {
            return;
        }
        if (1 === count($fields)) {
            $data_source->restrict($this->get_expression($expression_builder, $type, current($fields), $value));
            return;
        }
        $expressions = [];
        foreach ($fields as $field) {
            $expressions[] = $this->get_expression($expression_builder, $type, $field, $value);
        }
        if (self::TYPE_NOT_EQUAL === $type) {
            $data_source->restrict($expression_builder->and_x(...$expressions));
            return;
        }
        $data_source->restrict($expression_builder->or_x(...$expressions));
    }
    /**
     * @param string|null $value
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    private function get_expression(Expression_Builder_Interface|Member_Of_Aware_Expression_Builder_Interface $expression_builder, string $type, string $field, $value)
    {
        switch ($type) {
            case self::TYPE_EQUAL:
                return $expression_builder->equals($field, $value);
            case self::TYPE_NOT_EQUAL:
                return $expression_builder->not_equals($field, $value);
            case self::TYPE_EMPTY:
                return $expression_builder->is_null($field);
            case self::TYPE_NOT_EMPTY:
                return $expression_builder->is_not_null($field);
            case self::TYPE_CONTAINS:
                return $expression_builder->like($field, '%' . $value . '%');
            case self::TYPE_NOT_CONTAINS:
                return $expression_builder->not_like($field, '%' . $value . '%');
            case self::TYPE_STARTS_WITH:
                return $expression_builder->like($field, $value . '%');
            case self::TYPE_ENDS_WITH:
                return $expression_builder->like($field, '%' . $value);
            case self::TYPE_IN:
                return $expression_builder->in($field, array_map(trim(...), explode(',', (string) $value)));
            case self::TYPE_NOT_IN:
                return $expression_builder->not_in($field, array_map(trim(...), explode(',', (string) $value)));
            case self::TYPE_MEMBER_OF:
                if (method_exists($expression_builder, 'memberOf')) {
                    return $expression_builder->member_of($value, $field);
                }
                throw new \InvalidArgumentException(sprintf('The memberOf method is not supported by %s', $expression_builder::class));
            default:
                throw new \InvalidArgumentException(sprintf('Could not get an expression for type "%s"!', $type));
        }
    }
}