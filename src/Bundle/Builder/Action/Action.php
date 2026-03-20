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
namespace Sylius\Bundle\Grid_Bundle\Builder\Action;

final class Action implements Action_Interface
{
    private ?string $label = null;
    private ?bool $enabled = null;
    private ?string $template = null;
    private ?string $icon = null;
    /** @var array<string, mixed> */
    private array $options = [];
    private ?int $position = null;
    private function __construct(private readonly string $name, private readonly string $type)
    {
    }
    public static function create(string $name, string $type): Action_Interface
    {
        return new self($name, $type);
    }
    public function get_name(): string
    {
        return $this->name;
    }
    public function set_label(string $label): Action_Interface
    {
        $this->label = $label;
        return $this;
    }
    public function set_enabled(bool $enabled): Action_Interface
    {
        $this->enabled = $enabled;
        return $this;
    }
    public function get_template(): ?string
    {
        return $this->template;
    }
    public function set_template(string $template): Action_Interface
    {
        $this->template = $template;
        return $this;
    }
    public function set_icon(string $icon): Action_Interface
    {
        $this->icon = $icon;
        return $this;
    }
    public function set_options(array $options): Action_Interface
    {
        $this->options = $options;
        return $this;
    }
    public function set_position(int $position): Action_Interface
    {
        $this->position = $position;
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
        if (null !== $this->icon) {
            $output['icon'] = $this->icon;
        }
        if (count($this->options) > 0) {
            $output['options'] = $this->options;
        }
        if (null !== $this->position) {
            $output['position'] = $this->position;
        }
        return $output;
    }
}