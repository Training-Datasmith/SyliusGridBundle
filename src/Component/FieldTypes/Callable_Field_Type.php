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
namespace Sylius\Component\Grid\Field_Types;

use Psr\Container\Container_Interface;
use Sylius\Component\Grid\Data_Extractor\Data_Extractor_Interface;
use Sylius\Component\Grid\Definition\Field;
use Sylius\Component\Grid\Exception\UnexpectedValueException;
use Symfony\Component\Options_Resolver\Options_Resolver;
final readonly class Callable_Field_Type implements Field_Type_Interface
{
    public function __construct(private Data_Extractor_Interface $data_extractor, private Container_Interface $locator)
    {
    }
    public function render(Field $field, mixed $data, array $options): string
    {
        if (isset($options['callable']) === isset($options['service'])) {
            throw new \RuntimeException('Exactly one of the "callable" or "service" options must be defined.');
        }
        $value = $this->data_extractor->get($field, $data);
        $value = call_user_func($this->get_callable($options), $value);
        if (!is_scalar($value) && null !== $value && !(is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedValueException(\sprintf('Callable field (name "%s") returned value could not be converted to string.', $field->get_name()));
        }
        $value = (string) $value;
        if ($options['htmlspecialchars']) {
            return htmlspecialchars($value);
        }
        return $value;
    }
    /**
     * @param array<string, mixed> $options
     */
    private function get_callable(array $options): callable
    {
        if (isset($options['callable'])) {
            return $options['callable'];
        }
        /** @var string $serviceId */
        $service_id = $options['service'];
        if (!$this->locator->has($service_id)) {
            throw new \RuntimeException(sprintf('Service "%s" not found, make sure it is tagged with "sylius.grid_field_callable_service".', $service_id));
        }
        $service = $this->locator->get($service_id);
        if (isset($options['method'])) {
            /** @var string $method */
            $method = $options['method'];
            $callable = [$service, $method];
            if (!is_callable($callable)) {
                throw new \RuntimeException(sprintf('The method "%s" is not callable on service "%s".', $method, $service_id));
            }
            return $callable;
        }
        if (!is_callable($service)) {
            throw new \RuntimeException(sprintf('The service "%s" is not callable.', $service_id));
        }
        return $service;
    }
    public function configure_options(Options_Resolver $resolver): void
    {
        $resolver->set_defined('callable');
        $resolver->set_allowed_types('callable', 'callable');
        $resolver->set_defined('service');
        $resolver->set_allowed_types('service', 'string');
        $resolver->set_defined('method');
        $resolver->set_allowed_types('method', 'string');
        $resolver->set_default('htmlspecialchars', true);
        $resolver->set_allowed_types('htmlspecialchars', 'bool');
    }
}