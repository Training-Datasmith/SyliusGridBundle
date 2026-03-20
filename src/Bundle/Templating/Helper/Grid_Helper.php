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
use Sylius\Component\Grid\Definition\Field;
use Sylius\Component\Grid\Definition\Filter;
use Sylius\Component\Grid\Renderer\Grid_Renderer_Interface;
use Sylius\Component\Grid\View\Grid_View;
class Grid_Helper
{
    public function __construct(private readonly Grid_Renderer_Interface $grid_renderer)
    {
    }
    /**
     * @return string
     */
    public function render_grid(Grid_View $grid_view, ?string $template = null)
    {
        return $this->grid_renderer->render($grid_view, $template);
    }
    /**
     * @param mixed $data
     *
     * @return string
     */
    public function render_field(Grid_View $grid_view, Field $field, $data)
    {
        return $this->grid_renderer->render_field($grid_view, $field, $data);
    }
    /**
     * @param mixed|null $data
     *
     * @return string
     */
    public function render_action(Grid_View $grid_view, Action $action, $data = null)
    {
        return $this->grid_renderer->render_action($grid_view, $action, $data);
    }
    /**
     * @return string
     */
    public function render_filter(Grid_View $grid_view, Filter $filter)
    {
        return $this->grid_renderer->render_filter($grid_view, $filter);
    }
}