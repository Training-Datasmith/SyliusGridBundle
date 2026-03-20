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

use Doctrine\ODM\PHPCR\Query\Builder\Query_Builder;
use Pagerfanta\Doctrine\PHPCRODM\Query_Adapter;
use Pagerfanta\Pagerfanta;
use Sylius\Bundle\Grid_Bundle\Doctrine\Data_Source_Interface;
use Sylius\Component\Grid\Data\Expression_Builder_Interface;
use Sylius\Component\Grid\Parameters;
@trigger_error(sprintf('The "%s" class is deprecated since Sylius 1.3. Doctrine MongoDB and PHPCR support will no longer be supported in Sylius 2.0.', Data_Source::class), \E_USER_DEPRECATED);
final readonly class Data_Source implements Data_Source_Interface
{
    private Query_Builder $query_builder;
    private Expression_Builder_Interface $expression_builder;
    public function __construct(Query_Builder $query_builder, ?Expression_Builder_Interface $expression_builder = null)
    {
        $this->query_builder = $query_builder;
        $this->expression_builder = $expression_builder ?: new Expression_Builder();
    }
    public function restrict($expression, string $condition = Data_Source_Interface::CONDITION_AND): void
    {
        $parent_node = match ($condition) {
            Data_Source_Interface::CONDITION_AND => $this->query_builder->and_where(),
            Data_Source_Interface::CONDITION_OR => $this->query_builder->or_where(),
            default => throw new \RuntimeException(sprintf('Unknown restrict condition "%s"', $condition)),
        };
        $visitor = new Expression_Visitor($this->query_builder);
        $visitor->dispatch($expression, $parent_node);
    }
    public function get_query_builder(): Query_Builder
    {
        return $this->query_builder;
    }
    public function get_expression_builder(): Expression_Builder_Interface
    {
        return $this->expression_builder;
    }
    public function get_data(Parameters $parameters): \Pagerfanta\Pagerfanta
    {
        if (!class_exists(Query_Adapter::class)) {
            throw new \LogicException('Pagerfanta PHPCR-ODM adapter is not available. Try running "composer require pagerfanta/doctrine-phpcr-odm-adapter".');
        }
        $order_by = $this->query_builder->order_by();
        foreach ($this->expression_builder->get_order_bys() as $field => $direction) {
            if (is_int($field)) {
                $field = $direction;
                $direction = 'asc';
            }
            // todo: validate direction?
            $direction = strtolower($direction);
            $order_by->{$direction}()->field(sprintf('%s.%s', Driver::QB_SOURCE_ALIAS, $field));
        }
        $paginator = new Pagerfanta(new Query_Adapter($this->query_builder));
        $paginator->set_current_page((int) $parameters->get('page', 1));
        return $paginator;
    }
}