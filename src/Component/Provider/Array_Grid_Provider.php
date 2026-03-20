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
namespace Sylius\Component\Grid\Provider;

use Sylius\Component\Grid\Configuration\Grid_Configuration_Extender;
use Sylius\Component\Grid\Configuration\Grid_Configuration_Extender_Interface;
use Sylius\Component\Grid\Configuration\Grid_Configuration_Removals_Handler;
use Sylius\Component\Grid\Configuration\Grid_Configuration_Removals_Handler_Interface;
use Sylius\Component\Grid\Configuration\Grid_Configuration_Sorting_Handler;
use Sylius\Component\Grid\Configuration\Grid_Configuration_Sorting_Handler_Interface;
use Sylius\Component\Grid\Definition\Array_To_Definition_Converter_Interface;
use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Exception\Undefined_Grid_Exception;
use Webmozart\Assert\Assert;
final class Array_Grid_Provider implements Grid_Provider_Interface
{
    /**
     * @param array<string, array<string, mixed>> $gridConfigurations
     */
    public function __construct(private readonly Array_To_Definition_Converter_Interface $converter, private array $grid_configurations, private readonly ?Grid_Configuration_Extender_Interface $grid_configuration_extender = new Grid_Configuration_Extender(), private readonly ?Grid_Configuration_Removals_Handler_Interface $grid_configuration_removals_handler = new Grid_Configuration_Removals_Handler(), private readonly ?Grid_Configuration_Sorting_Handler_Interface $grid_configuration_sorting_handler = new Grid_Configuration_Sorting_Handler())
    {
    }
    public function get(string $code): Grid
    {
        if (!array_key_exists($code, $this->grid_configurations)) {
            throw new Undefined_Grid_Exception($code);
        }
        $grid_configuration = $this->grid_configurations[$code];
        /** @var string|null $parentGridCode */
        $parent_grid_code = $grid_configuration['extends'] ?? null;
        if (null !== $parent_grid_code) {
            $parent_grid_configuration = $this->grid_configurations[$parent_grid_code] ?? null;
            Assert::not_null($parent_grid_configuration, sprintf('Parent grid with code "%s" does not exists.', $parent_grid_code));
            $grid_configuration = $this->grid_configuration_extender->extends($grid_configuration, $parent_grid_configuration);
        }
        $grid_configuration = $this->grid_configuration_removals_handler->handle($grid_configuration);
        $grid_configuration = $this->grid_configuration_sorting_handler->handle($grid_configuration);
        return $this->converter->convert($code, $grid_configuration);
    }
}