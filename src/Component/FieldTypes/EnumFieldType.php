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
use Sylius\Component\Grid\Exception\LogicException;
use Symfony\Component\Options_Resolver\Options_Resolver;
use Symfony\Contracts\Translation\Translatable_Interface;
use Symfony\Contracts\Translation\Translator_Interface;
use Webmozart\Assert\Assert;
final readonly class Enum_Field_Type implements Field_Type_Interface
{
    public function __construct(private Data_Extractor_Interface $data_extractor, private ?Translator_Interface $translator = null)
    {
    }
    public function render(Field $field, mixed $data, array $options): string
    {
        $enum = $this->data_extractor->get($field, $data);
        if (null === $enum) {
            return '';
        }
        Assert::is_instance_of($enum, \Unit_Enum::class);
        if ($enum instanceof Translatable_Interface) {
            if (null === $this->translator) {
                throw new LogicException('You have configured a translatable enum, but Symfony translator is not available. Try running "composer require symfony/translator".');
            }
            return $enum->trans($this->translator);
        }
        if ($enum instanceof \Backed_Enum) {
            return (string) $enum->value;
        }
        return $enum->name;
    }
    public function configure_options(Options_Resolver $resolver): void
    {
        $resolver->set_defined('vars');
        $resolver->set_allowed_types('vars', 'array');
    }
}