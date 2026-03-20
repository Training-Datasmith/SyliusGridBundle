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
namespace Sylius\Component\Grid\View;

use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
use Webmozart\Assert\Assert;
class Grid_View implements Grid_View_Interface
{
    /**
     * @param mixed $data
     */
    public function __construct(private $data, private readonly Grid $definition, private readonly Parameters $parameters)
    {
    }
    public function get_data()
    {
        return $this->data;
    }
    public function get_definition(): Grid
    {
        return $this->definition;
    }
    public function get_parameters(): Parameters
    {
        return $this->parameters;
    }
    public function get_sorting_order(string $field_name): ?string
    {
        $this->assert_field_is_sortable($field_name);
        $current_sorting = $this->get_currently_sorted_by();
        if (array_key_exists($field_name, $current_sorting)) {
            return $current_sorting[$field_name];
        }
        $defined_sorting = $this->definition->get_sorting();
        return reset($defined_sorting) ?: null;
    }
    public function is_sorted_by(string $field_name): bool
    {
        $this->assert_field_is_sortable($field_name);
        if ($this->parameters->has('sorting')) {
            /** @var array<string, string> $sorting */
            $sorting = $this->parameters->get('sorting');
            return array_key_exists($field_name, $sorting);
        }
        $sorting_definition = $this->get_definition()->get_sorting();
        $sorted_fields = array_keys($sorting_definition);
        return $field_name === array_shift($sorted_fields);
    }
    /**
     * @return array<string, string>
     */
    private function get_currently_sorted_by(): array
    {
        $default_sorting = $this->definition->get_sorting();
        if (!$this->parameters->has('sorting')) {
            return $default_sorting;
        }
        /** @var array<string, string> $sorting */
        $sorting = $this->parameters->get('sorting');
        return array_merge($default_sorting, $sorting);
    }
    /**
     * @throws \InvalidArgumentException
     */
    private function assert_field_is_sortable(string $field_name): void
    {
        Assert::true($this->definition->has_field($field_name), sprintf('Field "%s" does not exist.', $field_name));
        Assert::true($this->definition->get_field($field_name)->is_sortable(), sprintf('Field "%s" is not sortable.', $field_name));
    }
}