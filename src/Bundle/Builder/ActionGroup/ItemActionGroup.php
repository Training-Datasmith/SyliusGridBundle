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
namespace Sylius\Bundle\Grid_Bundle\Builder\Action_Group;

use Sylius\Bundle\Grid_Bundle\Builder\Action\Action_Interface;
final class Item_Action_Group
{
    public static function create(Action_Interface ...$actions): Action_Group_Interface
    {
        return Action_Group::create(Action_Group_Interface::ITEM_GROUP, ...$actions);
    }
}