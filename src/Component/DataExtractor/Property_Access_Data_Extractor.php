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
namespace Sylius\Component\Grid\Data_Extractor;

use Sylius\Component\Grid\Definition\Field;
use Symfony\Component\Property_Access\Property_Accessor_Interface;
final readonly class Property_Access_Data_Extractor implements Data_Extractor_Interface
{
    private Property_Accessor_Interface $property_accessor;
    public function __construct(Property_Accessor_Interface $property_accessor)
    {
        $this->property_accessor = $property_accessor;
    }
    /**
     * @param mixed[]|object $data
     */
    public function get(Field $field, $data)
    {
        return $this->property_accessor->get_value($data, $field->get_path());
    }
}