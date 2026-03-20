<?php

declare (strict_types=1);
use Symfony\Bundle\Maker_Bundle\Str;
echo "<?php\n";
?>

namespace <?php 
echo $namespace;
?>;

use <?php 
echo $entity->get_name();
?>;
use Sylius\Bundle\GridBundle\Builder\Action\CreateAction;
use Sylius\Bundle\GridBundle\Builder\Action\DeleteAction;
use Sylius\Bundle\GridBundle\Builder\Action\ShowAction;
use Sylius\Bundle\GridBundle\Builder\Action\UpdateAction;
use Sylius\Bundle\GridBundle\Builder\ActionGroup\BulkActionGroup;
use Sylius\Bundle\GridBundle\Builder\ActionGroup\ItemActionGroup;
use Sylius\Bundle\GridBundle\Builder\ActionGroup\MainActionGroup;
use Sylius\Bundle\GridBundle\Builder\Field\DateTimeField;
use Sylius\Bundle\GridBundle\Builder\Field\StringField;
use Sylius\Bundle\GridBundle\Builder\Field\TwigField;
use Sylius\Bundle\GridBundle\Builder\GridBuilderInterface;
use Sylius\Bundle\GridBundle\Grid\AbstractGrid;
use Sylius\Component\Grid\Attribute\AsGrid;

#[AsGrid(
    resourceClass: <?php 
echo $entity->get_short_name();
?>::class,
    name: 'app_<?php 
echo Str::as_snake_case($entity->get_short_name());
?>',
)]
final class <?php 
echo $class_name;
?> extends AbstractGrid
{
    public function __construct()
    {
        // TODO inject services if required
    }

    public function __invoke(GridBuilderInterface $gridBuilder): void
    {
        $gridBuilder
            // see https://stack.sylius.com/grid/index/filters
            // ->addFilters()
            // see https://stack.sylius.com/grid/index/field_types
            ->addFields(
<?php 
foreach ($default_fields as $fieldname => $type) {
    if (in_array($type, ['STRING', 'TEXT'], true)) {
        echo "                StringField::create('" . $fieldname . "')\n";
        echo "                    ->setLabel('" . ucfirst((string) $fieldname) . "')\n";
        echo "                    ->setSortable(true),\n";
    }
    if (str_starts_with((string) $type, 'DATE')) {
        echo "                DateTimeField::create('" . $fieldname . "')\n";
        echo "                    ->setLabel('" . ucfirst((string) $fieldname) . "'),\n";
    }
    if (in_array($type, ['BOOLEAN', 'BOOL'], true)) {
        echo "            //    TwigField::create('" . $fieldname . "', 'path/to/field/template.html.twig')\n";
        echo "            //        ->setLabel('" . ucfirst((string) $fieldname) . "'),\n";
    }
}
?>
            )
            ->addActionGroup(
                MainActionGroup::create(
                    CreateAction::create(),
                )
            )
            ->addActionGroup(
                ItemActionGroup::create(
                    // ShowAction::create(),
                    UpdateAction::create(),
                    DeleteAction::create()
                )
            )
            ->addActionGroup(
                BulkActionGroup::create(
                    DeleteAction::create()
                )
            )
        ;
    }
}
