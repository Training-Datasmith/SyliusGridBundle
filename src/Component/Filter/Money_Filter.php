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
namespace Sylius\Component\Grid\Filter;

use Sylius\Component\Grid\Data\Data_Source_Interface;
use Sylius\Component\Grid\Filtering\Filter_Interface;
\trigger_deprecation('sylius/grid', '1.8', '%s is deprecated, replace it with your own implementation.', Money_Filter::class);
final class Money_Filter implements Filter_Interface
{
    public const DEFAULT_SCALE = 2;
    /**
     * @param array{
     *     field?: string,
     *     scale?: int,
     *     currency_field: string,
     * } $options
     * @param array{
     *     greaterThan?: string|float,
     *     lessThan?: string|float,
     *     currency?: string,
     * }|empty $data
     */
    public function apply(Data_Source_Interface $data_source, string $name, $data, array $options): void
    {
        if (empty($data)) {
            return;
        }
        $field = $options['field'] ?? $name;
        $scale = (int) ($options['scale'] ?? self::DEFAULT_SCALE);
        $greater_than = $data['greaterThan'] ?? '';
        $less_than = $data['lessThan'] ?? '';
        $expression_builder = $data_source->get_expression_builder();
        if (!empty($data['currency'])) {
            $currency_field = $options['currency_field'];
            $data_source->restrict($expression_builder->equals($currency_field, $data['currency']));
        }
        if ('' !== $greater_than) {
            $data_source->restrict($expression_builder->greater_than($field, $this->normalize_amount((float) $greater_than, $scale)));
        }
        if ('' !== $less_than) {
            $data_source->restrict($expression_builder->less_than($field, $this->normalize_amount((float) $less_than, $scale)));
        }
    }
    private function normalize_amount(float $amount, int $scale): int
    {
        return (int) round($amount * 10 ** $scale);
    }
}