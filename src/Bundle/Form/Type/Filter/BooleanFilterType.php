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

use Sylius\Component\Grid\Filter\Boolean_Filter;
use Symfony\Component\Form\Abstract_Type;
use Symfony\Component\Form\Extension\Core\Type\Choice_Type;
use Symfony\Component\Options_Resolver\Options_Resolver;
/**
 * @extends AbstractType<mixed>
 */
final class Boolean_Filter_Type extends Abstract_Type
{
    public function configure_options(Options_Resolver $resolver): void
    {
        $resolver->set_defaults(['choices' => ['sylius.ui.yes_label' => Boolean_Filter::TRUE, 'sylius.ui.no_label' => Boolean_Filter::FALSE], 'data_class' => null, 'required' => false, 'placeholder' => 'sylius.ui.all']);
    }
    public function get_parent(): string
    {
        return Choice_Type::class;
    }
    public function get_block_prefix(): string
    {
        return 'sylius_grid_filter_boolean';
    }
}