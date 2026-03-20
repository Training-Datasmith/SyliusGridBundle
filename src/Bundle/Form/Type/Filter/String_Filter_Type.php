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
namespace Sylius\Bundle\Grid_Bundle\Form\Type\Filter;

use Sylius\Component\Grid\Filter\String_Filter;
use Symfony\Component\Form\Abstract_Type;
use Symfony\Component\Form\Extension\Core\Type\Choice_Type;
use Symfony\Component\Form\Extension\Core\Type\Text_Type;
use Symfony\Component\Form\Form_Builder_Interface;
use Symfony\Component\Options_Resolver\Options_Resolver;
/**
 * @extends AbstractType<mixed>
 */
final class String_Filter_Type extends Abstract_Type
{
    public function build_form(Form_Builder_Interface $builder, array $options): void
    {
        if (!isset($options['type'])) {
            $builder->add('type', Choice_Type::class, ['choices' => ['sylius.ui.contains' => String_Filter::TYPE_CONTAINS, 'sylius.ui.not_contains' => String_Filter::TYPE_NOT_CONTAINS, 'sylius.ui.equal' => String_Filter::TYPE_EQUAL, 'sylius.ui.not_equal' => String_Filter::TYPE_NOT_EQUAL, 'sylius.ui.empty' => String_Filter::TYPE_EMPTY, 'sylius.ui.not_empty' => String_Filter::TYPE_NOT_EMPTY, 'sylius.ui.starts_with' => String_Filter::TYPE_STARTS_WITH, 'sylius.ui.ends_with' => String_Filter::TYPE_ENDS_WITH, 'sylius.ui.in' => String_Filter::TYPE_IN, 'sylius.ui.not_in' => String_Filter::TYPE_NOT_IN]]);
        }
        $builder->add('value', Text_Type::class, ['required' => false, 'label' => 'sylius.ui.value']);
    }
    public function configure_options(Options_Resolver $resolver): void
    {
        $resolver->set_defaults(['data_class' => null])->set_defined('type')->set_allowed_values('type', [String_Filter::TYPE_CONTAINS, String_Filter::TYPE_NOT_CONTAINS, String_Filter::TYPE_EQUAL, String_Filter::TYPE_NOT_EQUAL, String_Filter::TYPE_EMPTY, String_Filter::TYPE_NOT_EMPTY, String_Filter::TYPE_STARTS_WITH, String_Filter::TYPE_ENDS_WITH, String_Filter::TYPE_IN, String_Filter::TYPE_NOT_IN]);
    }
    public function get_block_prefix(): string
    {
        return 'sylius_grid_filter_string';
    }
}