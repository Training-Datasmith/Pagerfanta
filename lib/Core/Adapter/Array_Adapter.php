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
     * @return int<0, max>
     */
    public function get_nb_results(): int
    {
        return \count($this->array);
    }
    /**
     * @param int<0, max> $offset
     * @param int<0, max> $length
     *
     * @return iterable<array-key, T>
     */
    public function get_slice(int $offset, int $length): iterable
    {
        return \array_slice($this->array, $offset, $length);
    }
}