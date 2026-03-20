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
use Doctrine\Common\Collections\Expr\Composite_Expression;
use Doctrine\Common\Collections\Expr\Expression;
use Doctrine\ODM\PHPCR\Query\Builder\Abstract_Node;
use Doctrine\ODM\PHPCR\Query\Builder\Query_Builder;
@trigger_error(sprintf('The "%s" class is deprecated since Sylius 1.3. Doctrine MongoDB and PHPCR support will no longer be supported in Sylius 2.0.', Expression_Visitor::class), \E_USER_DEPRECATED);
/**
 * Walks a Doctrine\Commons\Expr object graph and builds up a PHPCR-ODM
 * query using the (fluent) PHPCR-ODM query builder.
 */
final readonly class Expression_Visitor
{
    private Query_Builder $query_builder;
    public function __construct(Query_Builder $query_builder)
    {
        $this->query_builder = $query_builder;
    }
    /**
     * @throws \RuntimeException
     */
    public function walk_comparison(Comparison $comparison, Abstract_Node $parent_node)
    {
        $field = $comparison->get_field();
        $value = $comparison->get_value()->get_value();
        // shortcut for walkValue()
        switch ($comparison->get_operator()) {
            case Comparison::EQ:
                return $parent_node->eq()->field($this->get_field($field))->literal($value)->end();
            case Comparison::NEQ:
                return $parent_node->neq()->field($this->get_field($field))->literal($value)->end();
            case Comparison::LT:
                return $parent_node->lt()->field($this->get_field($field))->literal($value)->end();
            case Comparison::LTE:
                return $parent_node->lte()->field($this->get_field($field))->literal($value)->end();
            case Comparison::GT:
                return $parent_node->gt()->field($this->get_field($field))->literal($value)->end();
            case Comparison::GTE:
                return $parent_node->gte()->field($this->get_field($field))->literal($value)->end();
            case Comparison::IN:
                return $this->get_in_constraint($parent_node, $field, $value);
            case Comparison::NIN:
                $node = $parent_node->not();
                $this->get_in_constraint($node, $field, $value);
                return $node->end();
            case Comparison::CONTAINS:
                return $parent_node->like()->field($this->get_field($field))->literal($value)->end();
            case Extra_Comparison::NOT_CONTAINS:
                return $parent_node->not()->like()->field($this->get_field($field))->literal($value)->end()->end();
            case Extra_Comparison::IS_NULL:
                return $parent_node->not()->field_isset($this->get_field($field))->end();
            case Extra_Comparison::IS_NOT_NULL:
                return $parent_node->field_isset($this->get_field($field));
        }
        throw new \RuntimeException('Unknown comparison operator: ' . $comparison->get_operator());
    }
    /**
     * @throws \RuntimeException
     */
    public function walk_composite_expression(Composite_Expression $expr, Abstract_Node $parent_node)
    {
        $node = match ($expr->get_type()) {
            Composite_Expression::TYPE_AND => $parent_node->and_x(),
            Composite_Expression::TYPE_OR => $parent_node->or_x(),
            default => throw new \RuntimeException('Unknown composite: ' . $expr->get_type()),
        };
        $expressions = $expr->get_expression_list();
        $left_expression = array_shift($expressions);
        $this->dispatch($left_expression, $node);
        $parent_node = $node;
        foreach ($expressions as $index => $expression) {
            if (count($expressions) === $index + 1) {
                $this->dispatch($expression, $parent_node);
                break;
            }
            switch ($expr->get_type()) {
                case Composite_Expression::TYPE_AND:
                    $parent_node = $parent_node->and_x();
                    break;
                case Composite_Expression::TYPE_OR:
                    $parent_node = $parent_node->or_x();
                    break;
            }
            $this->dispatch($expression, $parent_node);
        }
        return $node;
    }
    /**
     * Walk the given expression to build up the PHPCR-ODM query builder.
     *
     *
     *
     * @throws \RuntimeException
     */
    public function dispatch(Expression $expr, ?Abstract_Node $parent_node = null)
    {
        if ($parent_node === null) {
            $parent_node = $this->query_builder->where();
        }
        return match (true) {
            $expr instanceof Comparison => $this->walk_comparison($expr, $parent_node),
            $expr instanceof Composite_Expression => $this->walk_composite_expression($expr, $parent_node),
            default => throw new \RuntimeException('Unknown Expression: ' . $expr::class),
        };
    }
    private function get_field(string $field): string
    {
        return Driver::QB_SOURCE_ALIAS . '.' . $field;
    }
    private function get_in_constraint(Abstract_Node $parent_node, string $field, array $values): void
    {
        $or_node = $parent_node->orx();
        foreach ($values as $value) {
            $or_node->eq()->field($this->get_field($field))->literal($value);
        }
        $or_node->end();
    }
}