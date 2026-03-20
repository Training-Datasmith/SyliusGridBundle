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

use Symfony\Bridge\Doctrine\Form\Type\Entity_Type;
use Symfony\Component\Form\Abstract_Type;
use Symfony\Component\Options_Resolver\Options_Resolver;
/**
 * @extends AbstractType<mixed>
 */
final class Entity_Filter_Type extends Abstract_Type
{
    public function configure_options(Options_Resolver $resolver): void
    {
        $resolver->set_defaults(['class' => null, 'label' => false, 'placeholder' => 'sylius.ui.all']);
    }
    public function get_parent(): string
    {
        return Entity_Type::class;
    }
    public function get_block_prefix(): string
    {
        return 'sylius_grid_filter_entity';
    }
}