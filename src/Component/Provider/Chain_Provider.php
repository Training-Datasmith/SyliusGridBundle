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

use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Exception\Undefined_Grid_Exception;
final readonly class Chain_Provider implements Grid_Provider_Interface
{
    /**
     * @param iterable<GridProviderInterface> $providers
     */
    public function __construct(private iterable $providers)
    {
    }
    public function get(string $code): Grid
    {
        foreach ($this->providers as $provider) {
            try {
                return $provider->get($code);
            } catch (Undefined_Grid_Exception) {
            }
        }
        throw new Undefined_Grid_Exception($code);
    }
}