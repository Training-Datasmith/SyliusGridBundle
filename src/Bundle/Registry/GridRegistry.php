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
namespace Sylius\Bundle\Grid_Bundle\Registry;

use Sylius\Bundle\Grid_Bundle\Grid\Grid_Interface;
use Symfony\Component\Dependency_Injection\Service_Locator;
final readonly class Grid_Registry implements Grid_Registry_Interface
{
    public function __construct(
        /** @var ServiceLocator<GridInterface> */
        private Service_Locator $grid_locator
    )
    {
    }
    public function get_grid(string $code): ?Grid_Interface
    {
        return $this->grid_locator->has($code) ? $this->grid_locator->get($code) : null;
    }
}