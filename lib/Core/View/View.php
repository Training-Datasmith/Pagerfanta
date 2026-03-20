<?php

declare (strict_types=1);
namespace Pagerfanta\View;

use Pagerfanta\Pagerfanta_Interface;
abstract class View implements View_Interface
{
    /**
     * @var PagerfantaInterface<mixed>
     */
    protected Pagerfanta_Interface $pagerfanta;
    /**
     * @var positive-int|null
     */
    protected ?int $current_page = null;
    /**
     * @var positive-int|null
     */
    protected ?int $nb_pages = null;
    protected ?int $proximity = null;
    /**
     * @var positive-int|null
     */
    protected ?int $start_page = null;
    /**
     * @var positive-int|null
     */
    protected ?int $end_page = null;
    /**
     * @param PagerfantaInterface<mixed> $pagerfanta
     */
    protected function initialize_pagerfanta(Pagerfanta_Interface $pagerfanta): void
    {
        $this->pagerfanta = $pagerfanta;
        $this->current_page = $pagerfanta->get_current_page();
        $this->nb_pages = $pagerfanta->get_nb_pages();
    }
    /**
     * @param array<string, mixed> $options
     */
    protected function initialize_options(array $options): void
    {
        $this->proximity = isset($options['proximity']) ? (int) $options['proximity'] : $this->get_default_proximity();
    }
    protected function get_default_proximity(): int
    {
        return 2;
    }
    protected function calculate_start_and_end_page(): void
    {
        \assert(null !== $this->current_page);
        \assert(null !== $this->proximity);
        $start_page = $this->current_page - $this->proximity;
        $end_page = $this->current_page + $this->proximity;
        if ($this->start_page_underflow($start_page)) {
            $end_page = $this->calculate_end_page_for_start_page_underflow($start_page, $end_page);
            $start_page = 1;
        }
        if ($this->end_page_overflow($end_page)) {
            $start_page = $this->calculate_start_page_for_end_page_overflow($start_page, $end_page);
            $end_page = $this->nb_pages;
        }
        \assert($start_page >= 1);
        \assert($end_page >= 1);
        $this->start_page = $start_page;
        $this->end_page = $end_page;
    }
    protected function start_page_underflow(int $start_page): bool
    {
        return $start_page < 1;
    }
    protected function end_page_overflow(int $end_page): bool
    {
        return $end_page > $this->nb_pages;
    }
    /**
     * @return positive-int
     */
    protected function calculate_end_page_for_start_page_underflow(int $start_page, int $end_page): int
    {
        \assert(null !== $this->nb_pages);
        return min($end_page + (1 - $start_page), $this->nb_pages);
    }
    /**
     * @return positive-int
     */
    protected function calculate_start_page_for_end_page_overflow(int $start_page, int $end_page): int
    {
        return max($start_page - ($end_page - $this->nb_pages), 1);
    }
    protected function to_last(int $n): int
    {
        return $this->pagerfanta->get_nb_pages() - ($n - 1);
    }
}