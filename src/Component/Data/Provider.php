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

use Psr\Container\Container_Interface;
use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
use Webmozart\Assert\Assert;
final readonly class Provider implements Data_Provider_Interface
{
    public function __construct(private Container_Interface $locator, private Data_Provider_Interface $decorated)
    {
    }
    public function get_data(Grid $grid, Parameters $parameters)
    {
        $provider = $grid->get_provider();
        if (null === $provider) {
            return $this->decorated->get_data($grid, $parameters);
        }
        if (\is_callable($provider)) {
            return $provider($grid, $parameters);
        }
        if (!$this->locator->has($provider)) {
            throw new \RuntimeException(sprintf('Provider "%s" not found on grid "%s"', $provider, $grid->get_code()));
        }
        $provider_instance = $this->locator->get($provider);
        Assert::is_instance_of($provider_instance, Data_Provider_Interface::class);
        return $provider_instance->get_data($grid, $parameters);
    }
}