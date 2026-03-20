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
namespace Sylius\Bundle\Grid_Bundle\Templating\Helper;

use Sylius\Component\Grid\Definition\Action;
use Sylius\Component\Grid\Renderer\Bulk_Action_Grid_Renderer_Interface;
use Sylius\Component\Grid\View\Grid_View;
/**
 * @final
 */
class Bulk_Action_Grid_Helper
{
    public function __construct(private readonly Bulk_Action_Grid_Renderer_Interface $bulk_action_grid_renderer)
    {
    }
    /**
     * @param mixed|null $data
     */
    public function render_bulk_action(Grid_View $grid_view, Action $bulk_action, $data = null): string
    {
        return $this->bulk_action_grid_renderer->render_bulk_action($grid_view, $bulk_action, $data);
    }
}