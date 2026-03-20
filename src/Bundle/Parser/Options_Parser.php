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
namespace Sylius\Bundle\Grid_Bundle\Parser;

use Sylius\Component\Grid\Exception\InvalidArgumentException;
final class Options_Parser implements Options_Parser_Interface
{
    public function parse_options(array $parameters): array
    {
        return array_map(function (mixed $parameter): mixed {
            if (is_array($parameter)) {
                /** @var array<string, mixed> $parameter */
                return $this->parse_options($parameter);
            }
            return $this->parse_option($parameter);
        }, $parameters);
    }
    private function parse_option(mixed $parameter): mixed
    {
        if (!is_string($parameter)) {
            return $parameter;
        }
        if (str_starts_with($parameter, 'callable:')) {
            return $this->parse_option_callable(substr($parameter, 9));
        }
        return $parameter;
    }
    private function parse_option_callable(string $callable): \Closure
    {
        if (!is_callable($callable)) {
            throw new InvalidArgumentException(\sprintf('%s is not a callable.', $callable));
        }
        return $callable(...);
    }
}