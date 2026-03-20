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
namespace Sylius\Bundle\Grid_Bundle\Form\Data_Transformer;

use Symfony\Component\Form\Data_Transformer_Interface;
use Webmozart\Assert\Assert;
/**
 * @implements DataTransformerInterface<array<string, mixed>, array<string, mixed>>
 */
final class Date_Time_Filter_Transformer implements Data_Transformer_Interface
{
    /** @var array<string, array{hour: string, minute: string}> */
    private static array $default_time = ['from' => ['hour' => '00', 'minute' => '00'], 'to' => ['hour' => '23', 'minute' => '59']];
    public function __construct(private readonly string $type)
    {
        Assert::one_of($type, array_keys(self::$default_time));
    }
    public function transform(mixed $value): mixed
    {
        return $value;
    }
    public function reverse_transform(mixed $value): mixed
    {
        if (!is_array($value)) {
            return $value;
        }
        if (!isset($value['date']) || !is_array($value['date']) || !($value['date']['year'] ?? false)) {
            return $value;
        }
        if (!isset($value['time']) || !is_array($value['time'])) {
            return $value;
        }
        $value['time']['hour'] = $value['time']['hour'] === '' ? self::$default_time[$this->type]['hour'] : $value['time']['hour'];
        $value['time']['minute'] = $value['time']['minute'] === '' ? self::$default_time[$this->type]['minute'] : $value['time']['minute'];
        return $value;
    }
}