<?php

declare (strict_types=1);
namespace Pagerfanta\View\Template;

use Pagerfanta\Route_Generator\Route_Generator_Interface;
interface Template_Interface
{
    /**
     * Sets the route generator used while rendering the template.
     *
     *
     * @phpstan-param callable(int $page): string|RouteGeneratorInterface $routeGenerator
     */
    public function set_route_generator(callable $route_generator): void;
    /**
     * Sets the options for the template, overwriting keys that were previously set.
     *
     * @param array<string, mixed> $options
     */
    public function set_options(array $options): void;
    /**
     * Renders the container for the pagination.
     *
     * The %pages% placeholder will be replaced by the rendering of pages.
     */
    public function container(): string;
    /**
     * Renders a given page.
     */
    public function page(int $page): string;
    /**
     * Renders a given page with a specified text.
     */
    public function page_with_text(int $page, string $text, ?string $rel = null): string;
    /**
     * Renders the disabled state of the previous page.
     */
    public function previous_disabled(): string;
    /**
     * Renders the enabled state of the previous page.
     */
    public function previous_enabled(int $page): string;
    /**
     * Renders the disabled state of the next page.
     */
    public function next_disabled(): string;
    /**
     * Renders the enabled state of the next page.
     */
    public function next_enabled(int $page): string;
    /**
     * Renders the first page.
     */
    public function first(): string;
    /**
     * Renders the last page.
     */
    public function last(int $page): string;
    /**
     * Renders the current page.
     */
    public function current(int $page): string;
    /**
     * Renders the separator between pages.
     */
    public function separator(): string;
}