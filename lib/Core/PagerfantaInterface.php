<?php

declare (strict_types=1);
namespace Pagerfanta;

use Pagerfanta\Adapter\Adapter_Interface;
use Pagerfanta\Exception\Less_Than1current_Page_Exception;
use Pagerfanta\Exception\Less_Than1max_Pages_Exception;
use Pagerfanta\Exception\Less_Than1max_Per_Page_Exception;
use Pagerfanta\Exception\LogicException;
use Pagerfanta\Exception\Out_Of_Range_Current_Page_Exception;
/**
 * @template-covariant T
 *
 * @extends \IteratorAggregate<T>
 *
 * @method \Generator<int, T, mixed, void> autoPagingIterator()
 */
interface Pagerfanta_Interface extends \Countable, \IteratorAggregate
{
    /**
     * @return AdapterInterface<T>
     */
    public function get_adapter(): Adapter_Interface;
    /**
     * @return $this
     */
    public function set_allow_out_of_range_pages(bool $allow_out_of_range_pages): self;
    public function get_allow_out_of_range_pages(): bool;
    /**
     * @return $this
     */
    public function set_normalize_out_of_range_pages(bool $normalize_out_of_range_pages): self;
    public function get_normalize_out_of_range_pages(): bool;
    /**
     * @return $this
     *
     * @throws LessThan1MaxPerPageException if the page is less than 1
     */
    public function set_max_per_page(int $max_per_page): self;
    /**
     * @return positive-int
     */
    public function get_max_per_page(): int;
    /**
     * @return $this
     *
     * @throws LessThan1CurrentPageException  if the current page is less than 1
     * @throws OutOfRangeCurrentPageException if It is not allowed out of range pages and they are not normalized
     */
    public function set_current_page(int $current_page): self;
    /**
     * @return positive-int
     */
    public function get_current_page(): int;
    /**
     * @return iterable<array-key, T>
     */
    public function get_current_page_results(): iterable;
    /**
     * @return int<0, max>
     */
    public function get_current_page_offset_start(): int;
    /**
     * @return int<0, max>
     */
    public function get_current_page_offset_end(): int;
    /**
     * @return int<0, max>
     */
    public function get_nb_results(): int;
    /**
     * @return positive-int
     */
    public function get_nb_pages(): int;
    /**
     * @return $this
     *
     * @throws LessThan1MaxPagesException if the max number of pages is less than 1
     */
    public function set_max_nb_pages(int $max_nb_pages): self;
    /**
     * @return $this
     */
    public function reset_max_nb_pages(): self;
    public function have_to_paginate(): bool;
    public function has_previous_page(): bool;
    /**
     * @return positive-int
     *
     * @throws LogicException if there is no previous page
     */
    public function get_previous_page(): int;
    public function has_next_page(): bool;
    /**
     * @return positive-int
     *
     * @throws LogicException if there is no next page
     */
    public function get_next_page(): int;
    /**
     * Get page number of the item at specified position (1-based index).
     *
     * @param positive-int $position
     *
     * @return positive-int
     */
    public function get_page_number_for_item_at_position(int $position): int;
}