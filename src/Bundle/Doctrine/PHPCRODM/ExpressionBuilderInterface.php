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
namespace Sylius\Bundle\Grid_Bundle\Doctrine\PHPCRODM;

use Sylius\Component\Grid\Data\Member_Of_Aware_Expression_Builder_Interface;
@trigger_error(sprintf('The "%s" class is deprecated since Sylius 1.3. Doctrine MongoDB and PHPCR support will no longer be supported in Sylius 2.0.', Expression_Builder_Interface::class), \E_USER_DEPRECATED);
interface Expression_Builder_Interface extends Member_Of_Aware_Expression_Builder_Interface
{
    public function get_order_bys(): array;
}