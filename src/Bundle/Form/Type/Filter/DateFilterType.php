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

use Sylius\Bundle\Grid_Bundle\Form\Data_Transformer\Date_Time_Filter_Transformer;
use Symfony\Component\Form\Abstract_Type;
use Symfony\Component\Form\Extension\Core\Type\Date_Time_Type;
use Symfony\Component\Form\Form_Builder_Interface;
use Symfony\Component\Options_Resolver\Options_Resolver;
/**
 * @extends AbstractType<mixed>
 */
final class Date_Filter_Type extends Abstract_Type
{
    public function build_form(Form_Builder_Interface $builder, array $options): void
    {
        $builder->add('from', Date_Time_Type::class, ['label' => 'sylius.ui.from', 'date_widget' => 'single_text', 'time_widget' => 'single_text', 'required' => false])->add('to', Date_Time_Type::class, ['label' => 'sylius.ui.to', 'date_widget' => 'single_text', 'time_widget' => 'single_text', 'required' => false]);
        $builder->get('from')->add_view_transformer(new Date_Time_Filter_Transformer('from'));
        $builder->get('to')->add_view_transformer(new Date_Time_Filter_Transformer('to'));
    }
    public function configure_options(Options_Resolver $resolver): void
    {
        $resolver->set_defaults(['data_class' => null]);
    }
    public function get_block_prefix(): string
    {
        return 'sylius_grid_filter_date';
    }
}