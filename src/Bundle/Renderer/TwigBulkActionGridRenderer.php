<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\GridBundle\Renderer;

use Sylius\Component\Grid\Definition\Action;
use Sylius\Component\Grid\Renderer\BulkActionGridRendererInterface;
use Sylius\Component\Grid\View\GridViewInterface;
use Twig\Environment;

final class TwigBulkActionGridRenderer implements BulkActionGridRendererInterface
{
    private readonly Environment $twig;

    /**
     * @param array<string, string> $bulkActionTemplates
     */
    public function __construct(Environment $twig, private array $bulkActionTemplates)
    {
        $this->twig = $twig;
    }

    public function renderBulkAction(GridViewInterface $gridView, Action $bulkAction, $data = null): string
    {
        $type = $bulkAction->getType();
        if (!isset($this->bulkActionTemplates[$type])) {
            throw new \InvalidArgumentException(sprintf('Missing template for bulk action type "%s".', $type));
        }

        return $this->twig->render($this->bulkActionTemplates[$type], [
            'grid' => $gridView,
            'action' => $bulkAction,
            'data' => $data,
        ]);
    }
}
