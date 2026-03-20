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
final class Numeric_Range_Filter implements Filter_Interface
{
    public const DEFAULT_SCALE = 0;
    public const DEFAULT_ROUNDING_MODE = \Number_Formatter::ROUND_HALFUP;
    public const DEFAULT_INCLUSIVE_FROM = true;
    public const DEFAULT_INCLUSIVE_TO = true;
    /**
     * @param array{
     *     greaterThan?: string,
     *     lessThan?: string,
     * } $data
     */
    public function apply(Data_Source_Interface $data_source, string $name, $data, array $options): void
    {
        if (empty($data)) {
            return;
        }
        /** @var string $field */
        $field = $options['field'] ?? $name;
        /** @var int $scale */
        $scale = $options['scale'] ?? self::DEFAULT_SCALE;
        /** @var int $mode */
        $mode = $options['rounding_mode'] ?? self::DEFAULT_ROUNDING_MODE;
        $greater_than = $this->get_data_value($data, 'greaterThan');
        $less_than = $this->get_data_value($data, 'lessThan');
        $expression_builder = $data_source->get_expression_builder();
        if ('' !== $greater_than) {
            $inclusive = (bool) ($options['inclusive_from'] ?? self::DEFAULT_INCLUSIVE_FROM);
            $amount = $this->normalize_amount((float) $greater_than, $scale, $mode);
            if ($inclusive) {
                $data_source->restrict($expression_builder->greater_than_or_equal($field, $amount));
            } else {
                $data_source->restrict($expression_builder->greater_than($field, $amount));
            }
        }
        if ('' !== $less_than) {
            $inclusive = (bool) ($options['inclusive_to'] ?? self::DEFAULT_INCLUSIVE_TO);
            $amount = $this->normalize_amount((float) $less_than, $scale, $mode);
            if ($inclusive) {
                $data_source->restrict($expression_builder->less_than_or_equal($field, $amount));
            } else {
                $data_source->restrict($expression_builder->less_than($field, $amount));
            }
        }
    }
    private function normalize_amount(float $amount, int $scale, int $mode): int
    {
        return (int) round($amount * 10 ** $scale, $mode);
    }
    /**
     * @param array{
     *     greaterThan?: string,
     *     lessThan?: string,
     * } $data
     */
    private function get_data_value(array $data, string $key): string
    {
        return $data[$key] ?? '';
    }
}