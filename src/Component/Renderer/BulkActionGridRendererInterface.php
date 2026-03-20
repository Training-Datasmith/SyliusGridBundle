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
namespace Sylius\Component\Grid\Renderer;

use Sylius\Component\Grid\Definition\Action;
use Sylius\Component\Grid\View\Grid_View_Interface;
interface Bulk_Action_Grid_Renderer_Interface
{
    /**
     * @param mixed|null $data
     */
    public function render_bulk_action(Grid_View_Interface $grid_view, Action $bulk_action, $data = null): string;
}