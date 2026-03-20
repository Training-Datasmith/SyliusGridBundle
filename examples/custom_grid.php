<?php

declare(strict_types=1);

/**
 * SyliusGridBundle — defining and using a custom grid example.
 *
 * Shows both attribute-based and YAML-based grid definition, plus
 * how to render a grid in a controller.
 *
 * --- Option A: PHP attribute grid (recommended for PHP 8.1+) ---
 *
 * use Sylius\Bundle\GridBundle\Builder\GridBuilderInterface;
 * use Sylius\Bundle\GridBundle\Builder\Field\StringField;
 * use Sylius\Bundle\GridBundle\Builder\Field\DateTimeField;
 * use Sylius\Bundle\GridBundle\Builder\Filter\StringFilter;
 * use Sylius\Bundle\GridBundle\Builder\Action\CreateAction;
 * use Sylius\Bundle\GridBundle\Builder\Action\UpdateAction;
 * use Sylius\Bundle\GridBundle\Builder\Action\DeleteAction;
 * use Sylius\Component\Grid\Attribute\AsGrid;
 *
 * #[AsGrid(resourceClass: Product::class)]
 * final class ProductGrid implements GridBuilderInterface
 * {
 *     public static function buildGrid(GridBuilderInterface $grid): void
 *     {
 *         $grid
 *             ->addField(StringField::create('name')->setLabel('Name')->setSortable(true))
 *             ->addField(DateTimeField::create('createdAt')->setLabel('Created'))
 *             ->addFilter(StringFilter::create('search', ['name', 'sku']))
 *             ->addActionGroup(
 *                 MainActionGroup::create(CreateAction::create())
 *             )
 *             ->addActionGroup(
 *                 ItemActionGroup::create(
 *                     UpdateAction::create(),
 *                     DeleteAction::create(),
 *                 )
 *             )
 *         ;
 *     }
 * }
 *
 * --- Option B: YAML grid definition ---
 *
 * # config/packages/sylius_grid.yaml
 * sylius_grid:
 *     grids:
 *         app_product:
 *             resource_class: App\Entity\Product
 *             fields:
 *                 name:
 *                     type: string
 *                     label: Name
 *                     sortable: ~
 *             filters:
 *                 search:
 *                     type: string
 *                     fields: [name, sku]
 *             actions:
 *                 main:
 *                     create: { type: create }
 *                 item:
 *                     update: { type: update }
 *                     delete: { type: delete }
 *
 * --- Rendering in a controller ---
 *
 * use Sylius\Bundle\GridBundle\Provider\ServiceGridProvider;
 *
 * class ProductController extends AbstractController
 * {
 *     public function index(ServiceGridProvider $gridProvider, Request $request): Response
 *     {
 *         $grid = $gridProvider->get('app_product', $request->query->all());
 *
 *         return $this->render('product/index.html.twig', ['grid' => $grid]);
 *     }
 * }
 *
 * --- Twig template ---
 *
 * {{ sylius_grid(grid) }}
 */

echo 'SyliusGridBundle requires a Symfony kernel.' . PHP_EOL;
echo 'See the docblock above for grid definition patterns.' . PHP_EOL;
