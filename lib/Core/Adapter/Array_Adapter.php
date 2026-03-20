<?php

declare (strict_types=1);
namespace Pagerfanta\Adapter;

/**
 * Adapter which calculates pagination from an array of items.
 *
 * @template T
 *
 * @implements AdapterInterface<T>
 */
class Array_Adapter implements Adapter_Interface
{
    /**
     * @param array<T> $array
     */
    public function __construct(private readonly array $array)
    {
    }
    /**
     * Returns the total number of items in the array.
     *
     * @return int<0, max> Total item count
     *
     * @complexity O(1) — PHP's count() on arrays is O(1)
     */
    public function get_nb_results(): int
    {
        return \count($this->array);
    }

    /**
     * Returns a slice of the array starting at the given offset.
     *
     * @param int<0, max> $offset Zero-based start position of the slice
     * @param int<0, max> $length Maximum number of items to return
     *
     * @return iterable<array-key, T> The requested slice of items
     *
     * @complexity O(length) — array_slice copies the requested elements
     */
    public function get_slice(int $offset, int $length): iterable
    {
        return \array_slice($this->array, $offset, $length);
    }
}