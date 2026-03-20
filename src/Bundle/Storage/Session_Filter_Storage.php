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
namespace Sylius\Bundle\Grid_Bundle\Storage;

use Symfony\Component\Http_Foundation\Request_Stack;
use Symfony\Component\Http_Foundation\Session\Session_Interface;
final readonly class Session_Filter_Storage implements Filter_Storage_Interface
{
    public function __construct(private Request_Stack $request_stack)
    {
    }
    public function set(array $filters): void
    {
        $this->get_session()->set('filters', $filters);
    }
    public function all(): array
    {
        /** @var array<string, mixed> $all */
        $all = $this->get_session()->all()['filters'] ?? [];
        return $all;
    }
    public function has_filters(): bool
    {
        return [] !== $this->get_session()->get('filters', []);
    }
    private function get_session(): Session_Interface
    {
        return $this->request_stack->get_session();
    }
}