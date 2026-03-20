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

use Sylius\Bundle\Currency_Bundle\Form\Type\Currency_Choice_Type;
use Sylius\Component\Grid\Filter\Money_Filter;
use Symfony\Component\Form\Abstract_Type;
use Symfony\Component\Form\Extension\Core\Type\Number_Type;
use Symfony\Component\Form\Form_Builder_Interface;
use Symfony\Component\Options_Resolver\Options_Resolver;
\trigger_deprecation('sylius/grid-bundle', '1.8', '%s is deprecated, replace it with your own implementation.', Money_Filter_Type::class);
/**
 * @extends AbstractType<mixed>
 */
final class Money_Filter_Type extends Abstract_Type
{
    public function build_form(Form_Builder_Interface $builder, array $options): void
    {
        $builder->add('greaterThan', Number_Type::class, ['label' => 'sylius.ui.greater_than', 'required' => false, 'scale' => $options['scale']])->add('lessThan', Number_Type::class, ['label' => 'sylius.ui.less_than', 'required' => false, 'scale' => $options['scale']])->add('currency', Currency_Choice_Type::class, ['label' => 'sylius.ui.currency', 'placeholder' => '---', 'required' => false]);
    }
    public function configure_options(Options_Resolver $resolver): void
    {
        $resolver->set_defaults(['data_class' => null, 'scale' => Money_Filter::DEFAULT_SCALE])->set_allowed_types('scale', ['string', 'int']);
    }
    public function get_block_prefix(): string
    {
        return 'sylius_grid_filter_money';
    }
}