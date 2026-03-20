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

use Sylius\Bundle\Grid_Bundle\Form\Registry\Form_Type_Registry_Interface;
use Sylius\Bundle\Grid_Bundle\Parser\Options_Parser_Interface;
use Sylius\Component\Grid\Definition\Action;
use Sylius\Component\Grid\Definition\Field;
use Sylius\Component\Grid\Definition\Filter;
use Sylius\Component\Grid\Field_Types\Field_Type_Interface;
use Sylius\Component\Grid\Renderer\Grid_Renderer_Interface;
use Sylius\Component\Grid\View\Grid_View_Interface;
use Sylius\Component\Registry\Service_Registry_Interface;
use Symfony\Component\Form\Extension\Core\Type\Form_Type;
use Symfony\Component\Form\Form_Factory_Interface;
use Symfony\Component\Options_Resolver\Options_Resolver;
use Twig\Environment;
final readonly class Twig_Grid_Renderer implements Grid_Renderer_Interface
{
    public function __construct(
        private Environment $twig,
        private Service_Registry_Interface $fields_registry,
        private Form_Factory_Interface $form_factory,
        private Form_Type_Registry_Interface $form_type_registry,
        private string $default_template,
        /** @var array<string, string> $actionTemplates */
        private array $action_templates = [],
        /** @var array<string, string> $filterTemplates */
        private array $filter_templates = [],
        private ?Options_Parser_Interface $options_parser = null
    )
    {
        if (null === $options_parser) {
            trigger_deprecation('sylius/grid-bundle', '1.14', 'Not passing an instance of "%s" as the eighth constructor argument of "%s" is deprecated.', Options_Parser_Interface::class, self::class);
        }
    }
    public function render(Grid_View_Interface $grid_view, ?string $template = null)
    {
        return $this->twig->render($template ?: $this->default_template, ['grid' => $grid_view]);
    }
    public function render_field(Grid_View_Interface $grid_view, Field $field, $data)
    {
        /** @var FieldTypeInterface $fieldType */
        $field_type = $this->fields_registry->get($field->get_type());
        $resolver = new Options_Resolver();
        $field_type->configure_options($resolver);
        $options = $field->get_options();
        if (null !== $this->options_parser) {
            $options = $this->options_parser->parse_options($options);
        }
        /** @var array<string, mixed> $options */
        $options = $resolver->resolve($options);
        return $field_type->render($field, $data, $options);
    }
    public function render_action(Grid_View_Interface $grid_view, Action $action, $data = null)
    {
        $type = $action->get_type();
        $template = $action->get_template() ?? $this->action_templates[$type] ?? null;
        if (null === $template) {
            throw new \InvalidArgumentException(sprintf('Missing template for action type "%s".', $type));
        }
        return $this->twig->render($template, ['grid' => $grid_view, 'action' => $action, 'data' => $data]);
    }
    public function render_filter(Grid_View_Interface $grid_view, Filter $filter)
    {
        $template = $this->get_filter_template($filter);
        $form = $this->form_factory->create_named('criteria', Form_Type::class, [], ['allow_extra_fields' => true, 'csrf_protection' => false, 'required' => false]);
        $form->add($filter->get_name(), $this->form_type_registry->get($filter->get_type(), 'default'), $filter->get_form_options());
        /** @var array<string, mixed> $criteria */
        $criteria = $grid_view->get_parameters()->get('criteria', []);
        $form->submit($criteria);
        return $this->twig->render($template, ['grid' => $grid_view, 'filter' => $filter, 'form' => $form->get($filter->get_name())->create_view()]);
    }
    /**
     * @throws \InvalidArgumentException
     */
    private function get_filter_template(Filter $filter): string
    {
        $template = $filter->get_template();
        if (null !== $template) {
            return $template;
        }
        $type = $filter->get_type();
        if (!isset($this->filter_templates[$type])) {
            throw new \InvalidArgumentException(sprintf('Missing template for filter type "%s".', $type));
        }
        return $this->filter_templates[$type];
    }
}