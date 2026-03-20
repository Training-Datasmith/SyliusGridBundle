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
use Pagerfanta\Doctrine\DBAL\Query_Adapter;
use Pagerfanta\Pagerfanta;
use Sylius\Bundle\Grid_Bundle\Doctrine\Data_Source_Interface;
use Sylius\Component\Grid\Data\Expression_Builder_Interface;
use Sylius\Component\Grid\Parameters;
final readonly class Data_Source implements Data_Source_Interface
{
    private Query_Builder $query_builder;
    private Expression_Builder_Interface $expression_builder;
    public function __construct(Query_Builder $query_builder)
    {
        $this->query_builder = $query_builder;
        $this->expression_builder = new Expression_Builder($query_builder);
    }
    /**
     * @param CompositeExpression|string $expression
     */
    public function restrict($expression, string $condition = Data_Source_Interface::CONDITION_AND): void
    {
        switch ($condition) {
            case Data_Source_Interface::CONDITION_AND:
                $this->query_builder->and_where($expression);
                break;
            case Data_Source_Interface::CONDITION_OR:
                $this->query_builder->or_where($expression);
                break;
        }
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
            throw new \LogicException('Pagerfanta DBAL adapter is not available. Try running "composer require pagerfanta/doctrine-dbal-adapter".');
        }
        /** @var int|string $page */
        $page = $parameters->get('page', 1);
        $page = (int) $page;
        $count_query_builder_modifier = function (Query_Builder $query_builder): void {
            $query_builder->select('COUNT(DISTINCT o.id) AS total_results')->set_max_results(1);
        };
        $paginator = new Pagerfanta(new Query_Adapter($this->query_builder, $count_query_builder_modifier));
        $paginator->set_normalize_out_of_range_pages(true);
        $paginator->set_current_page($page > 0 ? $page : 1);
        return $paginator;
    }
}