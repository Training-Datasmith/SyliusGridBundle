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
namespace Sylius\Component\Grid\Definition;

use Sylius\Component\Grid\Event\Grid_Definition_Converter_Event;
use Symfony\Contracts\Event_Dispatcher\Event_Dispatcher_Interface;
/**
 * @template TDriver of array{
 *     name: string,
 *     options?: array<string, mixed>,
 * }
 * @template TField of array{
 *         type: string,
 *         label?: string,
 *         path?: string,
 *         enabled?: bool,
 *         sortable?: bool|string,
 *         position?: int,
 *         options?: array<string, mixed>,
 *  }
 * @template TFilter of array{
 *         type: string,
 *         label?: string,
 *         template?: string,
 *         enabled?: bool,
 *         position?: int,
 *         options?: array<string, mixed>,
 *         form_options?: array<string, mixed>,
 *         default_value?: mixed,
 *  }
 * @template TActionGroup of array<string, TAction>
 * @template TAction of array{
 *         type: string,
 *         label?: string,
 *         template?: string,
 *         icon?: string,
 *         enabled?: bool,
 *         position?: int,
 *         options?: array<string, mixed>,
 *  }
 */
final readonly class Array_To_Definition_Converter implements Array_To_Definition_Converter_Interface
{
    public const EVENT_NAME = 'sylius.grid.%s';
    public function __construct(private Event_Dispatcher_Interface $event_dispatcher)
    {
    }
    /**
     * @param array{
     *        driver: TDriver,
     *        provider?: string|callable,
     *        sorting?: array<string, string>,
     *        limits?: int[],
     *        fields?: array<string, TField>,
     *        filters?: array<string, TFilter>,
     *        actions?: array<string, TActionGroup>,
     * } $configuration
     */
    public function convert(string $code, array $configuration): Grid
    {
        $grid = Grid::from_code_and_driver_configuration($code, $configuration['driver']['name'], $configuration['driver']['options'] ?? []);
        $grid->set_provider($configuration['provider'] ?? null);
        if (array_key_exists('sorting', $configuration)) {
            $grid->set_sorting($configuration['sorting']);
        }
        if (array_key_exists('limits', $configuration)) {
            $grid->set_limits($configuration['limits']);
        }
        /** @var TField $fieldConfiguration */
        foreach ($configuration['fields'] ?? [] as $name => $field_configuration) {
            $grid->add_field($this->convert_field($name, $field_configuration));
        }
        /** @var TFilter $filterConfiguration */
        foreach ($configuration['filters'] ?? [] as $name => $filter_configuration) {
            $grid->add_filter($this->convert_filter($name, $filter_configuration));
        }
        /** @var TActionGroup $actionGroupConfiguration */
        foreach ($configuration['actions'] ?? [] as $name => $action_group_configuration) {
            $grid->add_action_group($this->convert_action_group($name, $action_group_configuration));
        }
        $this->event_dispatcher->dispatch(new Grid_Definition_Converter_Event($grid), $this->get_event_name($code));
        return $grid;
    }
    /**
     * @param TField $configuration
     */
    private function convert_field(string $name, array $configuration): Field
    {
        $field = Field::from_name_and_type($name, $configuration['type']);
        if (array_key_exists('path', $configuration)) {
            $field->set_path($configuration['path']);
        }
        if (array_key_exists('label', $configuration)) {
            $field->set_label($configuration['label']);
        }
        if (array_key_exists('enabled', $configuration)) {
            $field->set_enabled($configuration['enabled']);
        }
        if (array_key_exists('sortable', $configuration)) {
            $sortable = $configuration['sortable'];
            if ($sortable === true || $sortable === null) {
                $sortable = $name;
            }
            if ($sortable === false) {
                $sortable = null;
            }
            $field->set_sortable($sortable);
        }
        if (array_key_exists('position', $configuration)) {
            $field->set_position($configuration['position']);
        }
        if (array_key_exists('options', $configuration)) {
            $field->set_options($configuration['options']);
        }
        return $field;
    }
    /**
     * @param TFilter $configuration
     */
    private function convert_filter(string $name, array $configuration): Filter
    {
        $filter = Filter::from_name_and_type($name, $configuration['type']);
        if (array_key_exists('label', $configuration)) {
            $filter->set_label($configuration['label']);
        }
        if (array_key_exists('template', $configuration)) {
            $filter->set_template($configuration['template']);
        }
        if (array_key_exists('enabled', $configuration)) {
            $filter->set_enabled($configuration['enabled']);
        }
        if (array_key_exists('position', $configuration)) {
            $filter->set_position($configuration['position']);
        }
        if (array_key_exists('options', $configuration)) {
            $filter->set_options($configuration['options']);
        }
        if (array_key_exists('form_options', $configuration)) {
            $filter->set_form_options($configuration['form_options']);
        }
        if (array_key_exists('default_value', $configuration)) {
            $filter->set_criteria($configuration['default_value']);
        }
        return $filter;
    }
    /**
     * @param TActionGroup $configuration
     */
    private function convert_action_group(string $name, array $configuration): Action_Group
    {
        $action_group = Action_Group::named($name);
        foreach ($configuration as $action_name => $action_configuration) {
            $action_group->add_action($this->convert_action($action_name, $action_configuration));
        }
        return $action_group;
    }
    /**
     * @param TAction $configuration
     */
    private function convert_action(string $name, array $configuration): Action
    {
        $action = Action::from_name_and_type($name, $configuration['type']);
        if (array_key_exists('label', $configuration)) {
            $action->set_label($configuration['label']);
        }
        if (array_key_exists('template', $configuration)) {
            $action->set_template($configuration['template']);
        }
        if (array_key_exists('icon', $configuration)) {
            $action->set_icon($configuration['icon']);
        }
        if (array_key_exists('enabled', $configuration)) {
            $action->set_enabled($configuration['enabled']);
        }
        if (array_key_exists('position', $configuration)) {
            $action->set_position($configuration['position']);
        }
        if (array_key_exists('options', $configuration)) {
            $action->set_options($configuration['options']);
        }
        return $action;
    }
    private function get_event_name(string $code): string
    {
        return sprintf(self::EVENT_NAME, str_replace('sylius_', '', $code));
    }
}