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
namespace Sylius\Bundle\Grid_Bundle\Field_Types;

use Sylius\Component\Grid\Data_Extractor\Data_Extractor_Interface;
use Sylius\Component\Grid\Definition\Field;
use Sylius\Component\Grid\Field_Types\Field_Type_Interface;
use Symfony\Component\Options_Resolver\Options_Resolver;
use Twig\Environment;
final readonly class Twig_Field_Type implements Field_Type_Interface
{
    private Environment $twig;
    public function __construct(private Data_Extractor_Interface $data_extractor, Environment $twig)
    {
        $this->twig = $twig;
    }
    public function render(Field $field, mixed $data, array $options): string
    {
        if ('.' !== $field->get_path()) {
            $data = $this->data_extractor->get($field, $data);
        }
        /** @var string $template */
        $template = $options['template'];
        return $this->twig->render($template, ['data' => $data, 'options' => $options]);
    }
    public function configure_options(Options_Resolver $resolver): void
    {
        $resolver->set_required('template');
        $resolver->set_allowed_types('template', 'string');
        $resolver->set_defined('vars');
        $resolver->set_allowed_types('vars', 'array');
    }
}