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

use Sylius\Component\Grid\Filter\Numeric_Range_Filter;
use Symfony\Component\Form\Abstract_Type;
use Symfony\Component\Form\Extension\Core\Type\Number_Type;
use Symfony\Component\Form\Form_Builder_Interface;
use Symfony\Component\Options_Resolver\Options_Resolver;
/**
 * @extends AbstractType<mixed>
 */
final class Numeric_Range_Filter_Type extends Abstract_Type
{
    public function build_form(Form_Builder_Interface $builder, array $options): void
    {
        $builder->add('greaterThan', Number_Type::class, ['label' => 'sylius.ui.greater_than', 'required' => false, 'scale' => $options['scale'], 'rounding_mode' => $options['rounding_mode']])->add('lessThan', Number_Type::class, ['label' => 'sylius.ui.less_than', 'required' => false, 'scale' => $options['scale'], 'rounding_mode' => $options['rounding_mode']]);
    }
    public function configure_options(Options_Resolver $resolver): void
    {
        $resolver->set_defaults(['data_class' => null, 'scale' => Numeric_Range_Filter::DEFAULT_SCALE, 'rounding_mode' => Numeric_Range_Filter::DEFAULT_ROUNDING_MODE])->set_allowed_types('scale', ['string', 'int'])->set_allowed_types('rounding_mode', ['string', 'int']);
    }
    public function get_block_prefix(): string
    {
        return 'sylius_grid_filter_numeric_range';
    }
}