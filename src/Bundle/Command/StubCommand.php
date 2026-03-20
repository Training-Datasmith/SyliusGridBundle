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

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\Input_Interface;
use Symfony\Component\Console\Output\Output_Interface;
use Symfony\Component\Console\Style\Symfony_Style;
abstract class Stub_Command extends Command
{
    protected function execute(Input_Interface $input, Output_Interface $output): int
    {
        (new Symfony_Style($input, $output))->error(\sprintf("To run command you need the \"%s\" which is currently not installed.\n\nTry running \"composer require %s\".", 'MakerBundle', 'symfony/maker-bundle --dev'));
        return Command::SUCCESS;
    }
}