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
namespace Sylius\Bundle\Grid_Bundle\Builder\Filter;

use Sylius\Component\Grid\Filter\String_Filter as GridStringFilter;
final class String_Filter
{
    public const NAME = Grid_String_Filter::NAME;
    public const TYPE_EQUAL = Grid_String_Filter::TYPE_EQUAL;
    public const TYPE_NOT_EQUAL = Grid_String_Filter::TYPE_NOT_EQUAL;
    public const TYPE_EMPTY = Grid_String_Filter::TYPE_EMPTY;
    public const TYPE_NOT_EMPTY = Grid_String_Filter::TYPE_NOT_EMPTY;
    public const TYPE_CONTAINS = Grid_String_Filter::TYPE_CONTAINS;
    public const TYPE_NOT_CONTAINS = Grid_String_Filter::TYPE_NOT_CONTAINS;
    public const TYPE_STARTS_WITH = Grid_String_Filter::TYPE_STARTS_WITH;
    public const TYPE_ENDS_WITH = Grid_String_Filter::TYPE_ENDS_WITH;
    public const TYPE_IN = Grid_String_Filter::TYPE_IN;
    public const TYPE_NOT_IN = Grid_String_Filter::TYPE_NOT_IN;
    /**
     * @param string[]|null $fields
     * @param mixed $type
     */
    public static function create(string $name, ?array $fields = null, $type = null): Filter_Interface
    {
        $filter = Filter::create($name, 'string');
        if (null !== $fields) {
            $filter->set_options(['fields' => $fields]);
        }
        if (null !== $type) {
            $filter->set_form_options(['type' => $type]);
        }
        return $filter;
    }
}