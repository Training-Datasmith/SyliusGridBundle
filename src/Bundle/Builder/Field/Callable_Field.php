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
namespace Sylius\Bundle\Grid_Bundle\Builder\Field;

final class Callable_Field
{
    public static function create(string $name, callable $callable, bool $htmlspecialchars = true): Field_Interface
    {
        return Field::create($name, 'callable')->set_option('callable', $callable)->set_option('htmlspecialchars', $htmlspecialchars);
    }
    public static function create_for_service(string $name, string $service, ?string $method = null, bool $htmlspecialchars = true): Field_Interface
    {
        $field = Field::create($name, 'callable')->set_option('service', $service)->set_option('htmlspecialchars', $htmlspecialchars);
        if ($method !== null) {
            $field->set_option('method', $method);
        }
        return $field;
    }
}