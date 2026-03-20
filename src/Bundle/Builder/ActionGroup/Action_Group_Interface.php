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
interface Action_Group_Interface
{
    public const MAIN_GROUP = 'main';
    public const ITEM_GROUP = 'item';
    public const SUB_ITEM_GROUP = 'subitem';
    public const BULK_GROUP = 'bulk';
    public static function create(string $name, Action_Interface ...$actions): self;
    public function get_name(): string;
    public function add_action(Action_Interface $action): self;
    public function remove_action(string $name): self;
    /**
     * @return array<string, mixed>
     */
    public function to_array(): array;
}