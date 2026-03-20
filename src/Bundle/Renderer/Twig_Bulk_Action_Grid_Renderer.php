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
namespace Sylius\Bundle\Grid_Bundle\Renderer;

use Sylius\Component\Grid\Definition\Action;
use Sylius\Component\Grid\Renderer\Bulk_Action_Grid_Renderer_Interface;
use Sylius\Component\Grid\View\Grid_View_Interface;
use Twig\Environment;
final class Twig_Bulk_Action_Grid_Renderer implements Bulk_Action_Grid_Renderer_Interface
{
    private readonly Environment $twig;
    /**
     * @param array<string, string> $bulkActionTemplates
     */
    public function __construct(Environment $twig, private array $bulk_action_templates)
    {
        $this->twig = $twig;
    }
    public function render_bulk_action(Grid_View_Interface $grid_view, Action $bulk_action, $data = null): string
    {
        $type = $bulk_action->get_type();
        if (!isset($this->bulk_action_templates[$type])) {
            throw new \InvalidArgumentException(sprintf('Missing template for bulk action type "%s".', $type));
        }
        return $this->twig->render($this->bulk_action_templates[$type], ['grid' => $grid_view, 'action' => $bulk_action, 'data' => $data]);
    }
}