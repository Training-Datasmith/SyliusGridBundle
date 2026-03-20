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
namespace Sylius\Bundle\Grid_Bundle\Command;

use Sylius\Bundle\Grid_Bundle\Grid\Grid_Interface;
use Sylius\Component\Grid\Provider\Grid_Provider_Interface;
use Symfony\Component\Console\Attribute\As_Command;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Dumper;
use Symfony\Component\Console\Input\Input_Argument;
use Symfony\Component\Console\Input\Input_Interface;
use Symfony\Component\Console\Output\Output_Interface;
use Symfony\Component\Console\Style\Symfony_Style;
use Symfony\Component\Property_Access\Property_Access;
use Symfony\Contracts\Service\Service_Provider_Interface;
#[As_Command(name: 'sylius:debug:grid', description: 'Debug grid configuration')]
final class Debug_Grid_Command extends Command
{
    /**
     * @param ServiceProviderInterface<GridInterface> $taggedGrids
     * @param array<string, mixed> $gridConfigurations
     */
    public function __construct(private readonly Grid_Provider_Interface $grid_provider, private readonly Service_Provider_Interface $tagged_grids, private readonly array $grid_configurations)
    {
        parent::__construct();
    }
    protected function configure(): void
    {
        $this->add_argument(name: 'grid', mode: Input_Argument::OPTIONAL, description: 'The name or fully-qualified class name (FQCN) of the grid to debug')->set_help(<<<'EOF'
        The <info>%command.name%</info> command displays all configured grids:
        
          <info>php %command.full_name%</info>
          
        To get specific grid, specify its name (or FQCN):
        
          <info>php %command.full_name% sylius_product</info>
          
          <info>php %command.full_name% App\Grid\SupplierGrid</info>
        EOF);
    }
    public function interact(Input_Interface $input, Output_Interface $output): void
    {
        if ($input->get_argument('grid')) {
            return;
        }
        $io = new Symfony_Style($input, $output);
        $entity = $io->choice('Which grid do you want to debug?', $this->get_grid_choices());
        $input->set_argument('grid', $entity);
    }
    protected function execute(Input_Interface $input, Output_Interface $output): int
    {
        $io = new Symfony_Style($input, $output);
        $dumper = new Dumper($output);
        /** @var string $grid */
        $grid = $input->get_argument('grid');
        $grid_definition = $this->grid_provider->get($grid);
        $io->title(sprintf('Definition of "%s" Grid', $grid_definition->get_code()));
        $rows = [];
        foreach ($this->object_to_array($grid_definition) as $key => $value) {
            $rows[] = [$key, $dumper($value)];
        }
        $io->table(['Option', 'Value'], $rows);
        return Command::SUCCESS;
    }
    /**
     * @return array<string, mixed>
     */
    private function object_to_array(object $object): array
    {
        $accessor = Property_Access::create_property_accessor();
        $reflection = new \ReflectionClass($object);
        $values = [];
        foreach ($reflection->get_properties() as $property) {
            $property_name = $property->get_name();
            if ($accessor->is_readable($object, $property_name)) {
                $values[$property->get_name()] = $accessor->get_value($object, $property_name);
            }
        }
        return $values;
    }
    /**
     * @return array<int, string>
     */
    private function get_grid_choices(): array
    {
        $grids = array_merge($this->tagged_grids->get_provided_services(), array_keys($this->grid_configurations));
        \sort($grids);
        return $grids;
    }
}