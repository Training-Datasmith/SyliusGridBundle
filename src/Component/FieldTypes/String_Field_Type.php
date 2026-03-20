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
namespace Sylius\Component\Grid\Field_Types;

use Sylius\Component\Grid\Data_Extractor\Data_Extractor_Interface;
use Sylius\Component\Grid\Definition\Field;
use Symfony\Component\Options_Resolver\Options_Resolver;
final readonly class String_Field_Type implements Field_Type_Interface
{
    public function __construct(private Data_Extractor_Interface $data_extractor)
    {
    }
    public function render(Field $field, mixed $data, array $options): string
    {
        /** @var string|null $value */
        $value = $this->data_extractor->get($field, $data);
        return htmlspecialchars((string) $value);
    }
    public function configure_options(Options_Resolver $resolver): void
    {
        $resolver->set_defined('vars');
        $resolver->set_allowed_types('vars', 'array');
    }
}