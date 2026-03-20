<?php

declare (strict_types=1);
namespace Pagerfanta;

use Pagerfanta\Adapter\Adapter_Interface;
use Pagerfanta\Exception\InvalidArgumentException;
use Pagerfanta\Exception\Less_Than1current_Page_Exception;
use Pagerfanta\Exception\Less_Than1max_Pages_Exception;
use Pagerfanta\Exception\Less_Than1max_Per_Page_Exception;
use Pagerfanta\Exception\LogicException;
use Pagerfanta\Exception\OutOfBoundsException;
use Pagerfanta\Exception\Out_Of_Range_Current_Page_Exception;
/**
 * @template T
 *
 * @implements PagerfantaInterface<T>
 */
class Pagerfanta implements Pagerfanta_Interface, \JsonSerializable
{
    private bool $allow_out_of_range_pages = false;
    private bool $normalize_out_of_range_pages = false;
    /**
     * @var positive-int
     */
    private int $max_per_page = 10;
    /**
     * @var positive-int
     */
    private int $current_page = 1;
    /**
     * @var int<0, max>|null
     */
    private ?int $nb_results = null;
    /**
     * @var positive-int|null
     */
    private ?int $max_nb_pages = null;
    /**
     * @var iterable<array-key, T>|null
     */
    private ?iterable $current_page_results = null;
    /**
     * @param AdapterInterface<T> $adapter
     */
    public function __construct(private readonly Adapter_Interface $adapter)
    {
    }
    /**
     * @param AdapterInterface<T> $adapter
     *
     * @psalm-param AdapterInterface<mixed> $adapter
     *
     * @return self<T>
     */
    public static function create_for_current_page_with_max_per_page(Adapter_Interface $adapter, int $current_page, int $max_per_page): self
    {
        $pagerfanta = new self($adapter);
        $pagerfanta->set_max_per_page($max_per_page);
        $pagerfanta->set_current_page($current_page);
        return $pagerfanta;
    }
    /**
     * @return AdapterInterface<T>
     */
    public function get_adapter(): Adapter_Interface
    {
        return $this->adapter;
    }
    /**
     * @return $this
     */
    public function set_allow_out_of_range_pages(bool $allow_out_of_range_pages): Pagerfanta_Interface
    {
        $this->allow_out_of_range_pages = $allow_out_of_range_pages;
        return $this;
    }
    public function get_allow_out_of_range_pages(): bool
    {
        return $this->allow_out_of_range_pages;
    }
    /**
     * @return $this
     */
    public function set_normalize_out_of_range_pages(bool $normalize_out_of_range_pages): Pagerfanta_Interface
    {
        $this->normalize_out_of_range_pages = $normalize_out_of_range_pages;
        return $this;
    }
    public function get_normalize_out_of_range_pages(): bool
    {
        return $this->normalize_out_of_range_pages;
    }
    /**
     * @return $this
     *
     * @throws LessThan1MaxPerPageException if the page is less than 1
     */
    public function set_max_per_page(int $max_per_page): Pagerfanta_Interface
    {
        $this->filter_max_per_page($max_per_page);
        \assert($max_per_page > 0);
        $this->max_per_page = $max_per_page;
        $this->reset_for_max_per_page_change();
        $this->filter_out_of_range_current_page($this->current_page);
        return $this;
    }
    private function filter_max_per_page(int $max_per_page): void
    {
        $this->check_max_per_page($max_per_page);
    }
    /**
     * @throws LessThan1MaxPerPageException if the page is less than 1
     */
    private function check_max_per_page(int $max_per_page): void
    {
        if ($max_per_page < 1) {
            throw new Less_Than1max_Per_Page_Exception();
        }
    }
    private function reset_for_max_per_page_change(): void
    {
        $this->current_page_results = null;
    }
    /**
     * @return positive-int
     */
    public function get_max_per_page(): int
    {
        return $this->max_per_page;
    }
    /**
     * @return $this
     *
     * @throws LessThan1CurrentPageException  if the current page is less than 1
     * @throws OutOfRangeCurrentPageException if It is not allowed out of range pages and they are not normalized
     */
    public function set_current_page(int $current_page): Pagerfanta_Interface
    {
        $this->current_page = $this->filter_current_page($current_page);
        $this->reset_for_current_page_change();
        return $this;
    }
    /**
     * @return positive-int
     */
    private function filter_current_page(int $current_page): int
    {
        $this->check_current_page($current_page);
        \assert($current_page > 0);
        return $this->filter_out_of_range_current_page($current_page);
    }
    /**
     * @throws LessThan1CurrentPageException if the current page is less than 1
     */
    private function check_current_page(int $current_page): void
    {
        if ($current_page < 1) {
            throw new Less_Than1current_Page_Exception();
        }
    }
    /**
     * @param positive-int $currentPage
     *
     * @return positive-int
     */
    private function filter_out_of_range_current_page(int $current_page): int
    {
        if ($this->not_allowed_current_page_out_of_range($current_page)) {
            return $this->normalize_out_of_range_current_page($current_page);
        }
        return $current_page;
    }
    private function not_allowed_current_page_out_of_range(int $current_page): bool
    {
        return !$this->get_allow_out_of_range_pages() && $this->current_page_out_of_range($current_page);
    }
    private function current_page_out_of_range(int $current_page): bool
    {
        return $current_page > 1 && $current_page > $this->get_nb_pages();
    }
    /**
     * @return positive-int
     *
     * @throws OutOfRangeCurrentPageException if the page should not be normalized
     */
    private function normalize_out_of_range_current_page(int $current_page): int
    {
        if ($this->get_normalize_out_of_range_pages()) {
            return $this->get_nb_pages();
        }
        throw new Out_Of_Range_Current_Page_Exception(\sprintf('Page "%d" does not exist. The currentPage must be inferior to "%d"', $current_page, $this->get_nb_pages()));
    }
    private function reset_for_current_page_change(): void
    {
        $this->current_page_results = null;
    }
    /**
     * @return positive-int
     */
    public function get_current_page(): int
    {
        return $this->current_page;
    }
    /**
     * @return iterable<array-key, T>
     */
    public function get_current_page_results(): iterable
    {
        return $this->current_page_results ??= $this->get_current_page_results_from_adapter();
    }
    /**
     * @return iterable<array-key, T>
     */
    private function get_current_page_results_from_adapter(): iterable
    {
        $offset = $this->calculate_offset_for_current_page_results();
        $length = $this->get_max_per_page();
        return $this->get_adapter()->get_slice($offset, $length);
    }
    /**
     * @return int<0, max>
     */
    private function calculate_offset_for_current_page_results(): int
    {
        return ($this->get_current_page() - 1) * $this->get_max_per_page();
    }
    /**
     * @return int<0, max>
     */
    public function get_current_page_offset_start(): int
    {
        return 0 !== $this->get_nb_results() ? $this->calculate_offset_for_current_page_results() + 1 : 0;
    }
    /**
     * @return int<0, max>
     */
    public function get_current_page_offset_end(): int
    {
        return $this->has_next_page() ? $this->get_current_page() * $this->get_max_per_page() : $this->get_nb_results();
    }
    /**
     * @return int<0, max>
     */
    public function get_nb_results(): int
    {
        return $this->nb_results ??= $this->get_adapter()->get_nb_results();
    }
    /**
     * @return positive-int
     */
    public function get_nb_pages(): int
    {
        $nb_pages = $this->calculate_nb_pages();
        if (0 === $nb_pages) {
            return $this->minimum_nb_pages();
        }
        if (null !== $this->max_nb_pages && $this->max_nb_pages < $nb_pages) {
            return $this->max_nb_pages;
        }
        return $nb_pages;
    }
    /**
     * @return int<0, max>
     */
    private function calculate_nb_pages(): int
    {
        return (int) ceil($this->get_nb_results() / $this->get_max_per_page());
    }
    /**
     * @return positive-int
     */
    private function minimum_nb_pages(): int
    {
        return 1;
    }
    /**
     * @return $this
     *
     * @throws LessThan1MaxPagesException if the max number of pages is less than 1
     */
    public function set_max_nb_pages(int $max_nb_pages): Pagerfanta_Interface
    {
        if ($max_nb_pages < 1) {
            throw new Less_Than1max_Pages_Exception();
        }
        $this->max_nb_pages = $max_nb_pages;
        return $this;
    }
    /**
     * @return $this
     */
    public function reset_max_nb_pages(): Pagerfanta_Interface
    {
        $this->max_nb_pages = null;
        return $this;
    }
    public function have_to_paginate(): bool
    {
        return $this->get_nb_results() > $this->max_per_page;
    }
    public function has_previous_page(): bool
    {
        return $this->current_page > 1;
    }
    /**
     * @return positive-int
     *
     * @throws LogicException if there is no previous page
     */
    public function get_previous_page(): int
    {
        if (!$this->has_previous_page()) {
            throw new LogicException('There is no previous page.');
        }
        \assert($this->current_page > 1);
        return $this->current_page - 1;
    }
    public function has_next_page(): bool
    {
        return $this->current_page < $this->get_nb_pages();
    }
    /**
     * @return positive-int
     *
     * @throws LogicException if there is no next page
     */
    public function get_next_page(): int
    {
        if (!$this->has_next_page()) {
            throw new LogicException('There is no next page.');
        }
        return $this->current_page + 1;
    }
    /**
     * @return int<0, max>
     */
    public function count(): int
    {
        return $this->get_nb_results();
    }
    /**
     * @return \Traversable<array-key, T>
     *
     * @throws InvalidArgumentException if an iterator cannot be created from the adapter slice
     */
    public function getIterator(): \Traversable
    {
        $results = $this->get_current_page_results();
        if ($results instanceof \Iterator) {
            return $results;
        }
        if ($results instanceof \IteratorAggregate) {
            return $results->getIterator();
        }
        if (\is_array($results)) {
            return new \ArrayIterator($results);
        }
        throw new InvalidArgumentException(\sprintf('Cannot create iterator with page results of type "%s".', get_debug_type($results)));
    }
    /**
     * @return T[]
     */
    public function jsonSerialize(): array
    {
        $results = $this->get_current_page_results();
        if ($results instanceof \Traversable) {
            return iterator_to_array($results);
        }
        return $results;
    }
    /**
     * Get page number of the item at specified position (1-based index).
     *
     * @param positive-int $position
     *
     * @return positive-int
     *
     * @throws OutOfBoundsException if the item is outside the result set
     */
    public function get_page_number_for_item_at_position(int $position): int
    {
        if ($this->get_nb_results() < $position) {
            throw new OutOfBoundsException(\sprintf('Item requested at position %d, but there are only %d items.', $position, $this->get_nb_results()));
        }
        return (int) ceil($position / $this->get_max_per_page());
    }
    /**
     * Generates an iterator to automatically iterate over all pages in a result set.
     *
     * @return \Generator<int, T, mixed, void>
     */
    public function auto_paging_iterator(): \Generator
    {
        while (true) {
            foreach ($this->get_current_page_results() as $item) {
                yield $item;
            }
            if (!$this->has_next_page()) {
                break;
            }
            $this->set_current_page($this->get_next_page());
        }
    }
}