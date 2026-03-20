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

use Doctrine\ODM\PHPCR\Document_Manager_Interface;
use Sylius\Component\Grid\Data\Data_Source_Interface;
use Sylius\Component\Grid\Data\Driver_Interface;
use Sylius\Component\Grid\Parameters;
@trigger_error(sprintf('The "%s" class is deprecated since Sylius 1.3. Doctrine MongoDB and PHPCR support will no longer be supported in Sylius 2.0.', Driver::class), \E_USER_DEPRECATED);
final readonly class Driver implements Driver_Interface
{
    /**
     * Driver name
     */
    public const NAME = 'doctrine/phpcr-odm';
    /**
     * Alias to use to reference fields from the data source class.
     */
    public const QB_SOURCE_ALIAS = 'o';
    public function __construct(private ?Document_Manager_Interface $document_manager = null)
    {
    }
    public function get_data_source(array $configuration, Parameters $parameters): Data_Source_Interface
    {
        if (null === $this->document_manager) {
            throw new \LogicException('Doctrine phpcr-odm is not available. Try running "composer require doctrine/phpcr-odm".');
        }
        if (!array_key_exists('class', $configuration)) {
            throw new \InvalidArgumentException('"class" must be configured.');
        }
        $repository = $this->document_manager->get_repository($configuration['class']);
        $query_builder = $repository->create_query_builder(self::QB_SOURCE_ALIAS);
        return new Data_Source($query_builder);
    }
}