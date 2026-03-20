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
namespace Sylius\Bundle\Grid_Bundle\Doctrine;

use Doctrine\DBAL\Query\Query_Builder as DBALQueryBuilder;
use Doctrine\ODM\PHPCR\Query\Builder\Query_Builder as ODMQueryBuilder;
use Doctrine\ORM\Query_Builder as ORMQueryBuilder;
use Sylius\Component\Grid\Data\Data_Source_Interface as BaseDataSourceInterface;
interface Data_Source_Interface extends Base_Data_Source_Interface
{
    public function get_query_builder(): Orm_Query_Builder|Dbal_Query_Builder|Odm_Query_Builder;
}