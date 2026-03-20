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
namespace Sylius\Bundle\Grid_Bundle\Doctrine\ORM;

use Doctrine\ORM\Query\Expr\Andx;
use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\Query\Expr\From;
use Doctrine\ORM\Query\Expr\Func;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query\Expr\Orx;
use Doctrine\ORM\Query_Builder;
use Sylius\Component\Grid\Data\Member_Of_Aware_Expression_Builder_Interface;
final readonly class Expression_Builder implements Member_Of_Aware_Expression_Builder_Interface
{
    private Query_Builder $query_builder;
    public function __construct(Query_Builder $query_builder)
    {
        $this->query_builder = $query_builder;
    }
    /**
     * @param Comparison|Func|Andx|Orx|string ...$expressions
     */
    public function and_x(...$expressions)
    {
        return $this->query_builder->expr()->and_x(...$expressions);
    }
    /**
     * @param Comparison|Func|Andx|Orx|string ...$expressions
     */
    public function or_x(...$expressions)
    {
        return $this->query_builder->expr()->or_x(...$expressions);
    }
    public function comparison(string $field, string $operator, $value)
    {
        return new Comparison($field, $operator, $value);
    }
    public function equals(string $field, $value)
    {
        $field = $this->adjust_field($field);
        $parameter_name = $this->get_parameter_name($field);
        $this->query_builder->set_parameter($parameter_name, $value);
        return $this->query_builder->expr()->eq($this->resolve_field_by_adding_joins($field), ':' . $parameter_name);
    }
    public function not_equals(string $field, $value)
    {
        $field = $this->adjust_field($field);
        $parameter_name = $this->get_parameter_name($field);
        $this->query_builder->set_parameter($parameter_name, $value);
        return $this->query_builder->expr()->neq($this->resolve_field_by_adding_joins($field), ':' . $parameter_name);
    }
    public function less_than(string $field, $value)
    {
        $field = $this->adjust_field($field);
        $parameter_name = $this->get_parameter_name($field);
        $this->query_builder->set_parameter($parameter_name, $value);
        return $this->query_builder->expr()->lt($this->resolve_field_by_adding_joins($field), ':' . $parameter_name);
    }
    public function less_than_or_equal(string $field, $value)
    {
        $field = $this->adjust_field($field);
        $parameter_name = $this->get_parameter_name($field);
        $this->query_builder->set_parameter($parameter_name, $value);
        return $this->query_builder->expr()->lte($this->resolve_field_by_adding_joins($field), ':' . $parameter_name);
    }
    public function greater_than(string $field, $value)
    {
        $field = $this->adjust_field($field);
        $parameter_name = $this->get_parameter_name($field);
        $this->query_builder->set_parameter($parameter_name, $value);
        return $this->query_builder->expr()->gt($this->resolve_field_by_adding_joins($field), ':' . $parameter_name);
    }
    public function greater_than_or_equal(string $field, $value)
    {
        $field = $this->adjust_field($field);
        $parameter_name = $this->get_parameter_name($field);
        $this->query_builder->set_parameter($parameter_name, $value);
        return $this->query_builder->expr()->gte($this->resolve_field_by_adding_joins($field), ':' . $parameter_name);
    }
    /**
     * @param string $value
     */
    public function member_of($value, string $field)
    {
        $field = $this->adjust_field($field);
        return $this->query_builder->expr()->is_member_of($value, $this->resolve_field_by_adding_joins($field));
    }
    public function in(string $field, array $values)
    {
        $field = $this->adjust_field($field);
        return $this->query_builder->expr()->in($this->resolve_field_by_adding_joins($field), $values);
    }
    public function not_in(string $field, array $values)
    {
        $field = $this->adjust_field($field);
        return $this->query_builder->expr()->not_in($this->resolve_field_by_adding_joins($field), $values);
    }
    public function is_null(string $field)
    {
        $field = $this->adjust_field($field);
        return $this->query_builder->expr()->is_null($this->resolve_field_by_adding_joins($field));
    }
    public function is_not_null(string $field)
    {
        $field = $this->adjust_field($field);
        return $this->query_builder->expr()->is_not_null($this->resolve_field_by_adding_joins($field));
    }
    public function like(string $field, string $pattern)
    {
        $field = $this->adjust_field($field);
        return $this->query_builder->expr()->like((string) $this->query_builder->expr()->lower($this->resolve_field_by_adding_joins($field)), $this->query_builder->expr()->literal(strtolower($pattern)));
    }
    public function not_like(string $field, string $pattern)
    {
        $field = $this->adjust_field($field);
        return $this->query_builder->expr()->not_like((string) $this->query_builder->expr()->lower($this->resolve_field_by_adding_joins($field)), $this->query_builder->expr()->literal(strtolower($pattern)));
    }
    public function order_by(string $field, string $direction)
    {
        $field = $this->adjust_field($field);
        return $this->query_builder->order_by($this->resolve_field_by_adding_joins($field), $direction);
    }
    public function add_order_by(string $field, string $direction)
    {
        $field = $this->adjust_field($field);
        return $this->query_builder->add_order_by($this->resolve_field_by_adding_joins($field), $direction);
    }
    private function get_parameter_name(string $field): string
    {
        $parameter_name = str_replace('.', '_', $field);
        $i = 1;
        while ($this->has_parameter_name($parameter_name)) {
            $parameter_name .= $i;
        }
        return $parameter_name;
    }
    private function has_parameter_name(string $parameter_name): bool
    {
        return null !== $this->query_builder->get_parameter($parameter_name);
    }
    private function adjust_field(string $field): string
    {
        $root_alias = $this->query_builder->get_root_aliases()[0];
        if (str_starts_with($field, $root_alias . '.')) {
            return substr_replace($field, '', 0, strlen($root_alias) + 1);
        }
        return $field;
    }
    private function resolve_field_by_adding_joins(string $field): string
    {
        [$field, $class_name] = $this->get_field_details($field);
        $metadata = $this->query_builder->get_entity_manager()->get_class_metadata($class_name);
        while (count($exploded_field = explode('.', $field, 3)) === 3) {
            [$root_field, $association_field, $remainder] = $exploded_field;
            if (isset($metadata->embedded_classes[$association_field])) {
                break;
            }
            /** @var class-string $targetEntity */
            $target_entity = $metadata->get_association_mapping($association_field)['targetEntity'];
            $metadata = $this->query_builder->get_entity_manager()->get_class_metadata($target_entity);
            $root_and_association_field = sprintf('%s.%s', $root_field, $association_field);
            /** @var array<Join[]> $joinDQLPart */
            $join_dql_part = $this->query_builder->get_dql_part('join');
            $joins = array_merge([], ...array_values($join_dql_part));
            foreach ($joins as $join) {
                if ($join->get_join() === $root_and_association_field) {
                    $field = sprintf('%s.%s', (string) $join->get_alias(), $remainder);
                    continue 2;
                }
            }
            // Association alias can't start with a number
            // Mapping numbers to letters will not increase the collision probability and not lower the entropy
            $association_alias = str_replace(['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'], ['g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p'], md5($root_and_association_field));
            $this->query_builder->inner_join($root_and_association_field, $association_alias);
            $field = sprintf('%s.%s', $association_alias, $remainder);
        }
        return $field;
    }
    /**
     * This method returns an absolute path of a property path and the FQCN of the root element.
     *
     * Given the following query:
     *
     * SELECT bo FROM App\Book bo INNER JOIN App\Author au ON bo.author_id = au.id
     *
     * It will behave as follows:
     *
     * bo.title => [book.title, App\Book]
     * title => [book.title, App\Book]
     * au => [book.author, App\Book]
     * au.name => [book.author.name, App\Book]
     *
     * @return array{
     *     string,
     *     string
     * }
     */
    private function get_field_details(string $field): array
    {
        $root_field = explode('.', $field)[0];
        if (!in_array($root_field, $this->query_builder->get_all_aliases(), true)) {
            $field = sprintf('%s.%s', $this->query_builder->get_root_aliases()[0], $field);
        }
        /** @var array<Join[]> $joinDQLPart */
        $join_dql_part = $this->query_builder->get_dql_part('join');
        $joins = array_merge([], ...array_values($join_dql_part));
        while ($exploded_field = explode('.', $field, 2)) {
            $root_field = $exploded_field[0];
            $remainder = $exploded_field[1] ?? '';
            if (in_array($root_field, $this->query_builder->get_root_aliases(), true)) {
                break;
            }
            foreach ($joins as $join) {
                if ($join->get_alias() === $root_field) {
                    $join_subject = $join->get_join();
                    if (class_exists($join_subject)) {
                        return [$field, $join_subject];
                    }
                    $field = rtrim(sprintf('%s.%s', $join_subject, $remainder), '.');
                    continue 2;
                }
            }
            throw new \RuntimeException(sprintf('Could not get mapping for "%s".', $field));
        }
        /** @var From[] $froms */
        $froms = $this->query_builder->get_dql_part('from');
        foreach ($froms as $from) {
            if ($from->get_alias() === $root_field) {
                return [$field, $from->get_from()];
            }
        }
        throw new \RuntimeException(sprintf('Could not get metadata for "%s".', $root_field));
    }
}