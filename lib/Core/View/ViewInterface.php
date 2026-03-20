<?php

declare (strict_types=1);
namespace Pagerfanta\View;

use Pagerfanta\Pagerfanta_Interface;
use Pagerfanta\Route_Generator\Route_Generator_Interface;
interface View_Interface
{
    /**
     * @param PagerfantaInterface<mixed>       $pagerfanta
     * @param array<string, mixed>             $options
     * @phpstan-param callable(int $page): string|RouteGeneratorInterface $routeGenerator
     */
    public function render(Pagerfanta_Interface $pagerfanta, callable $route_generator, array $options = []): string;
    public function get_name(): string;
}