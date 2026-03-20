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

use Doctrine\ORM\Entity_Repository;
use Doctrine\ORM\Query_Builder;
use Doctrine\Persistence\Manager_Registry;
use Sylius\Component\Grid\Data\Data_Source_Interface;
use Sylius\Component\Grid\Data\Driver_Interface;
use Sylius\Component\Grid\Exception\RuntimeException;
use Sylius\Component\Grid\Parameters;
final readonly class Driver implements Driver_Interface
{
    public const NAME = 'doctrine/orm';
    private Manager_Registry $manager_registry;
    public function __construct(Manager_Registry $manager_registry)
    {
        $this->manager_registry = $manager_registry;
    }
    /**
     * @param array{
     *     class: class-string,
     *     repository?: array{
     *         method: string|array{object, string},
     *         arguments?: array<int|string, mixed>,
     *     },
     *     pagination?: array{
     *         fetch_join_collection?: bool,
     *         use_output_walkers?: bool,
     *     },
     * } $configuration
     */
    public function get_data_source(array $configuration, Parameters $parameters): Data_Source_Interface
    {
        if (!array_key_exists('class', $configuration)) {
            throw new \InvalidArgumentException('Missing configuration: when using the ORM driver for a grid, you must define the "class" option.');
        }
        /** @var class-string $class */
        $class = $configuration['class'];
        $manager = $this->manager_registry->get_manager_for_class($class);
        if (null === $manager) {
            throw new RuntimeException(sprintf('Doctrine ORM manager for class "%s" not found.', $class));
        }
        /** @var EntityRepository<object> $repository */
        $repository = $manager->get_repository($class);
        /** @var bool $fetchJoinCollection */
        $fetch_join_collection = $configuration['pagination']['fetch_join_collection'] ?? true;
        /** @var bool $useOutputWalkers */
        $use_output_walkers = $configuration['pagination']['use_output_walkers'] ?? true;
        if (!isset($configuration['repository']['method'])) {
            return new Data_Source($repository->create_query_builder('o'), $fetch_join_collection, $use_output_walkers);
        }
        /** @var array<int|string, mixed> $repositoryArguments */
        $repository_arguments = $configuration['repository']['arguments'] ?? [];
        $arguments = array_values($repository_arguments);
        $method = $configuration['repository']['method'];
        if (is_array($method) && 2 === count($method)) {
            /** @var QueryBuilder $queryBuilder */
            $query_builder = $method[0];
            /** @var string $method */
            $method = $method[1];
            /** @var QueryBuilder $resultQueryBuilder */
            $result_query_builder = $query_builder->{$method}(...$arguments);
            return new Data_Source($result_query_builder, $fetch_join_collection, $use_output_walkers);
        }
        /** @var QueryBuilder $resultQueryBuilder */
        $result_query_builder = $repository->{$method}(...$arguments);
        return new Data_Source($result_query_builder, $fetch_join_collection, $use_output_walkers);
    }
}