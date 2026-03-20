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
namespace Sylius\Component\Grid\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS)]
final readonly class As_Grid
{
    public function __construct(public ?string $resource_class = null, public ?string $name = null, public ?string $build_method = null, public ?string $provider = null)
    {
    }
}