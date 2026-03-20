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

use Sylius\Bundle\Grid_Bundle\Templating\Helper\Bulk_Action_Grid_Helper;
use Twig\Extension\Abstract_Extension;
use Twig\Twig_Function;
final class Bulk_Action_Grid_Extension extends Abstract_Extension
{
    public function __construct(private readonly Bulk_Action_Grid_Helper $bulk_action_grid_helper)
    {
    }
    public function get_functions(): array
    {
        return [new Twig_Function('sylius_grid_render_bulk_action', $this->bulk_action_grid_helper->render_bulk_action(...), ['is_safe' => ['html']])];
    }
}