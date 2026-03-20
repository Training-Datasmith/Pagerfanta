<?php

declare (strict_types=1);
namespace Pagerfanta\View\Template;

use Pagerfanta\Exception\InvalidArgumentException;
use Pagerfanta\Exception\RuntimeException;
use Pagerfanta\Route_Generator\Route_Generator_Interface;
abstract class Template implements Template_Interface
{
    /**
     * @var array<string, mixed>
     */
    private array $options;
    /**
     * @var (callable(int): string)|RouteGeneratorInterface|null
     */
    private $route_generator;
    public function __construct()
    {
        $this->options = $this->get_default_options();
    }
    /**
     * Sets the route generator used while rendering the template.
     *
     *
     * @phpstan-param callable(int $page): string|RouteGeneratorInterface $routeGenerator
     */
    public function set_route_generator(callable $route_generator): void
    {
        $this->route_generator = $route_generator;
    }
    /**
     * Sets the options for the template, overwriting keys that were previously set.
     *
     * @param array<string, mixed> $options
     */
    public function set_options(array $options): void
    {
        $this->options = array_merge($this->options, $options);
    }
    /**
     * Generate the route (URL) for the given page, HTML-encoded for safe use in href attributes.
     */
    protected function generate_route(int $page): string
    {
        $generator = $this->get_route_generator();
        return htmlspecialchars($generator($page), \ENT_QUOTES | \ENT_SUBSTITUTE, 'UTF-8');
    }
    /**
     * @return array<string, mixed>
     */
    protected function get_default_options(): array
    {
        return [];
    }
    /**
     * @return (callable(int $page): string)|RouteGeneratorInterface
     *
     * @throws RuntimeException if the route generator has not been set
     */
    private function get_route_generator(): callable
    {
        if (!$this->route_generator) {
            throw new RuntimeException(\sprintf('The route generator was not set to the template, ensure you call %s::setRouteGenerator().', static::class));
        }
        return $this->route_generator;
    }
    /**
     * @return mixed The option value if it exists
     *
     * @throws InvalidArgumentException if the option does not exist
     */
    protected function option(string $name)
    {
        if (!isset($this->options[$name])) {
            throw new InvalidArgumentException(\sprintf('The option "%s" does not exist.', $name));
        }
        return $this->options[$name];
    }
}