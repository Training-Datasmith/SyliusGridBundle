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

interface Expression_Builder_Interface
{
    /**
     * @param mixed ...$expressions
     *
     * @return mixed
     */
    public function and_x(...$expressions);
    /**
     * @param mixed ...$expressions
     *
     * @return mixed
     */
    public function or_x(...$expressions);
    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function comparison(string $field, string $operator, $value);
    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function equals(string $field, $value);
    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function not_equals(string $field, $value);
    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function less_than(string $field, $value);
    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function less_than_or_equal(string $field, $value);
    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function greater_than(string $field, $value);
    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function greater_than_or_equal(string $field, $value);
    /**
     * @param mixed[] $values
     *
     * @return mixed
     */
    public function in(string $field, array $values);
    /**
     * @param mixed[] $values
     *
     * @return mixed
     */
    public function not_in(string $field, array $values);
    /**
     * @return mixed
     */
    public function is_null(string $field);
    /**
     * @return mixed
     */
    public function is_not_null(string $field);
    /**
     * @return mixed
     */
    public function like(string $field, string $pattern);
    /**
     * @return mixed
     */
    public function not_like(string $field, string $pattern);
    /**
     * @return mixed
     */
    public function order_by(string $field, string $direction);
    /**
     * @return mixed
     */
    public function add_order_by(string $field, string $direction);
}