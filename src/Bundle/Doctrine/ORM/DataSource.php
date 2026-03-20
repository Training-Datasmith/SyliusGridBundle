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

use Doctrine\ORM\Query_Builder;
use Pagerfanta\Doctrine\ORM\Query_Adapter;
use Pagerfanta\Pagerfanta;
use Sylius\Bundle\Grid_Bundle\Doctrine\Data_Source_Interface;
use Sylius\Component\Grid\Data\Expression_Builder_Interface;
use Sylius\Component\Grid\Parameters;
final readonly class Data_Source implements Data_Source_Interface
{
    private Query_Builder $query_builder;
    private Expression_Builder_Interface $expression_builder;
    /**
     * @param bool $fetchJoinCollection must be 'true' when the query fetch-joins a to-many collection,
     *                                  otherwise the pagination will yield incorrect results
     *                                  https://www.doctrine-project.org/projects/doctrine-orm/en/2.7/tutorials/pagination.html
     * @param bool $useOutputWalkers must be 'true' if the query has an order by statement for a field from
     *                                the to-many association, otherwise it will throw an exception
     *                                might greatly affect the performance (https://github.com/Sylius/Sylius/issues/3775)
     */
    public function __construct(Query_Builder $query_builder, private bool $fetch_join_collection, private bool $use_output_walkers)
    {
        $this->query_builder = $query_builder;
        $this->expression_builder = new Expression_Builder($query_builder);
    }
    /**
     * @param mixed $expression
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
            throw new \LogicException('Pagerfanta ORM adapter is not available. Try running "composer require pagerfanta/doctrine-orm-adapter".');
        }
        /** @var int|string $page */
        $page = $parameters->get('page', 1);
        $page = (int) $page;
        $paginator = new Pagerfanta(new Query_Adapter($this->query_builder, $this->fetch_join_collection, $this->use_output_walkers));
        $paginator->set_normalize_out_of_range_pages(true);
        $paginator->set_current_page($page > 0 ? $page : 1);
        return $paginator;
    }
}