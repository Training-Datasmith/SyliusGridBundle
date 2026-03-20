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
namespace Sylius\Bundle\Grid_Bundle\Provider;

use Sylius\Bundle\Grid_Bundle\Grid\Grid_Interface;
use Sylius\Bundle\Grid_Bundle\Registry\Grid_Registry_Interface;
use Sylius\Component\Grid\Configuration\Grid_Configuration_Extender_Interface;
use Sylius\Component\Grid\Configuration\Grid_Configuration_Removals_Handler;
use Sylius\Component\Grid\Configuration\Grid_Configuration_Removals_Handler_Interface;
use Sylius\Component\Grid\Configuration\Grid_Configuration_Sorting_Handler;
use Sylius\Component\Grid\Configuration\Grid_Configuration_Sorting_Handler_Interface;
use Sylius\Component\Grid\Definition\Array_To_Definition_Converter_Interface;
use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Exception\Undefined_Grid_Exception;
use Sylius\Component\Grid\Provider\Grid_Provider_Interface;
use Webmozart\Assert\Assert;
final readonly class Service_Grid_Provider implements Grid_Provider_Interface
{
    public function __construct(private Array_To_Definition_Converter_Interface $converter, private Grid_Registry_Interface $grid_registry, private Grid_Configuration_Extender_Interface $grid_configuration_extender, private ?Grid_Configuration_Removals_Handler_Interface $grid_configuration_removals_handler = new Grid_Configuration_Removals_Handler(), private ?Grid_Configuration_Sorting_Handler_Interface $grid_configuration_sorting_handler = new Grid_Configuration_Sorting_Handler())
    {
    }
    public function get(string $code): Grid
    {
        if (is_a($code, Grid_Interface::class, true)) {
            $code = $code::get_name();
        }
        $grid = $this->grid_registry->get_grid($code);
        if (null === $grid) {
            throw new Undefined_Grid_Exception($code);
        }
        $grid_configuration = $grid->to_array();
        if (isset($grid_configuration['extends'])) {
            /** @var string $parentGridCode */
            $parent_grid_code = $grid_configuration['extends'];
            $grid_configuration = $this->extend($grid_configuration, $parent_grid_code);
        }
        $grid_configuration = $this->grid_configuration_removals_handler->handle($grid_configuration);
        $grid_configuration = $this->grid_configuration_sorting_handler->handle($grid_configuration);
        return $this->converter->convert($code, $grid_configuration);
    }
    /**
     * @param array<string, mixed> $gridConfiguration
     *
     * @return array<string, mixed>
     */
    private function extend(array $grid_configuration, string $parent_grid_code): array
    {
        $parent_grid = $this->grid_registry->get_grid($parent_grid_code);
        Assert::not_null($parent_grid, sprintf('Parent grid with code "%s" does not exists.', $parent_grid_code));
        $parent_grid_configuration = $parent_grid->to_array();
        return $this->grid_configuration_extender->extends($grid_configuration, $parent_grid_configuration);
    }
}