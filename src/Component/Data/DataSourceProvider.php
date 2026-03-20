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
namespace Sylius\Component\Grid\Data;

use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
use Sylius\Component\Registry\Service_Registry_Interface;
final readonly class Data_Source_Provider implements Data_Source_Provider_Interface
{
    private Service_Registry_Interface $drivers_registry;
    public function __construct(Service_Registry_Interface $drivers_registry)
    {
        $this->drivers_registry = $drivers_registry;
    }
    public function get_data_source(Grid $grid, Parameters $parameters): Data_Source_Interface
    {
        $driver_name = $grid->get_driver();
        if (!$this->drivers_registry->has($driver_name)) {
            throw new Unsupported_Driver_Exception($driver_name);
        }
        /** @var DriverInterface $driver */
        $driver = $this->drivers_registry->get($driver_name);
        return $driver->get_data_source($grid->get_driver_configuration(), $parameters);
    }
}