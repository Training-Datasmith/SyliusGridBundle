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

final class Filter implements Filter_Interface
{
    private string|bool|null $label = null;
    private ?bool $enabled = null;
    private ?string $template = null;
    /** @var array<string, mixed> */
    private array $options = [];
    /** @var array<string, mixed> */
    private array $form_options = [];
    /** @var array<string, mixed> */
    private array $criteria = [];
    private mixed $default_value = null;
    private function __construct(private readonly string $name, private readonly string $type)
    {
    }
    public static function create(string $name, string $type): Filter_Interface
    {
        return new self($name, $type);
    }
    public function get_name(): string
    {
        return $this->name;
    }
    public function get_label(): string|bool|null
    {
        return $this->label;
    }
    public function set_label(string|bool|null $label): Filter_Interface
    {
        $this->label = $label;
        return $this;
    }
    public function set_enabled(bool $enabled): Filter_Interface
    {
        $this->enabled = $enabled;
        return $this;
    }
    public function is_enabled(): bool
    {
        return $this->enabled ?? true;
    }
    public function get_template(): ?string
    {
        return $this->template;
    }
    public function set_template(?string $template): Filter_Interface
    {
        $this->template = $template;
        return $this;
    }
    public function get_options(): array
    {
        return $this->options;
    }
    public function set_options(array $options): Filter_Interface
    {
        $this->options = $options;
        return $this;
    }
    /**
     * @param mixed $value
     */
    public function add_option(string $option, $value): Filter_Interface
    {
        $this->options[$option] = $value;
        return $this;
    }
    public function remove_option(string $option): Filter_Interface
    {
        unset($this->options[$option]);
        return $this;
    }
    public function get_form_options(): array
    {
        return $this->form_options;
    }
    public function set_form_options(array $form_options): Filter_Interface
    {
        $this->form_options = $form_options;
        return $this;
    }
    /**
     * @param mixed $value
     */
    public function add_form_option(string $option, $value): Filter_Interface
    {
        $this->form_options[$option] = $value;
        return $this;
    }
    public function remove_form_option(string $option): Filter_Interface
    {
        unset($this->form_options[$option]);
        return $this;
    }
    /**
     * @return array<string, mixed>
     */
    public function get_criteria(): array
    {
        return $this->criteria;
    }
    public function set_criteria(array $criteria): Filter_Interface
    {
        $this->criteria = $criteria;
        return $this;
    }
    public function get_default_value(): mixed
    {
        return $this->default_value;
    }
    public function set_default_value(mixed $default_value): Filter_Interface
    {
        $this->default_value = $default_value;
        return $this;
    }
    public function to_array(): array
    {
        $output = ['type' => $this->type];
        if (null !== $this->label) {
            $output['label'] = $this->label;
        }
        if (null !== $this->enabled) {
            $output['enabled'] = $this->enabled;
        }
        if (null !== $this->template) {
            $output['template'] = $this->template;
        }
        if (count($this->options) > 0) {
            $output['options'] = $this->options;
        }
        if (count($this->form_options) > 0) {
            $output['form_options'] = $this->form_options;
        }
        if (count($this->criteria) > 0) {
            $output['criteria'] = $this->criteria;
        }
        if (null !== $this->default_value) {
            $output['default_value'] = $this->default_value;
        }
        return $output;
    }
}