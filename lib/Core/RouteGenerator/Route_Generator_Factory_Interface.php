<?php

declare (strict_types=1);
namespace Pagerfanta\Route_Generator;

use Pagerfanta\Exception\RuntimeException;
interface Route_Generator_Factory_Interface
{
    /**
     * @param array<string, mixed> $options
     *
     * @throws RuntimeException if the route generator cannot be created
     */
    public function create(array $options = []): Route_Generator_Interface;
}