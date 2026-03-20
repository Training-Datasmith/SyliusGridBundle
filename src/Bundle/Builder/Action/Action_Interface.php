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

/**
 * @method string getTemplate()
 * @method self setTemplate(string $template)
 */
interface Action_Interface
{
    public static function create(string $name, string $type): self;
    public function get_name(): string;
    public function set_label(string $label): self;
    public function set_enabled(bool $enabled): self;
    public function set_icon(string $icon): self;
    /**
     * @param array<string, mixed> $options
     */
    public function set_options(array $options): self;
    public function set_position(int $position): self;
    /**
     * @return array<string, mixed>
     */
    public function to_array(): array;
}