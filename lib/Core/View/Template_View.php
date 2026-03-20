<?php

declare (strict_types=1);
namespace Pagerfanta\View;

use Pagerfanta\Pagerfanta_Interface;
use Pagerfanta\Route_Generator\Route_Generator_Interface;
use Pagerfanta\View\Template\Template_Interface;
abstract class Template_View extends View
{
    private readonly Template_Interface $template;
    public function __construct(?Template_Interface $template = null)
    {
        $this->template = $template ?? $this->create_default_template();
    }
    abstract protected function create_default_template(): Template_Interface;
    /**
     * @param PagerfantaInterface<mixed>       $pagerfanta
     * @param array<string, mixed>             $options
     * @phpstan-param callable(int $page): string|RouteGeneratorInterface $routeGenerator
     */
    public function render(Pagerfanta_Interface $pagerfanta, callable $route_generator, array $options = []): string
    {
        $this->initialize_pagerfanta($pagerfanta);
        $this->initialize_options($options);
        $this->configure_template($route_generator, $options);
        return $this->generate();
    }
    /**
     * @param callable(int $page): string|RouteGeneratorInterface $routeGenerator
     * @param array<string, mixed>                                $options
     */
    private function configure_template(callable|Route_Generator_Interface $route_generator, array $options): void
    {
        $this->template->set_route_generator($route_generator);
        $this->template->set_options($options);
    }
    private function generate(): string
    {
        return $this->generate_container($this->generate_pages());
    }
    private function generate_container(string $pages): string
    {
        return str_replace('%pages%', $pages, $this->template->container());
    }
    private function generate_pages(): string
    {
        $this->calculate_start_and_end_page();
        return $this->previous() . $this->first() . $this->second_if_start_is3() . $this->dots_if_start_is_over3() . $this->pages() . $this->dots_if_end_is_under3to_last() . $this->second_to_last_if_end_is3to_last() . $this->last() . $this->next();
    }
    private function previous(): string
    {
        if ($this->pagerfanta->has_previous_page()) {
            return $this->template->previous_enabled($this->pagerfanta->get_previous_page());
        }
        return $this->template->previous_disabled();
    }
    private function first(): string
    {
        if ($this->start_page > 1) {
            return $this->template->first();
        }
        return '';
    }
    private function second_if_start_is3(): string
    {
        if (3 === $this->start_page) {
            return $this->template->page(2);
        }
        return '';
    }
    private function dots_if_start_is_over3(): string
    {
        if ($this->start_page > 3) {
            return $this->template->separator();
        }
        return '';
    }
    private function pages(): string
    {
        \assert(null !== $this->start_page);
        \assert(null !== $this->end_page);
        $pages = '';
        foreach (range($this->start_page, $this->end_page) as $page) {
            $pages .= $this->page($page);
        }
        return $pages;
    }
    private function page(int $page): string
    {
        if ($page === $this->current_page) {
            return $this->template->current($page);
        }
        return $this->template->page($page);
    }
    private function dots_if_end_is_under3to_last(): string
    {
        if ($this->end_page < $this->to_last(3)) {
            return $this->template->separator();
        }
        return '';
    }
    private function second_to_last_if_end_is3to_last(): string
    {
        if ($this->end_page == $this->to_last(3)) {
            return $this->template->page($this->to_last(2));
        }
        return '';
    }
    private function last(): string
    {
        if ($this->pagerfanta->get_nb_pages() > $this->end_page) {
            return $this->template->last($this->pagerfanta->get_nb_pages());
        }
        return '';
    }
    private function next(): string
    {
        if ($this->pagerfanta->has_next_page()) {
            return $this->template->next_enabled($this->pagerfanta->get_next_page());
        }
        return $this->template->next_disabled();
    }
}