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
namespace Sylius\Bundle\Grid_Bundle\Builder\Filter;

/**
 * @method mixed getDefaultValue()
 * @method self setDefaultValue(mixed $defaultValue)
 * @method array<string, mixed> getCriteria()
 */
interface Filter_Interface
{
    public static function create(string $name, string $type): self;
    public function get_name(): string;
    public function get_label(): string|bool|null;
    public function set_label(string|bool|null $label): self;
    public function is_enabled(): bool;
    public function set_enabled(bool $enabled): self;
    public function get_template(): ?string;
    public function set_template(?string $template): self;
    /**
     * @return array<string, mixed>
     */
    public function get_options(): array;
    /**
     * @param array<string, mixed> $options
     */
    public function set_options(array $options): self;
    /**
     * @param mixed $value
     */
    public function add_option(string $option, $value): self;
    public function remove_option(string $option): self;
    /**
     * @return array<string, mixed>
     */
    public function get_form_options(): array;
    /**
     * @param array<string, mixed> $formOptions
     */
    public function set_form_options(array $form_options): self;
    /**
     * @param mixed $value
     */
    public function add_form_option(string $option, $value): self;
    public function remove_form_option(string $option): self;
    /**
     * @param array<string, mixed> $criteria
     */
    public function set_criteria(array $criteria): self;
    /**
     * @return array<string, mixed>
     */
    public function to_array(): array;
}