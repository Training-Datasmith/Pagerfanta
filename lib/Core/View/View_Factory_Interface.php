<?php

declare (strict_types=1);
namespace Pagerfanta\View;

use Pagerfanta\Exception\InvalidArgumentException;
interface View_Factory_Interface
{
    /**
     * @param array<string, ViewInterface> $views
     */
    public function add(array $views): void;
    /**
     * @return array<string, ViewInterface>
     */
    public function all(): array;
    public function clear(): void;
    /**
     * @throws InvalidArgumentException if the view does not exist
     */
    public function get(string $name): View_Interface;
    public function has(string $name): bool;
    public function remove(string $name): void;
    public function set(string $name, View_Interface $view): void;
}