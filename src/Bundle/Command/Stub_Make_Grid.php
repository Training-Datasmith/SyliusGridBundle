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
namespace Sylius\Bundle\Grid_Bundle\Command;

use Symfony\Component\Console\Attribute\As_Command;
#[As_Command(name: 'make:grid')]
final class Stub_Make_Grid extends Stub_Command
{
}