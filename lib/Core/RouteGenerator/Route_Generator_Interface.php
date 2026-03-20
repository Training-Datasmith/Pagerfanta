<?php

declare (strict_types=1);
namespace Pagerfanta\Route_Generator;

interface Route_Generator_Interface
{
    /**
     * Generates the URL for a page item in a paginator.
     *
     * @return string The page URL
     */
    public function __invoke(int $page): string;
}