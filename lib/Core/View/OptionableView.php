<?php

declare (strict_types=1);
namespace Pagerfanta\View;

use Pagerfanta\Pagerfanta_Interface;
use Pagerfanta\Route_Generator\Route_Generator_Interface;
/**
 * Decorator for a view with a default options list, enables re-use of option configurations.
 */
class Optionable_View implements View_Interface
{
    /**
     * @param array<string, mixed> $defaultOptions
     */
    public function __construct(private readonly View_Interface $view, private readonly array $default_options)
    {
    }
    /**
     * @param array<string, mixed> $options
     *
     * @phpstan-param callable(int $page): string|RouteGeneratorInterface $routeGenerator
     */
    public function render(Pagerfanta_Interface $pagerfanta, callable $route_generator, array $options = []): string
    {
        return $this->view->render($pagerfanta, $route_generator, [...$this->default_options, ...$options]);
    }
    public function get_name(): string
    {
        return 'optionable';
    }
}