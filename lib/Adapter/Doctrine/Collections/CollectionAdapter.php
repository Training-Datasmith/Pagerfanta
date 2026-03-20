<?php

declare (strict_types=1);
namespace Pagerfanta\Doctrine\Collections;

use Doctrine\Common\Collections\Readable_Collection;
use Pagerfanta\Adapter\Adapter_Interface;
/**
 * Adapter which calculates pagination from a Doctrine Collection.
 *
 * @template TKey of array-key
 * @template T
 *
 * @implements AdapterInterface<T>
 */
class Collection_Adapter implements Adapter_Interface
{
    /**
     * @param ReadableCollection<TKey, T> $collection
     */
    public function __construct(private readonly Readable_Collection $collection)
    {
    }
    /**
     * @return int<0, max>
     */
    public function get_nb_results(): int
    {
        return $this->collection->count();
    }
    /**
     * @param int<0, max> $offset
     * @param int<0, max> $length
     *
     * @return iterable<TKey, T>
     */
    public function get_slice(int $offset, int $length): iterable
    {
        return $this->collection->slice($offset, $length);
    }
}