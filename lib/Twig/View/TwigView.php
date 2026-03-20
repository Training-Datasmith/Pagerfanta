<?php

declare (strict_types=1);
namespace Pagerfanta\Twig\View;

use Pagerfanta\Pagerfanta_Interface;
use Pagerfanta\Route_Generator\Route_Generator_Decorator;
use Pagerfanta\Route_Generator\Route_Generator_Interface;
use Pagerfanta\View\View;
use Twig\Environment;
final class Twig_View extends View
{
    public const DEFAULT_TEMPLATE = '@Pagerfanta/default.html.twig';
    private string $template = self::DEFAULT_TEMPLATE;
    public function __construct(private readonly Environment $twig, private readonly ?string $default_template = null)
    {
    }
    public function get_name(): string
    {
        return 'twig';
    }
    /**
     * @param PagerfantaInterface<mixed>       $pagerfanta
     * @param array<string, mixed>             $options
     * @phpstan-param callable(int $page): string|RouteGeneratorInterface $routeGenerator
     */
    public function render(Pagerfanta_Interface $pagerfanta, callable $route_generator, array $options = []): string
    {
        $this->initialize_pagerfanta($pagerfanta);
        $this->initialize_options($options);
        $this->calculate_start_and_end_page();
        return $this->twig->load($this->template)->render_block('pager_widget', ['pagerfanta' => $pagerfanta, 'route_generator' => $this->decorate_route_generator($route_generator), 'options' => $options, 'start_page' => $this->start_page, 'end_page' => $this->end_page, 'current_page' => $this->current_page, 'nb_pages' => $this->nb_pages]);
    }
    /**
     * @param (callable(int $page): string)|RouteGeneratorInterface $routeGenerator
     */
    private function decorate_route_generator(callable|Route_Generator_Interface $route_generator): Route_Generator_Decorator
    {
        return new Route_Generator_Decorator($route_generator);
    }
    /**
     * @param array<string, mixed> $options
     */
    protected function initialize_options(array $options): void
    {
        if (isset($options['template'])) {
            $this->template = $options['template'];
        } elseif (null !== $this->default_template) {
            $this->template = $this->default_template;
        }
        parent::initialize_options($options);
    }
}