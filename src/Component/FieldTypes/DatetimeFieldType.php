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

use Sylius\Component\Grid\Data_Extractor\Data_Extractor_Interface;
use Sylius\Component\Grid\Definition\Field;
use Symfony\Component\Options_Resolver\Options_Resolver;
use Webmozart\Assert\Assert;
final readonly class Datetime_Field_Type implements Field_Type_Interface
{
    public function __construct(private Data_Extractor_Interface $data_extractor, private ?string $timezone = null)
    {
    }
    /**
     * @throws \InvalidArgumentException
     */
    public function render(Field $field, mixed $data, array $options): string
    {
        $value = $this->data_extractor->get($field, $data);
        if (null === $value) {
            return '';
        }
        /** @var \DateTimeImmutable|\DateTime $value */
        Assert::is_instance_of($value, \DateTimeInterface::class);
        if (null !== $options['timezone']) {
            /** @var string $timezone */
            $timezone = $options['timezone'];
            $value = $value->set_timezone(new \DateTimeZone($timezone));
        }
        /** @var string $format */
        $format = $options['format'];
        return $value->format($format);
    }
    public function configure_options(Options_Resolver $resolver): void
    {
        $resolver->set_default('format', 'Y-m-d H:i:s');
        $resolver->set_allowed_types('format', 'string');
        $resolver->set_default('timezone', $this->timezone);
        $resolver->set_allowed_types('timezone', ['null', 'string']);
        $resolver->set_defined('vars');
        $resolver->set_allowed_types('vars', 'array');
    }
}