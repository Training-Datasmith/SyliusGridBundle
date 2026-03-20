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

interface Field_Interface
{
    public static function create(string $name, string $type): self;
    public function get_name(): string;
    public function get_path(): ?string;
    public function set_path(?string $path): self;
    public function get_label(): ?string;
    public function set_label(?string $label): self;
    public function is_enabled(): bool;
    public function set_enabled(bool $enabled): self;
    public function is_sortable(): bool;
    public function set_sortable(bool $sortable, ?string $path = null): self;
    public function get_position(): ?int;
    public function set_position(?int $position): self;
    /**
     * @return array<string, mixed>
     */
    public function get_options(): array;
    /**
     * @param array<string, mixed> $options
     *
     * @deprecated use self::withOptions instead
     */
    public function set_options(array $options): self;
    /**
     * @param array<string, mixed> $options
     *
     * @deprecated use self::withOptions instead
     */
    public function add_options(array $options): self;
    /**
     * @param array<string, mixed> $options
     */
    public function with_options(array $options): self;
    /**
     * @param mixed $value
     */
    public function set_option(string $option, $value): self;
    public function remove_option(string $option): self;
    /**
     * @return array<string, mixed>
     */
    public function to_array(): array;
}