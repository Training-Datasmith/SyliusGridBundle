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
namespace Sylius\Bundle\Grid_Bundle\Doctrine\PHPCRODM;

use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Common\Collections\Expression_Builder as CollectionsExpressionBuilder;
@trigger_error(sprintf('The "%s" class is deprecated since Sylius 1.3. Doctrine MongoDB and PHPCR support will no longer be supported in Sylius 2.0.', Expression_Builder::class), \E_USER_DEPRECATED);
/**
 * Creates an object graph (using Doctrine\Commons\Collections\Expr\*) which we
 * can then walk in order to build up the PHPCR-ODM query builder.
 */
final class Expression_Builder implements Expression_Builder_Interface
{
    private readonly Collections_Expression_Builder $expression_builder;
    private array $order_bys = [];
    public function __construct(?Collections_Expression_Builder $expression_builder = null)
    {
        $this->expression_builder = $expression_builder ?: new Collections_Expression_Builder();
    }
    public function and_x(...$expressions)
    {
        return $this->expression_builder->and_x(...$expressions);
    }
    public function or_x(...$expressions)
    {
        return $this->expression_builder->or_x(...$expressions);
    }
    public function comparison(string $field, string $operator, $value)
    {
        return new Comparison($field, $operator, $value);
    }
    public function equals(string $field, $value)
    {
        return $this->expression_builder->eq($field, $value);
    }
    public function not_equals(string $field, $value)
    {
        return $this->expression_builder->neq($field, $value);
    }
    public function less_than(string $field, $value)
    {
        return $this->expression_builder->lt($field, $value);
    }
    public function less_than_or_equal(string $field, $value)
    {
        return $this->expression_builder->lte($field, $value);
    }
    public function greater_than(string $field, $value)
    {
        return $this->expression_builder->gt($field, $value);
    }
    public function greater_than_or_equal(string $field, $value)
    {
        return $this->expression_builder->gte($field, $value);
    }
    public function member_of($value, string $field)
    {
        return $this->expression_builder->member_of($value, $field);
    }
    public function in(string $field, array $values)
    {
        return $this->expression_builder->in($field, $values);
    }
    public function not_in(string $field, array $values)
    {
        return $this->expression_builder->not_in($field, $values);
    }
    public function is_null(string $field)
    {
        return new Comparison($field, Extra_Comparison::IS_NULL, null);
    }
    public function is_not_null(string $field)
    {
        return new Comparison($field, Extra_Comparison::IS_NOT_NULL, null);
    }
    public function like(string $field, string $pattern)
    {
        return $this->expression_builder->contains($field, $pattern);
    }
    public function not_like(string $field, string $pattern)
    {
        return new Comparison($field, Extra_Comparison::NOT_CONTAINS, $pattern);
    }
    public function order_by(string $field, string $direction): void
    {
        $this->order_bys = [$field => $direction];
    }
    public function add_order_by(string $field, string $direction): void
    {
        $this->order_bys[$field] = $direction;
    }
    public function get_order_bys(): array
    {
        return $this->order_bys;
    }
}