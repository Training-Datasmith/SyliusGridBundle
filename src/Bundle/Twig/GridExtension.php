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
namespace Sylius\Bundle\Grid_Bundle\Twig;

use Sylius\Bundle\Grid_Bundle\Templating\Helper\Grid_Helper;
use Twig\Extension\Abstract_Extension;
use Twig\Twig_Function;
final class Grid_Extension extends Abstract_Extension
{
    public function __construct(private readonly Grid_Helper $grid_helper)
    {
    }
    public function get_functions(): array
    {
        return [new Twig_Function('sylius_grid_render', $this->grid_helper->render_grid(...), ['is_safe' => ['html']]), new Twig_Function('sylius_grid_render_field', $this->grid_helper->render_field(...), ['is_safe' => ['html']]), new Twig_Function('sylius_grid_render_action', $this->grid_helper->render_action(...), ['is_safe' => ['html']]), new Twig_Function('sylius_grid_render_filter', $this->grid_helper->render_filter(...), ['is_safe' => ['html']])];
    }
}