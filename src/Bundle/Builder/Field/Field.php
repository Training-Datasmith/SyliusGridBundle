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

/**
 * Immutable grid field definition used in the Sylius grid builder DSL.
 *
 * Fields are created via Field::create() and configured using a fluent interface.
 * The final configuration is serialised to an array by to_array() for use in
 * the grid definition resolved by the grid registry.
 */
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

    /**
     * @param string $name Unique field identifier within the grid
     * @param string $type Field type key (e.g. 'string', 'datetime', 'twig')
     */
    private function __construct(private readonly string $name, private readonly string $type)
    {
    }

    /**
     * Creates a new field definition for the given name and type.
     *
     * @param string $name Unique field identifier within the grid
     * @param string $type Field type key (e.g. 'string', 'datetime', 'twig')
     *
     * @return Field_Interface Fluent field builder
     */
    public static function create(string $name, string $type): Field_Interface
    {
        return new self($name, $type);
    }

    /**
     * Returns the unique field identifier.
     *
     * @return string Field name as registered in the grid definition
     */
    public function get_name(): string
    {
        return $this->name;
    }

    /**
     * Returns the property path used to extract the field value from the resource.
     *
     * @return string|null Property path (e.g. 'order.total'), or null to use the field name
     */
    public function get_path(): ?string
    {
        return $this->path;
    }

    /**
     * Sets the property path for value extraction.
     *
     * @param string|null $path Property path or null to use the field name as the path
     *
     * @return Field_Interface Fluent interface
     */
    public function set_path(?string $path): Field_Interface
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Returns the human-readable label for this field.
     *
     * @return string|null Translation key or display label, null for default auto-label
     */
    public function get_label(): ?string
    {
        return $this->label;
    }

    /**
     * Sets the human-readable label (typically a translation key).
     *
     * @param string|null $label Translation key or null to use the auto-generated label
     *
     * @return Field_Interface Fluent interface
     */
    public function set_label(?string $label): Field_Interface
    {
        $this->label = $label;
        return $this;
    }

    /**
     * Returns whether this field is visible in the grid output.
     *
     * @return bool True by default if never explicitly set
     */
    public function is_enabled(): bool
    {
        return $this->enabled ?? true;
    }

    /**
     * Shows or hides this field in the rendered grid.
     *
     * @param bool $enabled True to show, false to hide
     *
     * @return Field_Interface Fluent interface
     */
    public function set_enabled(bool $enabled): Field_Interface
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * Returns whether sorting is enabled for this field.
     *
     * @return bool True if a sort path has been set
     */
    public function is_sortable(): bool
    {
        return null !== $this->sortable;
    }

    /**
     * Enables or disables column sorting for this field.
     *
     * When $sortable is true and $path is provided, that path is used for the
     * ORDER BY clause; otherwise the field name is used. Setting $sortable to
     * false clears the sort path.
     *
     * @param bool        $sortable Whether this field may be sorted
     * @param string|null $path     Optional ORDER BY property path override
     *
     * @return Field_Interface Fluent interface
     */
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