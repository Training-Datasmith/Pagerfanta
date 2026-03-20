<?php

declare (strict_types=1);
namespace Pagerfanta\Twig\Extension;

use Pagerfanta\Exception\Out_Of_Range_Current_Page_Exception;
use Pagerfanta\Pagerfanta_Interface;
use Pagerfanta\Route_Generator\Route_Generator_Factory_Interface;
use Pagerfanta\Route_Generator\Route_Generator_Interface;
use Pagerfanta\View\View_Factory_Interface;
use Twig\Extension\Runtime_Extension_Interface;
final class Pagerfanta_Runtime implements Runtime_Extension_Interface
{
    public function __construct(private readonly string $default_view, private readonly View_Factory_Interface $view_factory, private readonly Route_Generator_Factory_Interface $route_generator_factory)
    {
    }
    /**
     * @param PagerfantaInterface<mixed>       $pagerfanta
     * @param string|array<string, mixed>|null $viewName   The name of the view to render, or the options array
     * @param array<string, mixed>             $options
     */
    public function render_pagerfanta(Pagerfanta_Interface $pagerfanta, string|array|null $view_name = null, array $options = []): string
    {
        if (\is_array($view_name)) {
            $options = $view_name;
            $view_name = null;
        }
        $view_name = $view_name ?: $this->default_view;
        return $this->view_factory->get($view_name)->render($pagerfanta, $this->create_route_generator($options), $options);
    }
    /**
     * @param PagerfantaInterface<mixed> $pagerfanta
     * @param array<string, mixed>       $options
     *
     * @throws OutOfRangeCurrentPageException if the page is out of bounds
     */
    public function get_page_url(Pagerfanta_Interface $pagerfanta, int $page, array $options = []): string
    {
        if ($page < 0 || $page > $pagerfanta->get_nb_pages()) {
            throw new Out_Of_Range_Current_Page_Exception("Page '{$page}' is out of bounds");
        }
        $route_generator = $this->create_route_generator($options);
        return $route_generator($page);
    }
    /**
     * @param array<string, mixed> $options
     */
    private function create_route_generator(array $options = []): Route_Generator_Interface
    {
        return $this->route_generator_factory->create($options);
    }
}