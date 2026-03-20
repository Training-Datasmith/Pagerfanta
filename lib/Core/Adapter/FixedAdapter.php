<?php

declare (strict_types=1);
namespace Pagerfanta\Adapter;

use Pagerfanta\Exception\Not_Valid_Result_Count_Exception;
/**
 * Adapter which returns a fixed data set.
 *
 * Best used when you need to do a custom paging solution and don't want to implement a full adapter for a one-off use case.
 *
 * @template T
 *
 * @implements AdapterInterface<T>
 */
class Fixed_Adapter implements Adapter_Interface
{
    /**
     * @var int<0, max>
     */
    private readonly int $nb_results;
    /**
     * @param iterable<array-key, T> $results
     *
     * @throws NotValidResultCountException if the number of results is less than zero
     */
    public function __construct(int $nb_results, private readonly iterable $results)
    {
        if ($nb_results < 0) {
            throw new Not_Valid_Result_Count_Exception(\sprintf('The number of results for the "%s" constructor must be at least zero.', static::class));
        }
        $this->nb_results = $nb_results;
    }
    /**
     * @return int<0, max>
     */
    public function get_nb_results(): int
    {
        return $this->nb_results;
    }
    /**
     * @param int<0, max> $offset
     * @param int<0, max> $length
     *
     * @return iterable<array-key, T>
     */
    public function get_slice(int $offset, int $length): iterable
    {
        return $this->results;
    }
}