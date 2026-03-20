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
namespace Sylius\Bundle\Grid_Bundle\Doctrine\DBAL;

use Doctrine\DBAL\Query\Expression\Composite_Expression;
use Doctrine\DBAL\Query\Query_Builder;
use Sylius\Component\Grid\Data\Expression_Builder_Interface;
final readonly class Expression_Builder implements Expression_Builder_Interface
{
    private Query_Builder $query_builder;
    public function __construct(Query_Builder $query_builder)
    {
        $this->query_builder = $query_builder;
    }
    /**
     * @param CompositeExpression|string ...$expressions
     */
    public function and_x(...$expressions)
    {
        return $this->query_builder->expr()->and(...$expressions);
    }
    /**
     * @param CompositeExpression|string ...$expressions
     */
    public function or_x(...$expressions)
    {
        return $this->query_builder->expr()->or(...$expressions);
    }
    /**
     * @param string $value
     */
    public function comparison(string $field, string $operator, $value)
    {
        return $this->query_builder->expr()->comparison($field, $operator, $value);
    }
    public function equals(string $field, $value)
    {
        $this->query_builder->set_parameter($field, $value);
        return $this->query_builder->expr()->eq($field, ':' . $field);
    }
    public function not_equals(string $field, $value)
    {
        $this->query_builder->set_parameter($field, $value);
        return $this->query_builder->expr()->neq($field, ':' . $field);
    }
    public function less_than(string $field, $value)
    {
        $this->query_builder->set_parameter($field, $value);
        return $this->query_builder->expr()->lt($field, ':' . $field);
    }
    public function less_than_or_equal(string $field, $value)
    {
        $this->query_builder->set_parameter($field, $value);
        return $this->query_builder->expr()->lte($field, ':' . $field);
    }
    public function greater_than(string $field, $value)
    {
        $this->query_builder->set_parameter($field, $value);
        return $this->query_builder->expr()->gt($field, ':' . $field);
    }
    public function greater_than_or_equal(string $field, $value)
    {
        $this->query_builder->set_parameter($field, $value);
        return $this->query_builder->expr()->gte($field, ':' . $field);
    }
    /**
     * @param string[] $values
     */
    public function in(string $field, array $values)
    {
        return $this->query_builder->expr()->in($field, $values);
    }
    /**
     * @param string[] $values
     */
    public function not_in(string $field, array $values)
    {
        return $this->query_builder->expr()->not_in($field, $values);
    }
    public function is_null(string $field)
    {
        return $this->query_builder->expr()->is_null($field);
    }
    public function is_not_null(string $field)
    {
        return $this->query_builder->expr()->is_not_null($field);
    }
    public function like(string $field, string $pattern)
    {
        return $this->query_builder->expr()->like($field, $this->query_builder->expr()->literal($pattern));
    }
    public function not_like(string $field, string $pattern)
    {
        return $this->query_builder->expr()->not_like($field, $this->query_builder->expr()->literal($pattern));
    }
    public function order_by(string $field, string $direction)
    {
        return $this->query_builder->order_by($field, $direction);
    }
    public function add_order_by(string $field, string $direction)
    {
        return $this->query_builder->add_order_by($field, $direction);
    }
}