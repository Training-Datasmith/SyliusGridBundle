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

use Doctrine\DBAL\Connection;
use Sylius\Component\Grid\Data\Data_Source_Interface;
use Sylius\Component\Grid\Data\Driver_Interface;
use Sylius\Component\Grid\Parameters;
final readonly class Driver implements Driver_Interface
{
    public const NAME = 'doctrine/dbal';
    private Connection $connection;
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }
    /**
     * @param array{
     *     table: string,
     *     aliases: array<string, string>,
     * } $configuration
     */
    public function get_data_source(array $configuration, Parameters $parameters): Data_Source_Interface
    {
        if (!array_key_exists('table', $configuration)) {
            throw new \InvalidArgumentException('"table" must be configured.');
        }
        $query_builder = $this->connection->create_query_builder();
        $query_builder->select('o.*')->from($configuration['table'], 'o');
        foreach ($configuration['aliases'] as $column => $alias) {
            $query_builder->add_select(sprintf('o.%s as %s', $column, $alias));
        }
        return new Data_Source($query_builder);
    }
}