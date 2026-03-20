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
namespace Sylius\Bundle\Grid_Bundle\Maker;

use Doctrine\ORM\Entity_Manager_Interface;
use Doctrine\Persistence\Manager_Registry;
use Symfony\Bundle\Maker_Bundle\Console_Style;
use Symfony\Bundle\Maker_Bundle\Dependency_Builder;
use Symfony\Bundle\Maker_Bundle\Exception\Runtime_Command_Exception;
use Symfony\Bundle\Maker_Bundle\Generator;
use Symfony\Bundle\Maker_Bundle\Input_Configuration;
use Symfony\Bundle\Maker_Bundle\Maker\Abstract_Maker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\Input_Argument;
use Symfony\Component\Console\Input\Input_Interface;
use Symfony\Component\Console\Input\Input_Option;
use Webmozart\Assert\Assert;
final class Make_Grid extends Abstract_Maker
{
    public function __construct(private readonly ?Manager_Registry $manager_registry = null)
    {
    }
    public static function get_command_name(): string
    {
        return 'make:grid';
    }
    public static function get_command_description(): string
    {
        return 'Creates a Sylius Grid configuration for a given resource class or Doctrine entity.';
    }
    public function configure_command(Command $command, Input_Configuration $input_config): void
    {
        $command->set_description(self::get_command_description())->add_argument('entity', Input_Argument::OPTIONAL, 'Entity class to create a grid for')->add_option('namespace', null, Input_Option::VALUE_REQUIRED, 'Customize the namespace for generated grids', 'Grid');
        $input_config->set_argument_as_non_interactive('entity');
    }
    public function interact(Input_Interface $input, Console_Style $io, Command $command): void
    {
        if ($input->get_argument('entity')) {
            return;
        }
        $argument = $command->get_definition()->get_argument('entity');
        $entity = $io->choice($argument->get_description(), $this->entity_choices());
        $input->set_argument('entity', $entity);
    }
    public function generate(Input_Interface $input, Console_Style $io, Generator $generator): void
    {
        /** @var class-string $class */
        $class = $input->get_argument('entity');
        if (!\class_exists($class)) {
            $class = $generator->create_class_name_details($class, 'Entity\\')->get_full_name();
        }
        if (!\class_exists($class)) {
            /** @var class-string $entityArg */
            $entity_arg = $input->get_argument('entity');
            throw new Runtime_Command_Exception(\sprintf('Entity "%s" not found.', is_string($entity_arg) ? $entity_arg : 'unknown'));
        }
        /** @var string $namespace */
        $namespace = $input->get_option('namespace');
        // strip maker's root namespace if set
        if (0 === \mb_strpos($namespace, $generator->get_root_namespace())) {
            $namespace = \mb_substr($namespace, \mb_strlen($generator->get_root_namespace()));
        }
        $namespace = \trim($namespace, '\\');
        $entity = new \ReflectionClass($class);
        $grid = $generator->create_class_name_details($entity->get_short_name(), $namespace, 'Grid');
        $generator->generate_class($grid->get_full_name(), __DIR__ . '/../Resources/config/skeleton/Grid.tpl.php', ['entity' => $entity, 'defaultFields' => $this->default_fields_for($entity->get_name())]);
        $generator->write_changes();
        $this->write_success_message($io);
    }
    public function configure_dependencies(Dependency_Builder $dependencies): void
    {
        // No dependencies needed
    }
    /**
     * @return string[]
     */
    private function entity_choices(): array
    {
        $choices = [];
        foreach ($this->manager_registry?->get_managers() ?? [] as $manager) {
            foreach ($manager->get_metadata_factory()->get_all_metadata() as $metadata) {
                $choices[] = $metadata->get_name();
            }
        }
        \sort($choices);
        if (empty($choices)) {
            throw new Runtime_Command_Exception('No entities found.');
        }
        return $choices;
    }
    /**
     * @param class-string $class
     *
     * @return iterable<string, string|null>
     */
    private function default_fields_for(string $class): iterable
    {
        $entity_manager = $this->manager_registry?->get_manager_for_class($class);
        if (!$entity_manager instanceof Entity_Manager_Interface) {
            $metadata = new \ReflectionClass($class);
            $field_mappings = $metadata->get_properties();
            foreach ($field_mappings as $property) {
                // ignore identifier
                if ('id' === $property->get_name()) {
                    continue;
                }
                Assert::is_instance_of($property->get_type(), \ReflectionNamedType::class);
                $property_type = $property->get_type()->get_name();
                $type = $property_type ? \mb_strtoupper((string) $property_type) : null;
                yield $property->get_name() => $type;
            }
            return;
        }
        $metadata = $entity_manager->get_class_metadata($class);
        $ids = $metadata->get_identifier_field_names();
        foreach ($metadata->field_mappings as $property) {
            // ignore identifiers
            if (\in_array($property['fieldName'], $ids, true)) {
                continue;
            }
            $field_name = $property['fieldName'];
            $type = $property['type'];
            if (!\is_string($type)) {
                continue;
            }
            if (!\is_string($field_name)) {
                continue;
            }
            yield $field_name => \mb_strtoupper($type);
        }
    }
}