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
namespace Sylius\Component\Grid\Definition;

class Filter
{
    /** @var string|bool|null */
    private string $label;
    private bool $enabled = true;
    private ?string $template = null;
    /** @var array<string, mixed> */
    private array $options = [];
    /** @var array<string, mixed> */
    private array $form_options = [];
    /** @var mixed */
    private $criteria;
    /**
     * Position equals to 100 to ensure that wile sorting filters by position ASC
     * the filters positioned by default will be last
     */
    private int $position = 100;
    private function __construct(private readonly string $name, private readonly string $type)
    {
        $this->label = $this->name;
    }
    public static function from_name_and_type(string $name, string $type): self
    {
        return new self($name, $type);
    }
    public function get_name(): string
    {
        return $this->name;
    }
    public function get_type(): string
    {
        return $this->type;
    }
    /**
     * @return string|bool|null
     */
    public function get_label()
    {
        return $this->label;
    }
    /**
     * @param string|bool|null $label
     */
    public function set_label($label): void
    {
        $this->label = $label;
    }
    public function is_enabled(): bool
    {
        return $this->enabled;
    }
    public function set_enabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }
    public function get_template(): ?string
    {
        return $this->template;
    }
    public function set_template(string $template): void
    {
        $this->template = $template;
    }
    /**
     * @return array<string, mixed>
     */
    public function get_options(): array
    {
        return $this->options;
    }
    /**
     * @param array<string, mixed> $options
     */
    public function set_options(array $options): void
    {
        $this->options = $options;
    }
    /**
     * @return array<string, mixed>
     */
    public function get_form_options(): array
    {
        return $this->form_options;
    }
    /**
     * @param array<string, mixed> $formOptions
     */
    public function set_form_options(array $form_options): void
    {
        $this->form_options = $form_options;
    }
    public function get_position(): int
    {
        return $this->position;
    }
    public function set_position(int $position): void
    {
        $this->position = $position;
    }
    /**
     * @return mixed
     */
    public function get_criteria()
    {
        return $this->criteria;
    }
    /**
     * @param mixed $criteria
     */
    public function set_criteria($criteria): void
    {
        $this->criteria = $criteria;
    }
}