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
use Sylius\Component\Grid\Definition\Field;
use Sylius\Component\Grid\Definition\Filter;
use Sylius\Component\Grid\View\Grid_View_Interface;
interface Grid_Renderer_Interface
{
    /**
     * @return string
     */
    public function render(Grid_View_Interface $grid_view, ?string $template = null);
    /**
     * @param mixed $data
     *
     * @return string
     */
    public function render_field(Grid_View_Interface $grid_view, Field $field, $data);
    /**
     * @param mixed|null $data
     *
     * @return string
     */
    public function render_action(Grid_View_Interface $grid_view, Action $action, $data = null);
    /**
     * @return string
     */
    public function render_filter(Grid_View_Interface $grid_view, Filter $filter);
}