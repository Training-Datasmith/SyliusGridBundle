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
final class Date_Filter implements Filter_Interface
{
    public const NAME = 'date';
    public const DEFAULT_INCLUSIVE_FROM = true;
    public const DEFAULT_INCLUSIVE_TO = false;
    /**
     * @param array{
     *     from?: array{
     *        date: string,
     *        time?: string,
     *     },
     *     to?: array{
     *        date: string,
     *        time?: string,
     *     },
     * } $data
     * @param array{
     *     field?: string,
     *     inclusive_from?: bool|string|int,
     *     inclusive_to?: bool|string|int,
     * } $options
     */
    public function apply(Data_Source_Interface $data_source, string $name, $data, array $options): void
    {
        $expression_builder = $data_source->get_expression_builder();
        $field = $options['field'] ?? $name;
        $from = isset($data['from']) ? $this->get_date_time($data['from'], '00:00') : null;
        if (null !== $from) {
            $inclusive = $options['inclusive_from'] ?? self::DEFAULT_INCLUSIVE_FROM;
            if (true === $inclusive) {
                $data_source->restrict($expression_builder->greater_than_or_equal($field, $from));
            } else {
                $data_source->restrict($expression_builder->greater_than($field, $from));
            }
        }
        $to = isset($data['to']) ? $this->get_date_time($data['to'], '23:59') : null;
        if (null !== $to) {
            $inclusive = $options['inclusive_to'] ?? self::DEFAULT_INCLUSIVE_TO;
            if (true === $inclusive) {
                $data_source->restrict($expression_builder->less_than_or_equal($field, $to));
            } else {
                $data_source->restrict($expression_builder->less_than($field, $to));
            }
        }
    }
    /**
     * @param array{
     *     date: string,
     *     time?: string,
     * } $data
     */
    private function get_date_time(array $data, string $default_time): ?string
    {
        if (empty($data['date'])) {
            return null;
        }
        if (empty($data['time'])) {
            $data['time'] = $default_time;
        }
        return $data['date'] . ' ' . $data['time'];
    }
}