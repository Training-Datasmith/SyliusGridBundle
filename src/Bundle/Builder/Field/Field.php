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

final class Field implements Field_Interface
{
    private ?string $path = null;
    private ?string $label = null;
    private ?bool $enabled = null;
    /** @var bool|string|null */
    private $sortable;
    private ?int $position = null;
    /** @var array<string, mixed> */
    private array $options = [];
    private function __construct(private readonly string $name, private readonly string $type)
    {
    }
    public static function create(string $name, string $type): Field_Interface
    {
        return new self($name, $type);
    }
    public function get_name(): string
    {
        return $this->name;
    }
    public function get_path(): ?string
    {
        return $this->path;
    }
    public function set_path(?string $path): Field_Interface
    {
        $this->path = $path;
        return $this;
    }
    public function get_label(): ?string
    {
        return $this->label;
    }
    public function set_label(?string $label): Field_Interface
    {
        $this->label = $label;
        return $this;
    }
    public function is_enabled(): bool
    {
        return $this->enabled ?? true;
    }
    public function set_enabled(bool $enabled): Field_Interface
    {
        $this->enabled = $enabled;
        return $this;
    }
    public function is_sortable(): bool
    {
        return null !== $this->sortable;
    }
    public function set_sortable(bool $sortable, ?string $path = null): Field_Interface
    {
        if ($sortable) {
            $this->sortable = $path ?: true;
        } else {
            $this->sortable = null;
        }
        return $this;
    }
    public function get_position(): ?int
    {
        return $this->position;
    }
    public function set_position(?int $position): Field_Interface
    {
        $this->position = $position;
        return $this;
    }
    public function get_options(): array
    {
        return $this->options;
    }
    public function set_options(array $options): Field_Interface
    {
        $this->options = $options;
        return $this;
    }
    /**
     * @param mixed $value
     */
    public function set_option(string $option, $value): Field_Interface
    {
        $this->options[$option] = $value;
        return $this;
    }
    public function add_options(array $options): Field_Interface
    {
        $this->options = array_merge($this->options, $options);
        return $this;
    }
    public function with_options(array $options): self
    {
        $this->options = [...$this->options, ...$options];
        return $this;
    }
    public function remove_option(string $option): Field_Interface
    {
        unset($this->options[$option]);
        return $this;
    }
    public function to_array(): array
    {
        $output = ['type' => $this->type];
        if (null !== $this->label) {
            $output['label'] = $this->label;
        }
        if (null !== $this->path) {
            $output['path'] = $this->path;
        }
        if (null !== $this->enabled) {
            $output['enabled'] = $this->enabled;
        }
        if (null !== $this->sortable) {
            $output['sortable'] = $this->sortable;
        }
        if (null !== $this->position) {
            $output['position'] = $this->position;
        }
        if (count($this->options) > 0) {
            $output['options'] = $this->options;
        }
        return $output;
    }
}